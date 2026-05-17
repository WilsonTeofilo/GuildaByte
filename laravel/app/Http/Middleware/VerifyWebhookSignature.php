<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifica a assinatura HMAC do webhook do Mercado Pago.
 * Rejeita qualquer request sem assinatura válida antes de chegar no Controller.
 */
class VerifyWebhookSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('services.mercadopago.webhook_secret');
        $signature = $request->header('X-Signature', '');
        $payload = $request->getContent();

        // Se não tem secret configurado em produção, bloqueia tudo
        if (app()->isProduction() && empty($secret)) {
            abort(500, 'Webhook secret não configurado.');
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        if (! hash_equals($expected, $signature)) {
            abort(401, 'Assinatura de webhook inválida.');
        }

        return $next($request);
    }
}
