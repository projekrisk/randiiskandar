<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $author = User::first();

        $featuredPost = Post::query()
            ->with(['author', 'category', 'categories'])
            ->withCount(['comments' => fn ($q) => $q->where('is_approved', true)])
            ->featured()
            ->first();

        $latestPostsQuery = Post::query()
            ->with(['author', 'category', 'categories'])
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
