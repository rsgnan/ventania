<?php

namespace App\Models;

use App\Enums\ProductType;
use App\Models\Concerns\Auditable;
use DomainException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use Auditable, HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'type',
        'price',
        'stock_quantity',
        'min_stock',
        'description',
        'image_path',
        'is_active',
    ];

    /**
     * Inclui automaticamente o estoque calculado, o alerta de estoque baixo e a URL da foto no JSON enviado ao Vue.
     *
     * @var list<string>
     */
    protected $appends = [
        'available_stock',
        'is_low_stock',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'type' => ProductType::class,
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'min_stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Gera automaticamente o SKU (PRO-00001 ou KIT-00001) caso não seja informado
        static::creating(function (self $product): void {
            if (empty($product->sku)) {
                $product->sku = self::generateNextSku($product->type ?? ProductType::Simple);
            }
        });

        // Trava de domínio: proibido deletar produtos no ERP
        static::deleting(function (self $product): void {
            throw new DomainException(__('products.cannot_delete', ['name' => $product->name]));
        });
    }

    /**
     * Gera o próximo código SKU sequencial no formato PRO-00001 ou KIT-00001.
     */
    public static function generateNextSku(ProductType $type): string
    {
        $prefix = $type === ProductType::Kit ? 'KIT-' : 'PRO-';

        $lastSku = self::query()
            ->where('sku', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->value('sku');

        $nextNumber = $lastSku ? ((int) substr($lastSku, 4)) + 1 : 1;

        return $prefix . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Categoria à qual este produto pertence.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Produtos Simples que compõem este Kit (com a respectiva quantidade no pivot).
     */
    public function components(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'product_kit_items',
            'kit_product_id',
            'component_product_id',
        )->withPivot('quantity');
    }

    /**
     * Kits dos quais este Produto Simples faz parte.
     */
    public function kits(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'product_kit_items',
            'component_product_id',
            'kit_product_id',
        )->withPivot('quantity');
    }

    /**
     * Calcula dinamicamente o estoque disponível (Fórmula do Gargalo para Kits).
     */
    protected function availableStock(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                if ($this->type !== ProductType::Kit) {
                    return $this->stock_quantity;
                }

                $components = $this->relationLoaded('components')
                    ? $this->components
                    : $this->components()->get();

                if ($components->isEmpty()) {
                    return 0;
                }

                return (int) $components->map(
                    fn(self $component) => intdiv(max(0, $component->stock_quantity), max(1, (int) $component->pivot->quantity))
                )->min();
            }
        );
    }

    /**
     * Indica se o Produto Físico (Simple) está com saldo abaixo ou igual ao estoque mínimo.
     * Kits possuem estoque virtual e não geram alerta de estoque baixo próprio.
     */
    protected function isLowStock(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => $this->type === ProductType::Simple && $this->stock_quantity <= $this->min_stock,
        );
    }

    /**
     * Retorna a URL pública da imagem do produto através do link simbólico public/storage.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn(): ?string => $this->image_path ? asset('storage/' . $this->image_path) : null,
        );
    }
}
