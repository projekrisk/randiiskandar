<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', 'all');
        $tab = $request->input('tab', 'comments');

        $query = Comment::query()->with(['post', 'author'])->latest();

        if ($status === 'pending') {
            $query->pending();
        } elseif ($status === 'approved') {
            $query->approved();
        }

        $comments = $query->paginate(10);
        $bannedWords = Setting::get('banned_words', 'judi, slot, spam, penipuan, gacor, bokep, obat terlarang');

        return view('admin.comments.index', [
            'comments' => $comments,
            'currentStatus' => $status,
            'currentTab' => $tab,
            'pendingCount' => Comment::pending()->count(),
            'bannedWords' => $bannedWords,
        ]);
    }

    public function updateFilter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'banned_words' => ['nullable', 'string', 'max:5000'],
        ]);

        Setting::set('banned_words', $validated['banned_words'] ?? '');

        return redirect()->route('admin.comments.index', ['tab' => 'filter'])->with('success', 'Filter kata terlarang berhasil diperbarui.');
    }

    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => true]);

        return back()->with('success', 'Komentar telah berhasil disetujui.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Komentar telah berhasil dihapus.');
    }
}
