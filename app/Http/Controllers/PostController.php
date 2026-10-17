<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\PostView;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $query = Post::query()
            ->with(['author', 'category'])
            ->withCount(['comments' => fn ($q) => $q->where('is_approved', true)])
            ->where('slug', $slug);

        if (! Auth::check()) {
            $query->published();
        }

        $post = $query->first();

        if (! $post) {
            $pageQuery = Page::query()->where('slug', $slug);
            if (! Auth::check()) {
                $pageQuery->published();
            }
            $page = $pageQuery->first();

            if ($page) {
                return view('pages.show', [
                    'page' => $page,
                ]);
            }

            abort(404);
        }

        $sessionKey = 'viewed_post_'.$post->id;
        if (! $request->session()->has($sessionKey)) {
            $post->incrementQuietly('views_count');
            PostView::recordView($post->id);
            $request->session()->put($sessionKey, true);
        }

        $comments = $post->comments()
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with([
                'author',
                'replies' => fn ($q) => $q->where('is_approved', true)->with('author')->orderBy('created_at', 'asc'),
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $post->load(['categories', 'category']);

        $categoryIds = $post->categories->pluck('id')->all();
        if ($post->category_id && ! in_array($post->category_id, $categoryIds)) {
            $categoryIds[] = $post->category_id;
        }

        $relatedQuery = Post::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->with(['author', 'category', 'categories'])
            ->reorder()
            ->inRandomOrder();

        if (! empty($categoryIds)) {
            $hasRelated = Post::query()->published()
                ->where('id', '!=', $post->id)
                ->where(function ($q) use ($categoryIds) {
                    $q->whereIn('category_id', $categoryIds)
                        ->orWhereHas('categories', fn ($sub) => $sub->whereIn('categories.id', $categoryIds));
                })->exists();

            if ($hasRelated) {
                $relatedQuery->where(function ($q) use ($categoryIds) {
                    $q->whereIn('category_id', $categoryIds)
                        ->orWhereHas('categories', fn ($sub) => $sub->whereIn('categories.id', $categoryIds));
                });
            }
        }

        $relatedPosts = $relatedQuery->limit(2)->get();

        if ($relatedPosts->count() < 2) {
            $excludeIds = $relatedPosts->pluck('id')->push($post->id)->all();
            $additionalPosts = Post::query()
                ->published()
                ->whereNotIn('id', $excludeIds)
                ->with(['author', 'category', 'categories'])
                ->reorder()
                ->inRandomOrder()
                ->limit(2 - $relatedPosts->count())
                ->get();

            $relatedPosts = $relatedPosts->merge($additionalPosts);
        }

        if ($relatedPosts->isNotEmpty()) {
            $post->setRelation('relatedCategoryPost', $relatedPosts->first());
        }

        return view('pages.detail', [
            'post' => $post,
            'comments' => $comments,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    public function getClaps(string $slug): JsonResponse
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        return response()->json([
            'success' => true,
            'claps_count' => (int) $post->claps_count,
        ]);
    }

    public function clap(Request $request, string $slug): JsonResponse
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();
        $post->increment('claps_count');
        $post->refresh();

        return response()->json([
            'success' => true,
            'claps_count' => (int) $post->claps_count,
        ]);
    }
}
