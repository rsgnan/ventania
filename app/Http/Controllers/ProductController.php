<?php

namespace App\Http\Controllers;

use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Exibe o catálogo paginado de Produtos Simples e Kits com KPIs e filtros.
     */
    public function index(Request $request): Response
    {
        $products = Product::query()
            ->with(['category:id,name', 'components:id,name,sku,price,stock_quantity'])
            ->when($request->string('search')->trim()->value(), function (Builder $query, string $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                        ->orWhere('sku', 'ilike', "%{$search}%");
                });
            })
            ->when($request->integer('category_id'), fn(Builder $query, int $categoryId) => $query->where('category_id', $categoryId))
            ->when($request->string('type')->value(), fn(Builder $query, string $type) => $query->where('type', $type))
            ->when($request->string('status')->value(), function (Builder $query, string $status) {
                match ($status) {
                    'active' => $query->where('is_active', true),
                    'inactive' => $query->where('is_active', false),
                    'in_stock' => $query->where('type', ProductType::Simple)->where('stock_quantity', '>', 0),
                    'out_of_stock' => $query->where('type', ProductType::Simple)->where('stock_quantity', '<=', 0),
                    default => null,
                };
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('products/Index', [
            'products' => $products,
            'filters' => $request->only(['search', 'category_id', 'type', 'status']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'simpleProducts' => Product::where('type', ProductType::Simple)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'price', 'stock_quantity']),
            'nextSkus' => [
                'simple' => Product::generateNextSku(ProductType::Simple),
                'kit' => Product::generateNextSku(ProductType::Kit),
            ],
            'stats' => [
                'total' => Product::count(),
                'kits' => Product::where('type', ProductType::Kit)->count(),
                'active' => Product::where('is_active', true)->count(),
                'physical_units' => (int) Product::where('type', ProductType::Simple)->sum('stock_quantity'),
                'stock_value' => (float) Product::where('type', ProductType::Simple)
                    ->selectRaw('COALESCE(SUM(price * stock_quantity), 0) as total')
                    ->value('total'),
            ],
        ]);
    }

    /**
     * Cadastra um Produto Simples (com estoque inicial 0) ou um Kit com sua composição.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        DB::transaction(function () use ($validated) {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'sku' => $validated['sku'] ?? null,
                'type' => $validated['type'],
                'price' => $validated['price'],
                'is_active' => $validated['is_active'],
            ]);

            if ($product->type === ProductType::Kit) {
                $this->syncKitComponents($product, $validated['components']);
            }
        });

        return back();
    }

    /**
     * Atualiza dados cadastrais, status e composição (sem permitir edição direta de estoque).
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request, $product);

        DB::transaction(function () use ($product, $validated) {
            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'sku' => $validated['sku'] ?: $product->sku,
                'type' => $validated['type'],
                'price' => $validated['price'],
                'is_active' => $validated['is_active'],
            ]);

            if ($product->type === ProductType::Kit) {
                $this->syncKitComponents($product, $validated['components']);
            } else {
                $product->components()->detach();
            }
        });

        return back();
    }

    /**
     * Centraliza as regras de validação de criação e edição de produtos e kits.
     */
    private function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product?->id)],
            'type' => ['required', Rule::enum(ProductType::class)],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'components' => ['required_if:type,kit', 'nullable', 'array', 'min:1'],
            'components.*.product_id' => [
                'required_if:type,kit',
                'integer',
                'distinct',
                Rule::exists('products', 'id')->where('type', ProductType::Simple->value),
            ],
            'components.*.quantity' => ['required_if:type,kit', 'integer', 'min:1'],
        ]);
    }

    /**
     * Formata e sincroniza os itens componentes na tabela pivô product_kit_items.
     */
    private function syncKitComponents(Product $kit, array $components): void
    {
        $syncData = collect($components)
            ->mapWithKeys(fn(array $item) => [(int) $item['product_id'] => ['quantity' => (int) $item['quantity']]])
            ->all();

        $kit->components()->sync($syncData);
    }
}
