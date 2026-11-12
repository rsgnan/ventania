<?php

namespace App\Models;

use App\Enums\StockMovementType;
use App\Models\Concerns\Auditable;
use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockMovement extends Model
{
    use Auditable;

    /**
     * Movimentações de estoque possuem apenas created_at (são fatos históricos imutáveis).
     */
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'type',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => StockMovementType::class,
            'created_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::updating(fn() => throw new DomainException(__('stock.cannot_modify')));
        static::deleting(fn() => throw new DomainException(__('stock.cannot_modify')));
    }

    /**
     * Operador responsável pelo registro da movimentação.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lista de produtos e quantidades movimentadas nesta operação.
     */
    public function items(): HasMany
    {
        return $this->hasMany(StockMovementItem::class);
    }
}
