@extends('layouts.app')

@section('title', 'Dashboard - Ventania')

@section('content')
    <header class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold">Dashboard</h1>

            <p class="mt-0.5 text-xs text-slate-500">Visão geral do estoque e das vendas.</p>
        </div>
    </header>
    <div class="mt-6 grid grid-cols-4 gap-4">
        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Produtos cadastrados</p>

                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $productsCount }}</p>
                </div>
                <div class="flex h-8 w-8 items-center justify-center rounded-md bg-blue-50 text-blue-950">
                    {{-- Heroicon: cube --}}
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Unidades em estoque</p>

                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $stockQuantity }}</p>
                </div>
                <div class="flex h-8 w-8 items-center justify-center rounded-md bg-blue-50 text-blue-950">
                    {{-- Heroicon: archive-box --}}
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Vendas pendentes</p>

                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $pendingSalesCount }}</p>
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
                        aria-hidden="true"
                    >
                        <path d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Vendas concluídas</p>

                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $completedSalesCount }}</p>
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
                        aria-hidden="true"
                    >
                        <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
@endsection
