<?php

namespace App\Providers;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra serviços na inicialização do container.
     */
    public function register(): void
    {
        //
    }

    /**
     * Inicializa serviços e ouvintes de eventos globais da aplicação.
     */
    public function boot(): void
    {
        // Registra automaticamente na auditoria cada login de usuário ativo
        Event::listen(Login::class, function (Login $event): void {
            if ($event->user->is_active) {
                AuditLog::record('login', userId: $event->user->getAuthIdentifier());
            }
        });

        // Registra automaticamente na auditoria cada logout de usuário ativo
        Event::listen(Logout::class, function (Logout $event): void {
            if ($event->user?->is_active) {
                AuditLog::record('logout', userId: $event->user->getAuthIdentifier());
            }
        });
    }
}
