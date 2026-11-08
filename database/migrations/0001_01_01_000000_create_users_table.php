<?php

declare(strict_types=1);

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrations (cria a estrutura no PostgreSQL).
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Cria chave primária BIGINT auto-incremental (BIGSERIAL/IDENTITY no Postgres)
            $table->string('name');
            $table->string('email')->unique(); // Índice UNIQUE no banco para impedir e-mails duplicados
            $table->string('password');

            // Papel do usuário no ERP ('admin' ou 'user').
            // Limitamos a 20 caracteres (VARCHAR(20)) e usamos o Enum para evitar "magic strings" no default.
            $table->string('role', 20)->default(UserRole::User->value);

            // Flag de ativação: PROIBIDO deletar usuários no ERP.
            // Nasce ativo (true) por padrão. Se false, o acesso ao sistema é bloqueado.
            $table->boolean('is_active')->default(true);

            // Índice composto para acelerar filtros na listagem de usuários (ex: filtrar ativos por role)
            $table->index(['is_active', 'role']);

            $table->rememberToken();
            $table->timestamps(); // Cria created_at e updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverte as migrations caso seja necessário um rollback em ambiente de desenvolvimento.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
