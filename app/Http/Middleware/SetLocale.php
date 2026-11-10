<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Aplica el idioma guardado en la sesión del usuario.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get(
            'locale',
            'es'
        );

        App::setLocale(in_array($locale, ['es', 'en'], true) ? $locale : 'es');

        return $next($request);
    }
}
