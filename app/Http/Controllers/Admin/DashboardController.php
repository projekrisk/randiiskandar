<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalPosts = Post::count();
        $totalViews = Post::sum('views_count');
        $pendingCommentsCount = Comment::pending()->count();
        $totalComments = Comment::count();

        $recentPosts = Post::query()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalPosts' => $totalPosts,
            'totalViews' => $totalViews,
            'pendingCommentsCount' => $pendingCommentsCount,
            'totalComments' => $totalComments,
            'recentPosts' => $recentPosts,
        ]);
    }
}
