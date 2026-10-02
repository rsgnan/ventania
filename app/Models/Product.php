<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'name',
        'description',
        'price',
        'stock',
        'minimum_stock',
        'photo',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function saleItemChanges(): HasMany
    {
        return $this->hasMany(SaleItemChange::class);
    }

    public function stockMovementItems(): HasMany
    {
        return $this->hasMany(StockMovementItem::class);
    }

    public function getStockPercentageAttribute()
    {
        return $this->minimum_stock > 0
            ? min(($this->stock / $this->minimum_stock) *100, 100)
            : 100;
    }

    public function getLowStockAttribute()
    {
        return $this->minimum_stock > 0
            && $this->stock <= $this->minimum_stock;
    }
}
