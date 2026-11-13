<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use App\Models\Concerns\Auditable;
use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use Auditable;

    protected $fillable = [
        'user_id',
        'customer_name',
        'status',
        'payment_method',
        'discount',
        'total_amount',
        'notes',
        'completed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => SaleStatus::class,
            'payment_method' => PaymentMethod::class,
            'discount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // Trava de imutabilidade: verifica o status que estava salvo no banco (getRawOriginal) antes de permitir o update
        static::updating(function (self $sale): void {
            $originalStatus = SaleStatus::tryFrom((string) $sale->getRawOriginal('status'));

            if ($originalStatus && ! $originalStatus->isMutable()) {
                throw new DomainException(__('sales.immutable_sale'));
            }
        });

        // Trava de domínio: proibido excluir vendas (devem ser canceladas para manter rastro)
        static::deleting(function (): void {
            throw new DomainException(__('sales.cannot_delete'));
        });
    }

    /**
     * Operador/Vendedor que registrou a venda.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Itens (Produtos Simples ou Kits) que compõem esta venda.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
