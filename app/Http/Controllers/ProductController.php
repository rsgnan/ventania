<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $categoryId = $request->integer('category');

        $products = Product::with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->when($categoryId > 0, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
        ]);
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],
        ]);

        $photo = null;

        if ($request->hasFile('image')) {
            $photo = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'category_id' => $data['category_id'],
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'minimum_stock' => $data['minimum_stock'],
            'stock' => 0,
            'photo' => $photo,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produto cadastrado com sucesso.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        /*
         * Remove a imagem atual
         */

        if ($request->boolean('remove_image') && $product->photo) {
            Storage::disk('public')->delete($product->photo);

            $data['photo'] = null;
        }

        /*
         * Se uma nova imagem foi enviada, ela substitui a imagem atual
         */

        if ($request->hasFile('image')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }

            $data['photo'] = $request->file('image')->store('products', 'public');
        }

        unset($data['remove_image']);

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produto atualizado com sucesso.');
    }
}
