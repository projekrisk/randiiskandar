<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan Halaman Utama (Homepage):
     * - Profil Penulis
     * - Featured Post (Sorotan)
     * - Terbitan Terbaru (dengan Pagination & Eager Loading untuk mencegah N+1)
     */
    public function __invoke(Request $request): View
    {
        $author = User::first();

        $featuredPost = Post::query()
            ->with(['author', 'category'])
            ->withCount(['comments' => fn ($q) => $q->where('is_approved', true)])
            ->featured()
            ->first();

        // Ambil terbitan terbaru kecuali artikel yang sedang disorot (featured)
        $latestPostsQuery = Post::query()
            ->with(['author', 'category'])
            ->withCount(['comments' => fn ($q) => $q->where('is_approved', true)])
            ->published();

        if ($featuredPost) {
            $latestPostsQuery->where('id', '!=', $featuredPost->id);
        }

        $latestPosts = $latestPostsQuery->paginate(5);

        return view('pages.home', [
            'author' => $author,
            'featuredPost' => $featuredPost,
            'latestPosts' => $latestPosts,
        ]);
    }
}
