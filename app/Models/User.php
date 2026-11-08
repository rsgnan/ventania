<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atributos que podem ser preenchidos em massa ( User::create() / $user->update() ).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * Atributos que NUNCA devem ser expostos quando o Model for serializado para JSON (Inertia/Vue).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Define a conversão automática de tipos entre o PostgreSQL e o PHP.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Garante hash Bcrypt/Argon2 automático ao atribuir uma senha em texto puro
            'role' => UserRole::class, // Converte 'admin'/'user' do banco diretamente para o Enum UserRole
            'is_active' => 'boolean', // Garante tipo booleano estrito (true/false) no PHP e no Vue
        ];
    }

    /**
     * Registra gatilhos no ciclo de vida do Model (Model Events).
     * Blindagem arquitetural: impede que qualquer parte do sistema execute $user->delete().
     */
    protected static function booted(): void
    {
        static::deleting(function (self $user): void {
            throw new DomainException(
                "Operação proibida: o usuário [{$user->email}] não pode ser excluído. Utilize a inativação (is_active = false)."
            );
        });
    }

    /**
     * Verifica de forma semântica se o usuário possui perfil de Administrador.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }
}
