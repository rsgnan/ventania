@extends('layouts.app')

@section('title', 'Dashboard - Ventania')

@section('content')
<header class="flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold">Dashboard</h1>

        <p class="mt-0.5 text-xs text-neutral-500">Visão geral do estoque e das vendas.</p>
    </div>
</header>
<div class="mt-6 grid grid-cols-4 gap-4">
    <div class="rounded-lg border border-neutral-200 bg-white p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-neutral-500">Produtos cadastrados</p>

                <p class="mt-2 text-2xl font-bold text-neutral-900">{{ $productsCount }}</p>
            </div>
            <div class="flex h-8 w-8 items-center justify-center rounded-md bg-primary-50 text-primary-950">
                {{-- Heroicon: cube --}}
                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-neutral-200 bg-white p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-neutral-500">Unidades em estoque</p>

                <p class="mt-2 text-2xl font-bold text-neutral-900">{{ $stockQuantity }}</p>
            </div>
            <div class="flex h-8 w-8 items-center justify-center rounded-md bg-primary-50 text-primary-950">
                {{-- Heroicon: archive-box --}}
                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-neutral-200 bg-white p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-neutral-500">Vendas pendentes</p>

                <p class="mt-2 text-2xl font-bold text-neutral-900">{{ $pendingSalesCount }}</p>
            </div>
            <div class="flex h-8 w-8 items-center justify-center rounded-md bg-amber-50 text-amber-600">
                {{-- Heroicon: clock --}}
                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-neutral-200 bg-white p-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-neutral-500">Vendas concluídas</p>

                <p class="mt-2 text-2xl font-bold text-neutral-900">{{ $completedSalesCount }}</p>
            </div>
            <div class="flex h-8 w-8 items-center justify-center rounded-md bg-green-50 text-green-600">
                {{-- Heroicon: check-circle --}}
                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <div class="flex items-center justify-between border-b border-neutral-200 px-4 py-3">
        <div>
            <h2 class="text-sm font-semibold text-neutral-900">
                Vendas recentes
            </h2>

            <p class="mt-0.5 text-xs text-neutral-500">
                Últimas vendas registradas no sistema.
            </p>
        </div>
    </div>

    @forelse ($recentSales as $sale)
    <div class="flex items-center justify-between border-b border-neutral-100 px-4 py-3">
        <div>
            <p class="text-xs font-semibold text-neutral-900">
                {{ $sale->customer_name }}
            </p>

            <p class="mt-0.5 text-[11px] text-neutral-500">
                Venda #{{ $sale->id }}
            </p>
        </div>

        <div>
            @if ($sale->status === 'pending')
            <span class="rounded-full bg-amber-50 px-2 py-1 text-[11px] font-medium text-amber-700">
                Pendente
            </span>
            @elseif ($sale->status === 'completed')
            <span class="rounded-full bg-green-50 px-2 py-1 text-[11px] font-medium text-green-700">
                Concluída
            </span>
            @else
            <span class="rounded-full bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700">
                Cancelada
            </span>
            @endif
        </div>

        <div class="text-right">
            <p class="text-xs font-semibold text-neutral-900">
                R$ {{ number_format($sale->total_amount, 2, ',', '.') }}
            </p>

            <p class="mt-0.5 text-[11px] text-neutral-500">
                {{ $sale->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
    @empty
    <div class="px-4 py-8 text-center">
        <p class="text-xs text-neutral-500">
            Nenhuma venda registrada.
        </p>
    </div>
    @endforelse
</div>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <div class="border-b border-neutral-200 px-4 py-3">
        <h2 class="text-sm font-semibold text-neutral-900">
            Estoque baixo
        </h2>

        <p class="mt-0.5 text-xs text-neutral-500">
            Produtos que atingiram ou estão abaixo do estoque mínimo.
        </p>
    </div>
    @forelse ($lowStockProducts as $product)
    <div class="flex items-center justify-between border-b border-neutral-100 px-4 py-3">
        <div>
            <p class="text-xs font-semibold text-neutral-900">
                {{ $product->name }}
            </p>

            <p class="mt-0.5 text-[11px] text-neutral-500">
                Produto #{{ $product->id }}
            </p>
        </div>

        <div class="text-right">
            <p class="text-xs font-semibold text-neutral-900">
                {{ $product->stock }} unidades
            </p>

            <p class="mt-0.5 text-xs text-neutral-500">
                Mínimo: {{ $product->minimum_stock }}
            </p>
        </div>
    </div>
    @empty
    <div class="px-4 py-8 text-center">
        <p class="text-xs text-neutral-500">
            Nenhum produto com estoque baixo.
        </p>
    </div>
    @endforelse
</div>
@endsection