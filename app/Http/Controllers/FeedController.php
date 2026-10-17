<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class FeedController extends Controller
{
    public function index(): Response
    {
        $content = Cache::remember('feed_rss_content', 1800, function () {
            $posts = Post::published()
                ->with(['author', 'category'])
                ->latest('published_at')
                ->limit(30)
                ->get();

            $lastBuildDate = $posts->first()?->updated_at ?? now();

            return view('feed', [
                'posts' => $posts,
                'lastBuildDate' => $lastBuildDate,
            ])->render();
        });

        return response($content, 200, [
            'Content-Type' => 'application/rss+xml; charset=utf-8',
            'X-Robots-Tag' => 'noindex, follow',
            'Cache-Control' => 'public, max-age=1800, must-revalidate',
        ]);
    }
}
