@extends('layouts.app')

@section('title', 'Produtos - Ventania')

@section('content')
<header class="flex items-center justify-between">
    <div>
        <h1 class="text-lg font-bold">
            Produtos
        </h1>

        <p class="mt-0.5 text-xs text-neutral-500">
            Gerencie os produtos cadastrados no sistema.
        </p>
    </div>

    <a
        href="{{ route('products.create') }}"
        class="rounded-md bg-primary-950 px-4 py-2 text-xs font-semibold text-white transition hover:bg-primary-900">
        Novo produto
    </a>
</header>

@if (session('success'))
<div class="mt-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-xs text-green-700">
    {{ session('success') }}
</div>
@endif

<div class="mt-6 rounded-lg border border-neutral-200 bg-white p-5">

    <form
        method="GET"
        action="{{ route('products.index') }}"
        class="mb-4 flex items-center gap-3">

        <input
            type="search"
            name="search"
            value="{{ $search }}"
            placeholder="Buscar produto..."
            class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm outline-none transition focus:border-primary-950">

        <select
            name="category"
            class="w-52 rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-700 outline-none transition focus:border-primary-950">

            <option value="">Todas as categorias</option>
            @foreach ($categories as $category)
            <option
                value="{{ $category->id }}"
                @selected($categoryId===$category->id)>
                {{ $category->name }}
            </option>
            @endforeach
        </select>

        <button
            type="submit"
            class="cursor-pointer rounded-md bg-primary-950 px-4 py-2 text-xs font-semibold text-white transition hover:bg-primary-900">
            Buscar
        </button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b border-neutral-200">
                    <th class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        Produto
                    </th>

                    <th class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        Categoria
                    </th>

                    <th class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        Preço
                    </th>

                    <th class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        Estoque
                    </th>

                    <th class="px-2 py-2 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        Ações
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)

                @php
                $percentage = $product->minimum_stock > 0
                ? min (($product->stock / $product->minimum_stock) * 100, 100)
                : 100;

                $lowStock = $product->minimum_stock > 0
                && $product->stock <= $product->minimum_stock;
                    @endphp

                    <tr class="border-b border-neutral-200 last:border-b-0 hover:bg-neutral-50">
                        <td class="px-2 py-2">
                            <p class="text-sm font-semibold text-neutral-900">
                                {{ $product->name }}
                            </p>

                            <p class="mt-1 text-xs text-neutral-500">
                                Produto #{{ $product->id }}
                            </p>
                        </td>

                        <td class="px-2 py-2 text-sm text-neutral-700">
                            {{ $product->category->name }}
                        </td>

                        <td class="px-2 py-2 text-sm text-neutral-700">
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </td>

                        <td class="px-2 py-2">
                            <div class="flex items-center gap-3">
                                <span class="min-w-6 text-sm font-semibold text-neutral-700">
                                    {{ $product->stock }}
                                </span>

                                <div>
                                    <div class="h-2 w-24 overflow-hidden rounded-full bg-neutral-200">
                                        <div
                                            class="h-full rounded-full {{ $lowStock ? 'bg-red-500' : 'bg-primary-950' }}"
                                            style="width: {{ $percentage }}%">
                                        </div>
                                    </div>

                                    <p class="mt-1 text-xs text-neutral-500">
                                        Mínimo: {{ $product->minimum_stock }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-2 py-2">
                            <div class="flex justify-end">
                                <a
                                    href="{{ route('products.edit', $product) }}"
                                    class="flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 text-neutral-500 transition hover:bg-primary-50 hover:text-primary-950"
                                    title="Editar produto"
                                    aria-label="Editar produto">
                                    <svg
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true">
                                        <path d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td
                            colspan="5"
                            class="px-2 py-8 text-center text-sm text-neutral-500">
                            Nenhum produto encontrado.
                        </td>
                    </tr>
                    @endforelse
            </tbody>
        </table>
    </div>

    @if ($products->total() > 0)
    <div class="mt-4 flex items-center justify-between">
        <p class="text-xs text-neutral-500">
            Mostrando {{ $products->firstItem() }}-{{ $products->lastItem() }}
            de {{ $products->total() }}
        </p>

        @if ($products->hasPages())
        <div class="flex items-center gap-1">
            @if ($products->onFirstPage())
            <span class="flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 text-xs text-neutral-300">
                <
                    </span>
                    @else
                    <a
                        href="{{ $products->previousPageUrl() }}"
                        class="flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 text-xs text-neutral-700 transition hover:bg-neutral-50">
                        <
                            </a>
                            @endif

                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if ($page === $products->currentPage())
                            <span class="flex h-8 w-8 items-center justify-center rounded-md border border-primary-950 bg-primary-950 text-xs font-semibold text-white">
                                {{ $page }}
                            </span>
                            @else
                            <a
                                href="{{ $url }}"
                                class="flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 text-xs text-neutral-700 transition hover:bg-neutral-50">
                                {{ $page }}
                            </a>
                            @endif
                            @endforeach

                            @if ($products->hasMorePages())
                            <a
                                href="{{ $products->nextPageUrl() }}"
                                class="flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 text-xs text-neutral-700 transition hover:bg-neutral-50">
                                >
                            </a>
                            @else
                            <span class="flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 text-xs text-neutral-300">
                                >
                            </span>
                            @endif
        </div>
        @endif
    </div>
    @endif
</div>
@endsection