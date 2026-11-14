<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ShieldCheck, Warehouse } from 'lucide-vue-next';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <!-- Painel Institucional Esquerdo (Visível em Desktop) -->
        <div class="relative hidden h-full flex-col bg-zinc-900 p-10 text-white dark:border-r lg:flex">
            <div class="absolute inset-0 bg-zinc-900" />

            <!-- Identidade Visual Ventania no Topo -->
            <Link href="/" class="relative z-20 flex items-center gap-2.5 text-lg font-medium">
                <div class="flex aspect-square size-9 items-center justify-center rounded-lg bg-white text-zinc-900 shadow-sm">
                    <Warehouse class="size-5 text-zinc-900" />
                </div>
                <div class="grid text-left leading-tight">
                    <span class="font-bold tracking-tight text-white">Ventania</span>
                    <span class="text-xs text-zinc-400">ERP &amp; Hub Comercial</span>
                </div>
            </Link>

            <!-- Rodapé Institucional Esquerdo -->
            <div class="relative z-20 mt-auto">
                <div class="rounded-lg border border-zinc-800 bg-zinc-950/60 p-4 backdrop-blur">
                    <div class="mb-2 flex items-center gap-2 text-sm font-medium text-emerald-400">
                        <ShieldCheck class="size-4" />
                        Controle Transacional e Auditoria Kardex
                    </div>
                    <p class="text-xs leading-relaxed text-zinc-400">
                        Gestão centralizada de vendas físicas, composição dinâmica de kits e rastreabilidade completa de estoque em tempo real.
                    </p>
                </div>
            </div>
        </div>

        <!-- Coluna Direita: Formulário de Login -->
        <div class="lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[360px]">
                <!-- Logo visível apenas no celular (quando o painel esquerdo está oculto) -->
                <div class="flex justify-center lg:hidden">
                    <div class="flex items-center gap-2.5">
                        <div class="flex aspect-square size-9 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                            <Warehouse class="size-5" />
                        </div>
                        <div class="grid text-left leading-tight">
                            <span class="font-bold tracking-tight">Ventania</span>
                            <span class="text-xs text-muted-foreground">ERP &amp; Hub</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col space-y-2 text-center">
                    <h1 v-if="title" class="text-xl font-semibold tracking-tight">{{ title }}</h1>
                    <p v-if="description" class="text-sm text-muted-foreground">{{ description }}</p>
                </div>

                <slot />
            </div>
        </div>
    </div>
</template>
