<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovementItem extends Model
{
    /**
     * Os itens herdam a data/hora do cabeçalho (stock_movements.created_at), não possuindo timestamps próprios.
     */
    public $timestamps = false;

    protected $fillable = [
        'stock_movement_id',
        'product_id',
        'quantity',
        'previous_stock',
        'new_stock',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'previous_stock' => 'integer',
            'new_stock' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::updating(fn() => throw new DomainException(__('stock.cannot_modify')));
        static::deleting(fn() => throw new DomainException(__('stock.cannot_modify')));
    }

    public function stockMovement(): BelongsTo
    {
        return $this->belongsTo(StockMovement::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
