<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        $this->app->useLangPath(resource_path('lang'));

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
