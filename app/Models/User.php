<?php

declare(strict_types=1);

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;
use App\Models\Concerns\Auditable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Auditable, HasFactory, Notifiable;

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
            throw new DomainException(__('users.cannot_delete', ['email' => $user->email]));
        });
    }

    /**
     * Verifica de forma semântica se o usuário possui perfil de Administrador.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    /**
     * Retorna apenas o registro de login mais recente do usuário usando o índice composto de audit_logs.
     */
    public function latestLogin(): HasOne
    {
        return $this->hasOne(AuditLog::class)
            ->ofMany(['id' => 'max'], fn($query) => $query->where('action', 'login'));
    }
}
