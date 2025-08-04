<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ajustá esta lógica según cómo determines si un usuario es super admin
        if (auth()->check() && auth()->user()->rol === 'super_admin') {
            return $next($request);
        }

        abort(403, 'Acceso no autorizado.');
    }
}

