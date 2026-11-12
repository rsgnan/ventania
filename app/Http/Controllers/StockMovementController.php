<?php

namespace App\Http\Controllers;

use App\Enums\ProductType;
use App\Enums\StockMovementType;
use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockMovementItem;
use App\Services\StockMovementService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StockMovementController extends Controller
{
    /**
     * Lista o histórico Kardex de movimentações com KPIs e filtros.
     */
    public function index(Request $request): Response
    {
        $movements = StockMovement::query()
            ->with(['user:id,name', 'items.product:id,name,sku'])
            ->when($request->string('search')->trim()->value(), function (Builder $query, string $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('notes', 'ilike', "%{$search}%")
                        ->orWhereHas('items.product', function (Builder $pq) use ($search) {
                            $pq->where('name', 'ilike', "%{$search}%")
                                ->orWhere('sku', 'ilike', "%{$search}%");
                        });
                });
            })
            ->when($request->string('tab')->value(), function (Builder $query, string $tab) {
                match ($tab) {
                    'in' => $query->where('type', StockMovementType::In),
                    'out' => $query->where('type', StockMovementType::Out),
                    default => null,
                };
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('stock/Index', [
            'movements' => $movements,
            'filters' => $request->only(['search', 'tab']),
            'simpleProducts' => Product::where('type', ProductType::Simple)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'stock_quantity']),
            'stats' => [
                'total' => StockMovement::count(),
                'entries' => StockMovement::where('type', StockMovementType::In)->count(),
                'exits' => StockMovement::where('type', StockMovementType::Out)->count(),
                'units_in' => (int) StockMovementItem::whereHas(
                    'stockMovement',
                    fn(Builder $q) => $q->where('type', StockMovementType::In),
                )->sum('quantity'),
                'units_out' => (int) StockMovementItem::whereHas(
                    'stockMovement',
                    fn(Builder $q) => $q->where('type', StockMovementType::Out),
                )->sum('quantity'),
            ],
        ]);
    }

    /**
     * Registra uma nova operação de Entrada ou Saída com múltiplos itens via Service transacional.
     */
    public function store(StoreStockMovementRequest $request, StockMovementService $service): RedirectResponse
    {
        $validated = $request->validated();

        $service->record(
            userId: $request->user()->id,
            type: StockMovementType::from($validated['type']),
            items: $validated['items'],
            notes: $validated['notes'] ?? null,
        );

        return back();
    }
}
