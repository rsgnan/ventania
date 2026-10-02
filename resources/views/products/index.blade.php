@extends ('layouts.app')

@section ('title', 'Produtos - Ventania')

@section ('content')
    <header class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold">Produtos</h1>

            <p class="mt-0.5 text-xs text-neutral-500">Gerencie os produtos cadastrados no sistema.</p>
        </div>

        <a
            href="{{ route('products.create') }}"
            class="bg-primary-950 hover:bg-primary-900 rounded-md px-4 py-2 text-xs font-semibold text-white transition"
        >
            Novo produto
        </a>
    </header>

    @if (session('success'))
        <div
            class="mt-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-xs text-green-700"
        >
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-6 rounded-lg border border-neutral-200 bg-white p-5">
        <form
            method="GET"
            action="{{ route('products.index') }}"
            class="mb-4 flex items-center gap-3"
        >
            <input
                type="search"
                name="search"
                value="{{ $search }}"
                placeholder="Buscar produto..."
                class="focus:border-primary-950 w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm transition outline-none"
            />

            <select
                name="category"
                class="focus:border-primary-950 w-52 rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-700 transition outline-none"
            >
                <option value="">Todas as categorias</option>
                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected ($categoryId == $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button
                type="submit"
                class="bg-primary-950 hover:bg-primary-900 cursor-pointer rounded-md px-4 py-2 text-xs font-semibold text-white transition"
            >
                Buscar
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full table-fixed border-collapse">
                <thead>
                    <tr class="border-b border-neutral-200">
                        <th
                            class="w-[32%] px-2 py-2 text-left text-[11px] font-semibold tracking-wide text-neutral-500 uppercase"
                        >
                            Produto
                        </th>

                        <th
                            class="w-[21%] px-2 py-2 text-left text-[11px] font-semibold tracking-wide text-neutral-500 uppercase"
                        >
                            Categoria
                        </th>

                        <th
                            class="w-[15%] px-2 py-2 text-left text-[11px] font-semibold tracking-wide text-neutral-500 uppercase"
                        >
                            Preço
                        </th>

                        <th
                            class="w-[25%] px-2 py-2 text-left text-[11px] font-semibold tracking-wide text-neutral-500 uppercase"
                        >
                            Estoque
                        </th>

                        <th
                            class="w-[7%] px-2 py-2 text-left text-[11px] font-semibold tracking-wide text-neutral-500 uppercase"
                        >
                            Ações
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr
                            class="border-b border-neutral-200 last:border-b-0 hover:bg-neutral-50"
                        >
                            <td class="px-2 py-2">
                                <p class="text-xs font-semibold text-neutral-900">{{ $product->name }}</p>

                                <p class="mt-1 text-[10px] text-neutral-500">Produto #{{ $product->id }}</p>
                            </td>

                            <td class="px-2 py-2 text-xs text-neutral-700">
                                {{ $product->category->name }}
                            </td>

                            <td class="px-2 py-2 text-xs text-neutral-700">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </td>

                            <td class="px-2 py-2">
                                <div class="w-48">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span
                                            class="text-xs font-semibold text-neutral-700"
                                        >
                                            {{ $product->stock }} unidades
                                        </span>

                                        <span
                                            class="text-[11px] font-normal text-neutral-500"
                                        >
                                            @if ($product->minimum_stock > 0)
                                                Mínimo: {{ $product->minimum_stock }}
                                            @else
                                                sem mínimo
                                            @endif
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 h-2 overflow-hidden rounded-full bg-neutral-200"
                                    >
                                        <div
                                            class="h-full rounded-full {{ $product->low_stock ? 'bg-red-500' : 'bg-primary-950' }}"
                                            style="width: {{ $product->stock_percentage }}%"
                                        ></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-2 py-2">
                                <div class="flex justify-center">
                                    <a
                                        href="{{ route('products.edit', $product) }}"
                                        class="hover:bg-primary-50 hover:text-primary-950 flex h-8 w-8 items-center justify-center rounded-md border border-neutral-200 text-neutral-500 transition"
                                        title="Editar produto"
                                        aria-label="Editar produto"
                                    >
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
                                class="px-2 py-8 text-center text-sm text-neutral-500"
                            >
                                Nenhum produto encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>
@endsection
