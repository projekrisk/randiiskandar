<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Hasilkan XML Sitemap dinamis untuk mesin pencari (SEO).
     */
    public function index(): Response
    {
        $posts = Post::published()->latest('updated_at')->get();
        $categories = Category::latest('updated_at')->get();
        $pages = Page::published()->latest('updated_at')->get();

        $content = view('sitemap', [
            'posts' => $posts,
            'categories' => $categories,
            'pages' => $pages,
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
