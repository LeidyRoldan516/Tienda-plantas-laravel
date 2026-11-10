<?php

namespace App\Providers;

use App\Interfaces\CatalogoNftInterface;
use App\Interfaces\RecomendacionServiceInterface;
use App\Services\GeminiRecomendacionService;
use App\Services\LocalRecomendacionService;
use App\Services\MonoverseCatalogoNftService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CatalogoNftInterface::class, MonoverseCatalogoNftService::class);

        // Inversión de dependencias: el controlador depende de la interfaz y aquí
        // se decide qué implementación concreta usar. Gemini (servicio externo) es
        // la principal; si se elige el driver "local" o no hay API key configurada,
        // se usa el recomendador local como respaldo.
        $this->app->bind(RecomendacionServiceInterface::class, function ($app) {
            $driver = config('services.recomendaciones.driver', 'gemini');
            $tieneCredenciales = filled(config('gemini.api_key') ?: config('services.gemini.key'));

            if ($driver === 'gemini' && $tieneCredenciales) {
                return $app->make(GeminiRecomendacionService::class);
            }

            return $app->make(LocalRecomendacionService::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->useLangPath(resource_path('lang'));

        // Solo forzar https si la app realmente se sirve por https (en la VM se usa http en el puerto 80)
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
