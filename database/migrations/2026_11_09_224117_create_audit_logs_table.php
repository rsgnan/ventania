<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela central de auditoria (Event Log) no PostgreSQL.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Quem executou a ação (nullable para ações automáticas de sistema/seeders, restrictOnDelete para impedir exclusão)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            // Ação realizada: ex: 'login', 'logout', 'created', 'updated', 'completed', 'cancelled'
            $table->string('action', 50);

            // Cria auditable_type (varchar), auditable_id (bigint) e o índice composto de ambos automaticamente
            $table->nullableMorphs('auditable');

            // Estado anterior e novo estado em formato binário otimizado do PostgreSQL (JSONB)
            $table->jsonb('old_values')->nullable();
            $table->jsonb('new_values')->nullable();

            // Metadados de rastreio de rede (suporta IPv4 e IPv6 até 45 caracteres)
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Apenas created_at, pois logs de auditoria são fatos históricos imutáveis
            $table->timestamp('created_at')->useCurrent();

            // Índice composto estratégico para buscar rapidamente o último login/logout ou ações por usuário
            $table->index(['user_id', 'action', 'created_at']);
        });
    }

    /**
     * Reverte a migration em ambiente de desenvolvimento.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
