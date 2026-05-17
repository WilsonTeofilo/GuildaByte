<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'    => \App\Http\Middleware\RoleMiddleware::class,
            'webhook' => \App\Http\Middleware\VerifyWebhookSignature::class,
        ]);

        // SecurityHeaders em todas as respostas HTTP
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        // Log de falha de login em todas as respostas HTTP
        $middleware->append(\App\Http\Middleware\LogFailedLogin::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
