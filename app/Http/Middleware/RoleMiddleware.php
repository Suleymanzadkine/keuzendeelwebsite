<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('dashboard')->with('error', 'Je hebt geen toegang tot deze pagina.');
        }

        $user = auth()->user();
        $roles = explode('|', $role);

        if (method_exists($user, 'hasRole') && $user->hasRole($roles)) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Je hebt geen toegang tot deze pagina.');
    }
}
