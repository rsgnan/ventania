<?php

namespace App\Services;

use App\Enums\ProductType;
use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementService
{
    /**
     * Registra uma movimentação de estoque (Entrada ou Saída) protegida por transação e Row-Level Locking.
     *
     * @param  array<int, array{product_id: int|string, quantity: int|string}>  $items
     */
    public function record(
        int $userId,
        StockMovementType $type,
        array $items,
        ?string $notes = null,
    ): StockMovement {
        return DB::transaction(function () use ($userId, $type, $items, $notes): StockMovement {
            $productIds = array_column($items, 'product_id');

            // Tranca as linhas dos produtos no PostgreSQL (SELECT ... FOR UPDATE) ordenadas por ID para evitar deadlocks
            $products = Product::query()
                ->whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $movement = StockMovement::create([
                'user_id' => $userId,
                'type' => $type,
                'notes' => $notes,
            ]);

            foreach ($items as $item) {
                /** @var Product|null $product */
                $product = $products->get((int) $item['product_id']);
                $quantity = (int) $item['quantity'];

                if (! $product || $product->type !== ProductType::Simple) {
                    throw ValidationException::withMessages([
                        'items' => __('stock.only_simple_products'),
                    ]);
                }

                $previousStock = $product->stock_quantity;

                // Se for Saída (out), garante que o estoque físico nunca fique negativo
                if ($type === StockMovementType::Out && $previousStock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => __('stock.insufficient_stock', [
                            'product' => "{$product->sku} - {$product->name}",
                            'available' => $previousStock,
                            'requested' => $quantity,
                        ]),
                    ]);
                }

                $newStock = $type === StockMovementType::In
                    ? $previousStock + $quantity
                    : $previousStock - $quantity;

                $product->update([
                    'stock_quantity' => $newStock,
                ]);

                $movement->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'previous_stock' => $previousStock,
                    'new_stock' => $newStock,
                ]);
            }

            return $movement->load(['user:id,name', 'items.product:id,name,sku']);
        });
    }
}
