<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Armazena o hash da senha em memória para não recalcular o Bcrypt a cada usuário gerado.
     */
    protected static ?string $password = null;

    /**
     * Define o estado padrão do Model (usuário comum e ativo).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => UserRole::User,
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Estado customizado para criar um usuário com papel de Administrador.
     * Uso: User::factory()->admin()->create()
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => UserRole::Admin,
        ]);
    }

    /**
     * Estado customizado para criar um usuário inativo (bloqueado).
     * Uso: User::factory()->inactive()->create()
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
