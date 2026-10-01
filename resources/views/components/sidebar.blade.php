<aside class="fixed inset-y-0 left-0 flex h-screen w-60 shrink-0 flex-col bg-primary-950 px-4 py-6 text-neutral-200">
    <div class="mb-3 border-b border-white/10 pb-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-white text-sm font-bold text-primary-950">
                V
            </div>
            <div class="min-w-0">
                <span class="block text-[13px] leading-4 font-bold text-white">Ventania</span>
                <span class="mt-0.5 block text-[10px] leading-4 text-neutral-400">Gestão de estoque e vendas</span>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto">
        <p class="px-2 pt-3 pb-1 text-[11px] font-semibold tracking-wide text-neutral-400 uppercase">Geral</p>

        <a
            href="{{ route('home') }}"
            class="flex items-center gap-3 rounded-md px-2 py-2 text-xs font-medium transition 
            {{
                request()->routeIs('home')
                ? 'bg-white/12 font-semibold text-white'
                : 'text-neutral-300 hover:bg-white/6'
            }}">
            <svg
                class="h-4 w-4"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true">
                <path d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            <span>Dashboard</span>
        </a>

        <p class="px-2 pt-3 pb-1 text-[11px] font-semibold tracking-wide text-neutral-400 uppercase">Gestão</p>

        <a
            href="{{ route('products.index') }}"
            class="flex items-center gap-3 rounded-md px-2 py-2 text-xs font-medium transition 
            {{
                request()->routeIs('products.*')
                ? 'bg-white/12 font-semibold text-white'
                : 'text-neutral-300 hover:bg-white/6'
            }}">
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

            <span>Produtos</span>
        </a>

        <span class="mt-0.5 flex items-center gap-3 rounded-md px-2 py-2 text-xs font-medium text-neutral-300">
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

            <span>Estoque</span>
        </span>

        <p class="px-2 pt-3 pb-1 text-[11px] font-semibold tracking-wide text-neutral-400 uppercase">Comercial</p>

        <span class="flex items-center gap-3 rounded-md px-2 py-2 text-xs font-medium text-neutral-300">
            <svg
                class="h-4 w-4"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true">
                <path d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
            <span>Vendas</span>
        </span>

        <p class="px-2 pt-3 pb-1 text-[11px] font-semibold tracking-wide text-neutral-400 uppercase">Administração</p>

        <span class="flex items-center gap-3 rounded-md px-2 py-2 text-xs font-medium text-neutral-300">
            <svg
                class="h-4 w-4"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true">
                <path d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>

            <span>Usuários</span>
        </span>
    </nav>

    <div class="border-t border-white/10 pt-4">
        <div class="flex items-center gap-2.5 px-2">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-white text-xs font-bold text-primary-950">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="min-w-0">
                <p class="truncate text-xs font-semibold text-white">{{ auth()->user()->name }}</p>

                <p class="text-[11px] text-neutral-400">
                    {{ auth()->user()->role === 'admin' ? 'Administrador' : 'Operador' }}
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf

            <button
                type="submit"
                class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-md border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-neutral-200 transition hover:bg-white/10 hover:text-white">
                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>

                <span>Sair</span>
            </button>
        </form>
    </div>
</aside>