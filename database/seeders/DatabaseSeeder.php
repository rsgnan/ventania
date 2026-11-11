<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Popula o banco de dados da aplicação com dados iniciais (Seed).
     */
    public function run(): void
    {
        // 1. Conta fixa de Administrador (Idempotente via firstOrCreate pelo e-mail único)
        User::firstOrCreate(
            ['email' => 'admin@ventania.com'],
            [
                'name' => 'Administrador Ventania',
                'password' => 'password', // O cast 'hashed' no Model User fará o hash automaticamente
                'role' => UserRole::Admin,
                'is_active' => true,
            ]
        );

        // 2. Conta fixa de Usuário Comum (Vendedor/Operador ativo)
        User::firstOrCreate(
            ['email' => 'vendedor@ventania.com'],
            [
                'name' => 'Vendedor Padrão',
                'password' => 'password',
                'role' => UserRole::User,
                'is_active' => true,
            ]
        );

        // 3. Conta fixa de Usuário Inativo (Para testar o bloqueio de autenticação)
        User::firstOrCreate(
            ['email' => 'inativo@ventania.com'],
            [
                'name' => 'Funcionário Desligado',
                'password' => 'password',
                'role' => UserRole::User,
                'is_active' => false,
            ]
        );

        // 4. Gera usuários adicionais apenas se o banco tiver poucos registros (mantém idempotência de volume)
        if (User::count() < 15) {
            // Cria 10 operadores ativos aleatórios
            User::factory(10)->create();

            // Cria 2 operadores inativos aleatórios para testar filtros na listagem
            User::factory(2)->inactive()->create();
        }

        // 5. Categorias fixas do catálogo Ventania (Idempotente via slug único)
        foreach (['Vestuários', 'Pedras', 'Guias', 'Ervas', 'Sabonetes', 'Perfumes', 'Incensos', 'Acessórios', 'Amigurumi'] as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
