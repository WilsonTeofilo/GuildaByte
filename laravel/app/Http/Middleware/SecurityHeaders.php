<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $nonce = base64_encode(random_bytes(16));
        // Guardamos o nonce para uso no blade caso necessário
        app()->instance('csp-nonce', $nonce);

        if (method_exists($response, 'header')) {
            $response->header('X-Frame-Options', 'DENY');
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->header('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

            // CSP: Allow inline for now since Vite and Tailwind might use some inline styles,
            // but restrict as much as possible according to the user's instructions.
            $response->header('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' fonts.googleapis.com; font-src fonts.gstatic.com 'self' data:; img-src 'self' data: https:; connect-src 'self' ws: wss:; frame-ancestors 'none';");
        }

        return $response;
    }
}
