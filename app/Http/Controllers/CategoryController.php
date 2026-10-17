<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = $category->posts()
            ->published()
            ->with(['author', 'category', 'categories'])
            ->withCount(['comments' => fn ($q) => $q->where('is_approved', true)])
            ->paginate(5);

        return view('pages.category', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }

    public function index(Request $request): View
    {
        $categories = Category::withCount(['posts' => fn ($q) => $q->published()])->get();

        $allPostsCount = Cache::remember('all_published_posts_count', 3600, fn () => Post::published()->count());

        $posts = Post::query()
            ->published()
            ->with(['author', 'category', 'categories'])
            ->withCount(['comments' => fn ($q) => $q->where('is_approved', true)])
            ->paginate(5);

        return view('pages.archive', [
            'categories' => $categories,
            'posts' => $posts,
            'allPostsCount' => $allPostsCount,
        ]);
    }
}
