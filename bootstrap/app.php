<?php

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Adiciona os nossos middlewares ao final da pilha 'web' (após a sessão iniciar)
        $middleware->web(append: [
            EnsureUserIsActive::class, // Nosso segurança que barra usuários com is_active = false
            HandleInertiaRequests::class, // Ponte de dados entre o Laravel e o Vue 3
            AddLinkHeadersForPreloadedAssets::class, // Otimização de carregamento de assets do Vite
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
