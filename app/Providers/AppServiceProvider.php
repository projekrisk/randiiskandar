<?php

namespace App\Providers;

use App\Models\NavigationMenu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Sesuaikan public_path ke public_html jika berada di lingkungan shared hosting
        $customPublic = config('app.public_path');

        if ($customPublic && is_dir($customPublic)) {
            $this->app->usePublicPath($customPublic);
        } elseif (is_dir(base_path('../public_html'))) {
            $this->app->usePublicPath(base_path('../public_html'));
        } elseif (is_dir(base_path('public_html'))) {
            $this->app->usePublicPath(base_path('public_html'));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer untuk menyediakan menu navigasi header dan footer ke layout
        View::composer('components.layout', function ($view): void {
            try {
                $headerMenus = NavigationMenu::header()->get();
                $footerMenus = NavigationMenu::footer()->get();
            } catch (\Throwable) {
                $headerMenus = collect();
                $footerMenus = collect();
            }

            $view->with([
                'headerMenus' => $headerMenus,
                'footerMenus' => $footerMenus,
            ]);
        });
    }
}
