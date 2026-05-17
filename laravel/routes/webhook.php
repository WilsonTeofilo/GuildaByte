<?php

use Illuminate\Support\Facades\Route;

/**
 * Rotas de Webhook — sem CSRF, mas com verificação de assinatura HMAC obrigatória.
 * O middleware 'webhook' rejeita qualquer request sem X-Signature válida.
 */
Route::middleware('webhook')->group(function () {
    // Mercado Pago (futura implementação na Missão 15)
    // Route::post('/mercadopago', [WebhookController::class, 'mercadoPago'])->name('webhook.mercadopago');
});
