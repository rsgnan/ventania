<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SaleController extends Controller
{
    /**
     * Exibe a listagem paginada de vendas com KPIs financeiros e filtros por status.
     */
    public function index(Request $request): Response
    {
        $sales = Sale::query()
            ->with(['user:id,name', 'items.product.components:id,name,sku'])
            ->when($request->string('search')->trim()->value(), function (Builder $query, string $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('customer_name', 'ilike', "%{$search}%")
                        ->orWhere('notes', 'ilike', "%{$search}%")
                        ->orWhereHas('items.product', function (Builder $pq) use ($search) {
                            $pq->where('name', 'ilike', "%{$search}%")
                                ->orWhere('sku', 'ilike', "%{$search}%");
                        });
                });
            })
            ->when($request->string('tab')->value(), function (Builder $query, string $tab) {
                match ($tab) {
                    'pending' => $query->where('status', SaleStatus::Pending),
                    'completed' => $query->where('status', SaleStatus::Completed),
                    'cancelled' => $query->where('status', SaleStatus::Cancelled),
                    default => null,
                };
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('sales/Index', [
            'sales' => $sales,
            'filters' => $request->only(['search', 'tab']),
            'paymentMethods' => PaymentMethod::options(),
            'catalogProducts' => Product::with('components:id,name,sku,stock_quantity')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'type', 'price', 'stock_quantity', 'min_stock']),
            'stats' => [
                'total' => Sale::count(),
                'pending' => Sale::where('status', SaleStatus::Pending)->count(),
                'completed' => Sale::where('status', SaleStatus::Completed)->count(),
                'cancelled' => Sale::where('status', SaleStatus::Cancelled)->count(),
                'revenue' => (float) Sale::where('status', SaleStatus::Completed)->sum('total_amount'),
                'pending_amount' => (float) Sale::where('status', SaleStatus::Pending)->sum('total_amount'),
            ],
        ]);
    }

    /**
     * Registra uma nova venda com status inicial Pendente.
     */
    public function store(StoreSaleRequest $request, SaleService $service): RedirectResponse
    {
        $service->create($request->user()->id, $request->validated());

        return back();
    }

    /**
     * Atualiza uma venda Pendente ou executa sua transição para Concluída/Cancelada.
     */
    public function update(UpdateSaleRequest $request, Sale $sale, SaleService $service): RedirectResponse
    {
        $service->update($sale, $request->validated(), $request->user()->id);

        return back();
    }
}
