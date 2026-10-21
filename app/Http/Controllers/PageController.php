<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about(): View
    {
        $page = Page::published()->whereIn('slug', ['about', 'tentang'])->first();

        if (! $page) {
            $page = new Page([
                'title' => 'Tentang Randi Iskandar',
                'slug' => 'about',
                'body' => '<p>Halo! Perkenalkan, saya <strong>Randi Iskandar</strong>, Saya senang mengeksplorasi teknologi digital, mencoba hal-hal baru, dan berbagi cerita tentang apa saja yang menarik untuk dibahas.</p>',
                'is_published' => true,
            ]);
        }

        return view('pages.show', [
            'page' => $page,
        ]);
    }

    public function contact(): View
    {
        $author = User::first();

        return view('pages.contact', [
            'author' => $author,
        ]);
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        return back()->with('success', 'Pesan Anda telah berhasil dikirim. Terima kasih telah menghubungi saya!');
    }

    public function terms(): View
    {
        $page = Page::published()->whereIn('slug', ['terms', 'term'])->first();

        return view('pages.terms', [
            'page' => $page,
        ]);
    }

    public function disclaimer(): View
    {
        $page = Page::published()->whereIn('slug', ['disclaimer', 'sanggahan'])->first();

        return view('pages.disclaimer', [
            'page' => $page,
        ]);
    }

    public function privacy(): View
    {
        $page = Page::published()->whereIn('slug', ['privasi', 'privacy'])->first();

        if (! $page) {
            $page = new Page([
                'title' => 'Kebijakan Privasi',
                'slug' => 'privasi',
                'body' => '<p>Privasi Anda sebagai pengunjung situs <strong>Randi Iskandar</strong> adalah hal yang diutamakan. Dokumen ini menjelaskan jenis data yang dicatat serta bagaimana informasi tersebut dikelola.</p>',
                'meta_description' => 'Kebijakan privasi dan perlindungan data pengunjung blog Randi Iskandar.',
                'is_published' => true,
            ]);
        }

        return view('pages.show', [
            'page' => $page,
        ]);
    }

    public function show(string $slug): View
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        return view('pages.show', [
            'page' => $page,
        ]);
    }
}
