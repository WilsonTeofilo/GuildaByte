<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $allowed = match ($role) {
            'admin' => $user->isAdmin(),
            'client' => $user->isClient(),
            default => false,
        };

        if (! $allowed) {
            abort(403, 'Acesso negado.');
        }

        return $next($request);
    }
}
