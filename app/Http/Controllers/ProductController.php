<?php

namespace App\Http\Controllers;

use App\Enums\ProductType;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            ->when($request->string('tab')->value(), function (Builder $query, string $tab) {
                match ($tab) {
                    'simple' => $query->where('type', ProductType::Simple),
                    'kit' => $query->where('type', ProductType::Kit),
                    'low_stock' => $query->where('type', ProductType::Simple)->whereColumn('stock_quantity', '<=', 'min_stock'),
                    default => null,
                };
            })
            ->when($request->string('status')->value(), function (Builder $query, string $status) {
                match ($status) {
                    'active' => $query->where('is_active', true),
                    'inactive' => $query->where('is_active', false),
                    default => null,
                };
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('products/Index', [
            'products' => $products,
            'filters' => $request->only(['search', 'category_id', 'tab', 'status']),
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
                'simple' => Product::where('type', ProductType::Simple)->count(),
                'kits' => Product::where('type', ProductType::Kit)->count(),
                'low_stock' => Product::where('type', ProductType::Simple)->whereColumn('stock_quantity', '<=', 'min_stock')->count(),
                'physical_units' => (int) Product::where('type', ProductType::Simple)->sum('stock_quantity'),
                'stock_value' => (float) Product::where('type', ProductType::Simple)
                    ->selectRaw('COALESCE(SUM(price * stock_quantity), 0) as total')
                    ->value('total'),
            ],
        ]);
    }

    /**
     * Cadastra um Produto Simples (com estoque inicial 0) ou um Kit com sua composição e imagem opcional.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated) {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'sku' => $validated['sku'] ?? null,
                'type' => $validated['type'],
                'price' => $validated['price'],
                'min_stock' => $validated['min_stock'],
                'description' => $validated['description'] ?? null,
                'image_path' => $request->file('image')?->store('products', 'public'),
                'is_active' => $validated['is_active'],
            ]);

            if ($product->type === ProductType::Kit) {
                $this->syncKitComponents($product, $validated['components']);
            }
        });

        return back();
    }

    /**
     * Atualiza dados cadastrais, foto, estoque mínimo e composição do Kit.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $product, $validated) {
            $imagePath = $product->image_path;

            if ($request->hasFile('image')) {
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }

                $imagePath = $request->file('image')->store('products', 'public');
            }

            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'sku' => $validated['sku'] ?: $product->sku,
                'price' => $validated['price'],
                'min_stock' => $validated['min_stock'],
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
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
