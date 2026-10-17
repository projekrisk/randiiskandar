<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(Request $request): Response
    {
        $latestPostUpdate = Post::published()->latest('updated_at')->value('updated_at');

        $content = Cache::remember('sitemap_xml_content', 3600, function () use ($latestPostUpdate) {
            $posts = Post::published()->latest('updated_at')->get();
            $categories = Category::latest('updated_at')->get();
            $pages = Page::published()->latest('updated_at')->get();
            $aboutPage = Page::published()->whereIn('slug', ['about', 'tentang'])->latest('updated_at')->first();

            return view('sitemap', [
                'posts' => $posts,
                'categories' => $categories,
                'pages' => $pages,
                'latestPostUpdate' => $latestPostUpdate,
                'aboutPage' => $aboutPage,
            ])->render();
        });

        $response = response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'X-Robots-Tag' => 'noindex, follow',
            'Cache-Control' => 'public, max-age=3600, must-revalidate',
        ]);

        if ($latestPostUpdate) {
            $response->setLastModified($latestPostUpdate);
        }

        return $response;
    }
}
