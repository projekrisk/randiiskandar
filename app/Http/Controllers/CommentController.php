<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class CommentController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        if (! empty($request->input('hp_company_name'))) {
            return back()->with('success', 'Komentar Anda telah diterima dan sedang menunggu moderasi.');
        }

        $ip = $request->ip();
        $rateLimitKey = 'submit-comment:'.$ip;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            return back()->withErrors([
                'comment' => "Terlalu banyak aktivitas. Silakan tunggu {$seconds} detik sebelum mengirim komentar kembali.",
            ])->withInput();
        }

        RateLimiter::hit($rateLimitKey, 120);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:75'],
            'email' => ['required', 'email', 'max:100'],
            'website' => ['nullable', 'url', 'max:150'],
            'comment' => ['required', 'string', 'min:3', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        if (! empty($validated['parent_id'])) {
            $parent = Comment::find($validated['parent_id']);
            if (! $parent || $parent->post_id !== $post->id) {
                return back()->withErrors(['comment' => 'Komentar induk tidak valid.'])->withInput();
            }
        }

        $bannedWordsSetting = Setting::get('banned_words', 'judi, slot, spam, penipuan, gacor, bokep, obat terlarang');
        if (! empty($bannedWordsSetting)) {
            $bannedList = array_filter(array_map('trim', explode(',', $bannedWordsSetting)));
            $commentLower = mb_strtolower($validated['comment']);
            $nameLower = mb_strtolower($validated['name']);

            foreach ($bannedList as $bannedWord) {
                if ($bannedWord !== '' && (str_contains($commentLower, mb_strtolower($bannedWord)) || str_contains($nameLower, mb_strtolower($bannedWord)))) {
                    return back()->withErrors([
                        'comment' => "Komentar Anda tidak dapat dikirim karena terdeteksi memuat kata terlarang (\"{$bannedWord}\").",
                    ])->withInput();
                }
            }
        }

        $isAuthor = Auth::check();

        Comment::create([
            'post_id' => $post->id,
            'user_id' => $isAuthor ? Auth::id() : null,
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $isAuthor ? Auth::user()->name : strip_tags($validated['name']),
            'email' => $isAuthor ? Auth::user()->email : $validated['email'],
            'website' => $validated['website'] ?? null,
            'comment' => strip_tags($validated['comment']),
            'is_approved' => $isAuthor ? true : false,
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
        ]);

        $message = $isAuthor
            ? 'Balasan komentar berhasil dipublikasikan.'
            : 'Terima kasih! Komentar Anda telah terkirim dan akan tampil setelah disetujui.';

        return back()->with('success', $message);
    }
}
