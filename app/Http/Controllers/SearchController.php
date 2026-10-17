<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse|View
    {
        $query = trim((string) $request->input('q', ''));

        $posts = collect();

        if (strlen($query) >= 2) {
            $posts = Post::query()
                ->published()
                ->with(['category', 'categories'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('excerpt', 'like', "%{$query}%")
                        ->orWhere('body', 'like', "%{$query}%");
                })
                ->limit(8)
                ->get();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'query' => $query,
                'count' => $posts->count(),
                'results' => $posts->map(fn ($post) => [
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'url' => route('post.show', $post->slug),
                    'category' => $post->categories->isNotEmpty() ? $post->categories->pluck('name')->join(', ') : $post->category?->name,
                    'date' => $post->formatted_published_at,
                    'excerpt' => $post->meta_description,
                ]),
            ]);
        }

        return view('pages.search', [
            'query' => $query,
            'posts' => $posts,
        ]);
    }
}
