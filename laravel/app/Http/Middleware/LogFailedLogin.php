<?php

namespace App\Http\Middleware;

use App\Models\SecurityEvent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Intercepta respostas de redirecionamento com erro de autenticação
 * e registra o evento em security_events para auditoria.
 */
class LogFailedLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Captura só tentativas de POST no /login que falharam (voltam com erro de sessão)
        if (
            $request->isMethod('POST') &&
            $request->routeIs('login') &&
            $response->isRedirect() &&
            session()->has('errors') &&
            session('errors')?->has('email')
        ) {
            SecurityEvent::create([
                'user_id' => null,
                'event_type' => 'failed_login',
                'ip_address' => $request->ip(),
            ]);
        }

        return $response;
    }
}
