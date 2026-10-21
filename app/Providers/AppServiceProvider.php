<?php

namespace App\Providers;

use App\Models\NavigationMenu;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $customPublic = config('app.public_path');

        if ($customPublic && is_dir($customPublic)) {
            $this->app->usePublicPath($customPublic);
        } elseif (is_dir(base_path('../public_html'))) {
            $this->app->usePublicPath(base_path('../public_html'));
        } elseif (is_dir(base_path('public_html'))) {
            $this->app->usePublicPath(base_path('public_html'));
        }
    }

    public function boot(): void
    {
        if ($this->app->environment('production') || str_starts_with((string) config('app.url'), 'https://') || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

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
