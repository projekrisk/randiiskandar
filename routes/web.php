<?php

use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes (Editorial Minimalist)
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

// Redirect legacy /baca/{slug} ke /{slug} (301 Permanent Redirect untuk SEO & Retrokompatibilitas)
Route::get('/baca/{slug}', function (string $slug) {
    return redirect('/'.$slug, 301);
});
Route::get('/baca/{slug}/apresiasi', [PostController::class, 'getClaps']);
Route::post('/baca/{slug}/apresiasi', [PostController::class, 'clap']);
Route::post('/baca/{slug}/komentar', [CommentController::class, 'store']);

// Endpoint Apresiasi & Komentar Realtime untuk artikel
Route::get('/{slug}/apresiasi', [PostController::class, 'getClaps'])->name('post.claps');
Route::post('/{slug}/apresiasi', [PostController::class, 'clap'])->name('post.clap');
Route::post('/{slug}/komentar', [CommentController::class, 'store'])->name('comment.store');

Route::get('/kategori/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/arsip', [CategoryController::class, 'index'])->name('archive.index');
Route::get('/cari', SearchController::class)->name('search');

// Halaman Statis
Route::get('/tentang', [PageController::class, 'about'])->name('about');
Route::get('/about', [PageController::class, 'about']);
Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
Route::post('/kontak', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/term', [PageController::class, 'terms']);
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('disclaimer');
Route::get('/privasi', [PageController::class, 'privacy'])->name('privacy');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/halaman/{slug}', function (string $slug) {
    return redirect('/'.$slug, 301);
});

/*
|--------------------------------------------------------------------------
| Admin Workspace & Moderation Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    // Autentikasi Admin
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Dasbor yang dilindungi auth
    Route::middleware('auth')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // CRUD Artikel
        Route::post('/posts/upload-image', [AdminPostController::class, 'uploadImage'])->name('posts.upload-image');
        Route::resource('posts', AdminPostController::class)->except(['show']);

        // CRUD Halaman Statis
        Route::resource('pages', AdminPageController::class)->except(['show']);

        // Manajemen Menu Navigasi (Header & Footer)
        Route::resource('menus', AdminMenuController::class)->except(['show']);
        Route::post('menus/reorder', [AdminMenuController::class, 'reorder'])->name('menus.reorder');

        // CRUD Kategori
        Route::resource('categories', AdminCategoryController::class)->only(['index', 'store', 'update', 'destroy']);

        // Moderasi Komentar
        Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
        Route::post('/comments/filter', [AdminCommentController::class, 'updateFilter'])->name('comments.filter.update');
        Route::post('/comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
        Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

        // Laporan & Analitik
        Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/export', [AdminAnalyticsController::class, 'exportCsv'])->name('analytics.export');

        // Pengaturan Sistem (Profil Penulis, Iklan, & Ganti Password)
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/profile', [AdminSettingController::class, 'updateProfile'])->name('settings.profile.update');
        Route::post('/settings/ads', [AdminSettingController::class, 'updateAds'])->name('settings.ads.update');
        Route::post('/settings/password', [AdminSettingController::class, 'updatePassword'])->name('settings.password.update');
    });
});

/*
|--------------------------------------------------------------------------
| Dynamic Content: Articles & Static Pages (Direct /{slug})
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', [PostController::class, 'show'])->name('post.show');
