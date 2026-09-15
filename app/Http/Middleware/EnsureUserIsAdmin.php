<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $usuario = Auth::user();

        if ($usuario === null || ! $usuario->isAdministrador()) {
            abort(403, __('messages.acceso_denegado_admin'));
        }

        return $next($request);
    }
}
