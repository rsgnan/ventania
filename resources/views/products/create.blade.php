@extends('layouts.app')

@section('title', 'Novo Produto - Ventania')

@section('content')
    <header>
        <h1 class="text-xl font-bold text-neutral-900">Novo produto</h1>

        <p class="mt-0.5 text-xs text-neutral-500">Cadastre um novo produto no sistema.</p>
    </header>

    <form class="mt-6" method="POST" enctype="multipart/form-data" action="{{ route('products.store') }}">
        @csrf

        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3">
                <ul class="space-y-1 text-xs text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-5 overflow-hidden rounded-xl border border-neutral-200 bg-white">
            <div class="border-b border-neutral-200 px-5 py-4">
                <h2 class="text-[13px] font-semibold text-neutral-900">Informações gerais</h2>

                <p class="mt-0.5 text-xs text-neutral-500">Dados principais e descrição do produto.</p>
            </div>

            <div class="p-5">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="mb-1 block text-xs font-medium text-neutral-700"> Nome </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            class="focus:border-primary-950 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 transition outline-none"
                        />
                    </div>

                    <div>
                        <label for="category_id" class="mb-1 block text-xs font-medium text-neutral-700">
                            Categoria
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            class="focus:border-primary-950 w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 transition outline-none"
                        >
                            <option value="">Categoria do produto</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="description" class="mb-1 block text-xs font-medium text-neutral-700">
                            Descrição
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="focus:border-primary-950 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm transition outline-none"
                        >{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-5 overflow-hidden rounded-xl border border-neutral-200 bg-white">
            <div class="border-b border-neutral-200 px-5 py-4">
                <h2 class="text-[13px] font-semibold text-neutral-900">Preço e Estoque</h2>

                <p class="mt-0.5 text-xs text-neutral-500">Valores de venda e controle de inventário.</p>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="price" class="mb-1 block text-xs font-medium text-neutral-700"> Preço </label>

                        <input
                            id="price"
                            name="price"
                            type="number"
                            min="0"
                            step="0.01"
                            value="{{ old('price') }}"
                            class="focus:border-primary-950 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm transition outline-none"
                        />
                    </div>

                    <div>
                        <label for="minimum_stock" class="mb-1 block text-xs font-medium text-neutral-700">
                            Estoque mínimo
                        </label>

                        <input
                            id="minimum_stock"
                            name="minimum_stock"
                            type="number"
                            min="0"
                            step="1"
                            value="{{ old('minimum_stock') }}"
                            class="focus:border-primary-950 w-full rounded-md border border-neutral-300 px-3 py-2 text-sm transition outline-none"
                        />
                        <p class="mt-1 text-[11px] text-neutral-400">
                            Valor usado para identificar quando o estoque está baixo.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-5 overflow-hidden rounded-xl border border-neutral-200 bg-white">
            <div class="border-b border-neutral-200 px-5 py-4">
                <h2 class="text-[13px] font-semibold text-neutral-900">Imagem do produto</h2>

                <p class="mt-1 text-xs text-neutral-500">Imagem utilizada para identificar o produto.</p>
            </div>

            <div class="p-5">
                <label
                    for="image"
                    id="image-dropzone"
                    class="hover:border-primary-950 relative flex min-h-55 cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-neutral-300 bg-neutral-50 px-5 py-8 text-center transition hover:bg-neutral-100"
                >
                    {{-- Preview --}}
                    <img
                        id="image-preview"
                        src=""
                        alt="Pré-visualização da imagem"
                        class="mb-4 hidden max-h-48 max-w-full rounded-lg object-contain"
                    />

                    <div id="image-placeholder">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-white text-neutral-400 shadow-sm">
                            <svg
                                class="h-6 w-6"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>

                        <p class="text-xs font-medium text-neutral-700">Adicione uma imagem</p>

                        <p class="mt-1 text-[11px] text-neutral-700">
                            Arraste uma imagem aqui e clique para selecionar
                        </p>

                        <p class="mt-2 text-[10px] text-neutral-400">JPG, JPEG, PNG ou WebP - Máx. 5 MB</p>
                    </div>

                    <div id="image-selected" class="hidden">
                        <p id="image-name" class="max-w-md truncate text-xs font-medium text-neutral-700"></p>

                        <p class="mt-1 text-[11px] text-neutral-500">Clique para selecionar outra imagem</p>
                    </div>

                    <input
                        id="image"
                        name="image"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="hidden"
                    />
                </label>

                <button
                    type="button"
                    id="remove-image"
                    class="mt-3 hidden items-center gap-1.5 text-xs font-medium text-red-600 transition hover:text-red-700"
                >
                    <svg
                        class="h-4 w-4 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span>Remover imagem</span>
                </button>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a
                href="{{ route('products.index') }}"
                class="rounded-md border border-neutral-300 px-3 py-2 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-50"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="bg-primary-950 hover:bg-primary-900 cursor-pointer rounded-md px-3 py-2 text-xs font-semibold text-white transition"
            >
                Cadastrar produto
            </button>
        </div>
    </form>
@endsection
