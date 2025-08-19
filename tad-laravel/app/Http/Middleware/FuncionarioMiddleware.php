<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FuncionarioMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        // Ajustá esta condición a tu esquema de roles/permisos real
        if (!$user || !in_array($user->role ?? '', ['funcionario','superadmin'])) {
            abort(403, 'No autorizado');
        }
        return $next($request);
    }
}
