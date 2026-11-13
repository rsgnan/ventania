<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Enums\ProductType;
use App\Enums\SaleStatus;
use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    public function __construct(
        private readonly StockMovementService $stockMovementService,
    ) {}

    /**
     * Cria uma nova venda sempre com status inicial Pendente (sem baixar estoque ainda).
     *
     * @param  array{customer_name: string, payment_method: string, discount?: float|int|string|null, notes?: ?string, items: array<int, array{product_id: int|string, quantity: int|string}>}  $data
     */
    public function create(int $userId, array $data): Sale
    {
        return DB::transaction(function () use ($userId, $data): Sale {
            $discount = round((float) ($data['discount'] ?? 0), 2);

            $sale = Sale::create([
                'user_id' => $userId,
                'customer_name' => $data['customer_name'],
                'status' => SaleStatus::Pending,
                'payment_method' => PaymentMethod::from($data['payment_method']),
                'discount' => $discount,
                'total_amount' => 0,
                'notes' => $data['notes'] ?? null,
            ]);

            $subtotal = $this->syncItems($sale, $data['items']);

            if ($discount > $subtotal) {
                throw ValidationException::withMessages([
                    'discount' => __('sales.discount_exceeds_subtotal'),
                ]);
            }

            $sale->update(['total_amount' => round($subtotal - $discount, 2)]);

            return $sale->load(['user:id,name', 'items.product:id,name,sku,type']);
        });
    }

    /**
     * Atualiza uma venda Pendente e processa a transição para Concluída (baixando estoque) ou Cancelada.
     *
     * @param  array{customer_name: string, status: string, payment_method: string, discount?: float|int|string|null, notes?: ?string, items: array<int, array{product_id: int|string, quantity: int|string}>}  $data
     */
    public function update(Sale $sale, array $data, int $userId): Sale
    {
        if (! $sale->status->isMutable()) {
            throw ValidationException::withMessages([
                'status' => __('sales.immutable_sale'),
            ]);
        }

        return DB::transaction(function () use ($sale, $data, $userId): Sale {
            $newStatus = SaleStatus::from($data['status']);
            $discount = round((float) ($data['discount'] ?? 0), 2);

            $subtotal = $this->syncItems($sale, $data['items']);

            if ($discount > $subtotal) {
                throw ValidationException::withMessages([
                    'discount' => __('sales.discount_exceeds_subtotal'),
                ]);
            }

            if ($newStatus === SaleStatus::Completed) {
                $this->deductStockForSale($sale, $userId);
            }

            $sale->update([
                'customer_name' => $data['customer_name'],
                'status' => $newStatus,
                'payment_method' => PaymentMethod::from($data['payment_method']),
                'discount' => $discount,
                'total_amount' => round($subtotal - $discount, 2),
                'notes' => $data['notes'] ?? null,
                'completed_at' => $newStatus === SaleStatus::Completed ? now() : null,
                'cancelled_at' => $newStatus === SaleStatus::Cancelled ? now() : null,
            ]);

            return $sale->load(['user:id,name', 'items.product:id,name,sku,type']);
        });
    }

    /**
     * Substitui os itens da venda Pendente congelando o preço atual do catálogo e retorna o subtotal bruto.
     *
     * @param  array<int, array{product_id: int|string, quantity: int|string}>  $items
     */
    private function syncItems(Sale $sale, array $items): float
    {
        $products = Product::query()
            ->whereIn('id', array_column($items, 'product_id'))
            ->get()
            ->keyBy('id');

        $sale->items()->delete();

        $subtotalSum = 0.0;

        foreach ($items as $item) {
            /** @var Product|null $product */
            $product = $products->get((int) $item['product_id']);

            if (! $product || ! $product->is_active) {
                throw ValidationException::withMessages([
                    'items' => __('sales.inactive_product', ['product' => $product?->name ?? '#']),
                ]);
            }

            $quantity = (int) $item['quantity'];
            $unitPrice = round((float) $product->price, 2);
            $subtotal = round($quantity * $unitPrice, 2);
            $subtotalSum += $subtotal;

            $sale->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);
        }

        return round($subtotalSum, 2);
    }

    /**
     * Desmembra Kits em seus componentes físicos, consolida as quantidades por produto e executa a baixa Kardex.
     */
    private function deductStockForSale(Sale $sale, int $userId): void
    {
        $sale->load('items.product.components');

        $physicalDemand = [];
        $kitReferences = [];

        foreach ($sale->items as $saleItem) {
            $product = $saleItem->product;

            if ($product->type === ProductType::Simple) {
                $physicalDemand[$product->id] = ($physicalDemand[$product->id] ?? 0) + $saleItem->quantity;
                continue;
            }

            $kitReferences[] = "{$saleItem->quantity}x {$product->sku}";

            foreach ($product->components as $component) {
                $requiredQty = $saleItem->quantity * (int) $component->pivot->quantity;
                $physicalDemand[$component->id] = ($physicalDemand[$component->id] ?? 0) + $requiredQty;
            }
        }

        $movementItems = collect($physicalDemand)
            ->map(fn(int $qty, int $productId) => [
                'product_id' => $productId,
                'quantity' => $qty,
            ])
            ->values()
            ->all();

        $kitNoteSuffix = $kitReferences !== []
            ? ' (inclui ' . implode(', ', $kitReferences) . ')'
            : '';

        $this->stockMovementService->record(
            userId: $userId,
            type: StockMovementType::Out,
            items: $movementItems,
            notes: "Baixa automática — Venda #{$sale->id}{$kitNoteSuffix}",
        );
    }
}
