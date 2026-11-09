<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    /**
     * Desativa a coluna updated_at, pois logs de auditoria nunca sofrem update.
     */
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array', // Converte automaticamente entre array PHP e JSONB do Postgres
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Garante que a tabela funcione em modo Append-Only (proibido editar ou excluir logs).
     */
    protected static function booted(): void
    {
        static::updating(fn() => throw new DomainException('Audit logs are immutable and cannot be updated.'));
        static::deleting(fn() => throw new DomainException('Audit logs are immutable and cannot be deleted.'));
    }

    /**
     * Usuário que executou a ação.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Entidade alvo da ação (User, Product, StockMovement ou Sale).
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Atalho limpo e centralizado para registrar qualquer evento de auditoria no sistema.
     */
    public static function record(
        string $action,
        ?Model $auditable = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null,
    ): self {
        return self::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'auditable_type' => $auditable?->getMorphClass(),
            'auditable_id' => $auditable?->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
