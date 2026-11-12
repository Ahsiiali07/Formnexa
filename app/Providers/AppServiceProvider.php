<?php

namespace App\Providers;

use App\View\Composers\SeoComposer;
use Illuminate\Support\Facades\URL;
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
        View::composer(['layouts.app', 'errors.*'], SeoComposer::class);

        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
