@extends('layouts.app')

@section('title', 'Novo Produto - Ventania')

@section('content')
<header>
    <h1 class="text-xl font-bold">
        Editar produto
    </h1>

    <p class="mt-0.5 text-xs text-neutral-500">
        Altere as informações do produto.
    </p>
</header>


<form
    class="mt-6"
    method="POST"
    action="{{ route('products.update', $product) }}">
    @csrf
    @method('PUT')

    @if ($errors->any())
    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3">
        <ul class="space-y-1 text-xs text-red-700">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="rounded-lg border border-neutral-200 bg-white p-5">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label
                    for="name"
                    class="mb-1.5 block text-xs font-medium text-neutral-700">
                    Nome
                </label>

                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $product->name) }}"
                    class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm outline-none transition focus:border-primary-950">
            </div>

            <div>
                <label
                    for="category_id"
                    class="mb-1.5 block text-xs font-medium text-neutral-700">
                    Categoria
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm outline-none transition focus:border-primary-950">

                    <option value="">Selecione uma categoria</option>

                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label
                    for="price"
                    class="mb-1.5 block text-xs font-medium text-neutral-700">
                    Preço
                </label>

                <input
                    id="price"
                    name="price"
                    type="number"
                    min="0"
                    step="0.01"
                    value="{{ old('price', $product->price) }}"
                    class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm outline-none transition focus:border-primary-950">
            </div>

            <div>
                <label
                    for="minimum_stock"
                    class="mb-1.5 block text-xs font-medium text-neutral-700">
                    Estoque mínimo
                </label>

                <input
                    id="minimum_stock"
                    name="minimum_stock"
                    type="number"
                    min="0"
                    step="1"
                    value="{{ old('minimum_stock', $product->minimum_stock) }}"
                    class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm outline-none transition focus:border-primary-950">
            </div>

            <div class="col-span-2">
                <label
                    for="description"
                    class="mb-1.5 block text-xs font-medium text-neutral-700">
                    Descrição
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm outline-none transition focus:border-primary-950">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <div class="mt-5 flex justify-end gap-2">
            <a
                href="{{ route('products.index') }}"
                class="rounded-md border border-neutral-300 px-3 py-2 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50">
                Cancelar
            </a>

            <button
                type="submit"
                class="cursor-pointer rounded-md bg-primary-950 px-3 py-2 text-xs font-semibold text-white transition hover:bg-primary-900">
                salvar alterações
            </button>
        </div>
    </div>
</form>
@endsection