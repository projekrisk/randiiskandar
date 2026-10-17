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
        //
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
