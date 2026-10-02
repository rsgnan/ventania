@if ($paginator->hasPages())
    <nav
        role="navigation"
        aria-label="{{ __('Navegação da paginação') }}"
        class="flex items-center justify-center gap-1"
    >
        {{-- Página anterior --}}
        @if ($paginator->onFirstPage())
            <span
                aria-disabled="true"
                aria-label="{{ __('Página anterior') }}"
                class="inline-flex h-8 w-8 items-center justify-center rounded-md text-neutral-300"
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
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </span>
        @else
            <a
                href="{{ $paginator->previousPageUrl() }}"
                rel="prev"
                aria-label="{{ __('Página anterior') }}"
                class="hover:bg-primary-50 hover:text-primary-500 inline-flex h-8 w-8 items-center justify-center rounded-md text-neutral-500 transition"
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
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </a>
        @endif

        {{-- Números das páginas --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span
                    aria-hidden="true"
                    class="inline-flex h-8 w-8 items-center justify-center text-sm text-neutral-400"
                >
                    {{ $element }}
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span
                            aria-current="page"
                            aria-label="{{ __('Página: :page', ['page' => $page]) }}"
                            class="bg-primary-950 inline-flex h-8 w-8 items-center justify-center rounded-md text-sm font-semibold text-white"
                        >
                            {{ $page }}
                        </span>
                    @else
                        <a
                            href="{{ $url }}"
                            aria-label="{{ __('Ir para a página :page', ['page' => $page]) }}"
                            class="hover:bg-primary-50 hover:text-primary-950 inline-flex h-8 w-8 items-center justify-center rounded-md text-sm text-neutral-600 transition"
                        >
                            {{ $page }}
                        </a>
                    @endif

                @endforeach

            @endif
        @endforeach

        {{-- Próxima página --}}
        @if ($paginator->hasMorePages())
            <a
                href="{{ $paginator->nextPageUrl() }}"
                rel="next"
                aria-label="{{ __('Próxima página') }}"
                class="hover:bg-primary-50 hover:text-primary-950 inline-flex h-8 w-8 items-center justify-center rounded-md text-neutral-500 transition"
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
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        @else
            <span
                aria-disabled="true"
                aria-label="{{ __('Próxima página') }}"
                class="inline-flex h-8 w-8 items-center justify-center rounded-md text-neutral-300"
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
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </span>
        @endif
    </nav>
@endif
