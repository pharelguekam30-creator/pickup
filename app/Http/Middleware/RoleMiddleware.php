<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Normalise le rôle utilisateur et les rôles autorisés pour éviter les problèmes de casse
        $userRole = Str::lower(Auth::user()->role);
        $allowedRoles = array_map(fn($role) => Str::lower($role), $roles);

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Accès refusé. Rôle autorisé(s) : ' . implode(', ', $roles));
        }

        return $next($request);
    }
}
