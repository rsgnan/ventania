<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetFooter,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import {
    ArrowDownLeft,
    ArrowUpDown,
    ArrowUpRight,
    Boxes,
    Plus,
    Search,
    Trash2,
    TrendingDown,
    TrendingUp,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface SimpleProductOption {
    id: number;
    name: string;
    sku: string;
    stock_quantity: number;
}

interface MovementItemRow {
    id: number;
    product_id: number;
    quantity: number;
    previous_stock: number;
    new_stock: number;
    product?: {
        id: number;
        name: string;
        sku: string;
    };
}

interface MovementRow {
    id: number;
    user_id: number;
    type: 'in' | 'out';
    notes: string | null;
    created_at: string;
    user?: {
        id: number;
        name: string;
    };
    items: MovementItemRow[];
}

interface Props {
    movements: {
        data: MovementRow[];
        current_page: number;
        last_page: number;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    filters: {
        search?: string;
        tab?: string;
    };
    simpleProducts: SimpleProductOption[];
    stats: {
        total: number;
        entries: number;
        exits: number;
        units_in: number;
        units_out: number;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Movimentações', href: '/stock-movements' },
];

// Estado dos filtros da listagem
const search = ref(props.filters.search ?? '');
const activeTab = ref(props.filters.tab ?? '');

let searchTimeout: ReturnType<typeof setTimeout>;
const applyFilters = () => {
    router.get(
        '/stock-movements',
        {
            search: search.value || undefined,
            tab: activeTab.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 300);
});

watch(activeTab, applyFilters);

// Controle do painel lateral (<Sheet>) de Movimentação em Lote
const isSheetOpen = ref(false);

const form = useForm({
    type: 'in' as 'in' | 'out',
    notes: '',
    items: [] as Array<{ product_id: number | string; quantity: number }>,
});

// Sugestões rápidas de motivo com 1 clique de acordo com o tipo da operação
const notePresets = computed(() =>
    form.type === 'in'
        ? ['Compra de fornecedor', 'Ajuste de inventário', 'Devolução de cliente']
        : ['Ajuste de inventário', 'Perda ou avaria', 'Uso interno'],
);

// Soma total de unidades físicas sendo movimentadas no lote atual
const totalBatchUnits = computed(() =>
    form.items.reduce((sum, item) => sum + Math.max(0, Number(item.quantity || 0)), 0),
);

const isProductSelected = (productId: number, currentIndex: number): boolean => {
    return form.items.some(
        (item, idx) => idx !== currentIndex && Number(item.product_id) === productId,
    );
};

const addItemRow = () => {
    const nextAvailable = props.simpleProducts.find(
        (p) => !form.items.some((item) => Number(item.product_id) === p.id),
    );

    if (!nextAvailable) return;

    form.items.push({
        product_id: nextAvailable.id,
        quantity: 1,
    });
};

const removeItemRow = (index: number) => {
    form.items.splice(index, 1);
};

const openCreateSheet = () => {
    form.reset();
    form.clearErrors();
    form.type = 'in';
    form.notes = '';
    form.items = [];
    addItemRow();
    isSheetOpen.value = true;
};

const submitForm = () => {
    form.post('/stock-movements', {
        preserveScroll: true,
        onSuccess: () => {
            isSheetOpen.value = false;
            form.reset();
        },
    });
};

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(dateString));
};
</script>

<template>
    <Head title="Movimentações de Estoque" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-w-0 flex-1 flex-col gap-4 p-4 sm:gap-6 sm:p-6">
            <!-- Cabeçalho da Página -->
            <div>
                <h1 class="text-xl font-bold tracking-tight sm:text-2xl">Movimentações de Estoque</h1>
                <p class="text-sm text-muted-foreground">
                    Histórico Kardex auditável de entradas de fornecedor, ajustes e baixas operacionais.
                </p>
            </div>

            <!-- Cards Superiores de KPIs -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Total de Lançamentos</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <ArrowUpDown class="h-3 w-3" />
                                Kardex
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.total }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Rastreabilidade completa
                            <ArrowUpDown class="h-4 w-4 shrink-0 text-muted-foreground" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Operações transacionais imutáveis
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Operações de Entrada</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <TrendingUp class="h-3 w-3 text-emerald-500" />
                                +{{ stats.units_in }} un.
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.entries }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Abastecimento de estoque
                            <ArrowDownLeft class="h-4 w-4 shrink-0 text-emerald-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Compras de fornecedor e ajustes positivos
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Operações de Saída</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <TrendingDown class="h-3 w-3 text-destructive" />
                                -{{ stats.units_out }} un.
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.exits }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Baixas e retiradas físicas
                            <ArrowUpRight class="h-4 w-4 shrink-0 text-destructive" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Vendas concluídas, perdas e avarias
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Saldo Físico Atual</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <Boxes class="h-3 w-3 text-primary" />
                                Balanço
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.units_in - stats.units_out }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Unidades físicas na loja
                            <Boxes class="h-4 w-4 shrink-0 text-primary" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Resultado exato de Entradas menos Saídas
                        </div>
                    </CardFooter>
                </Card>
            </div>

            <!-- Barra de Ferramentas: Pills (Esquerda) + Busca e Botão Nova Movimentação (Direita) -->
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="inline-flex w-fit flex-wrap items-center gap-1 rounded-lg bg-muted/70 p-1">
                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                        :class="
                            activeTab === ''
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="activeTab = ''"
                    >
                        Todas
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.total }}
                        </span>
                    </button>

                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                        :class="
                            activeTab === 'in'
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="activeTab = 'in'"
                    >
                        Entradas
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.entries }}
                        </span>
                    </button>

                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                        :class="
                            activeTab === 'out'
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="activeTab = 'out'"
                    >
                        Saídas
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.exits }}
                        </span>
                    </button>
                </div>

                <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                    <div class="relative w-full sm:w-72">
                        <Search class="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
                        <Input
                            v-model="search"
                            placeholder="Filtrar SKU, produto ou motivo..."
                            class="h-9 pl-9"
                        />
                    </div>

                    <Button class="h-9 w-full sm:w-auto" @click="openCreateSheet">
                        <Plus class="h-4 w-4" />
                        Nova Movimentação
                    </Button>
                </div>
            </div>

            <!-- Tabela de Movimentações com Scroll Horizontal Isolado -->
            <div class="w-full overflow-hidden rounded-md border bg-card">
                <div class="w-full overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-16 whitespace-nowrap">ID</TableHead>
                                <TableHead class="w-32 whitespace-nowrap">Tipo</TableHead>
                                <TableHead class="whitespace-nowrap">Itens Movimentados (Extrato Kardex)</TableHead>
                                <TableHead class="whitespace-nowrap">Motivo / Origem</TableHead>
                                <TableHead class="whitespace-nowrap">Operador</TableHead>
                                <TableHead class="whitespace-nowrap text-right">Data / Hora</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="mov in movements.data" :key="mov.id">
                                <TableCell class="whitespace-nowrap font-mono text-xs text-muted-foreground">
                                    #{{ mov.id }}
                                </TableCell>
                                <TableCell class="whitespace-nowrap">
                                    <Badge
                                        :variant="mov.type === 'in' ? 'outline' : 'destructive'"
                                        class="inline-flex items-center gap-1"
                                    >
                                        <ArrowDownLeft
                                            v-if="mov.type === 'in'"
                                            class="h-3 w-3 text-emerald-500"
                                        />
                                        <ArrowUpRight v-else class="h-3 w-3" />
                                        {{ mov.type === 'in' ? 'Entrada' : 'Saída' }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <div class="flex flex-wrap gap-1.5">
                                        <span
                                            v-for="item in mov.items"
                                            :key="item.id"
                                            class="inline-flex items-center gap-1.5 rounded border bg-muted/40 px-2 py-0.5 text-xs"
                                        >
                                            <strong
                                                class="font-mono tabular-nums"
                                                :class="
                                                    mov.type === 'in'
                                                        ? 'text-emerald-600 dark:text-emerald-400'
                                                        : 'text-destructive'
                                                "
                                            >
                                                {{ mov.type === 'in' ? `+${item.quantity}` : `-${item.quantity}` }}
                                            </strong>
                                            <span class="font-mono text-muted-foreground">
                                                {{ item.product?.sku }}
                                            </span>
                                            <span class="font-medium">{{ item.product?.name }}</span>
                                            <span class="rounded bg-background px-1.5 py-0.2 font-mono text-[10px] text-muted-foreground tabular-nums">
                                                {{ item.previous_stock }} → {{ item.new_stock }} un.
                                            </span>
                                        </span>
                                    </div>
                                </TableCell>
                                <TableCell class="whitespace-nowrap text-sm text-muted-foreground">
                                    {{ mov.notes || 'Sem observação' }}
                                </TableCell>
                                <TableCell class="whitespace-nowrap text-sm">
                                    {{ mov.user?.name }}
                                </TableCell>
                                <TableCell class="whitespace-nowrap text-right text-xs text-muted-foreground tabular-nums">
                                    {{ formatDate(mov.created_at) }}
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="movements.data.length === 0">
                                <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                                    Nenhuma movimentação de estoque encontrada.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- Rodapé de Paginação -->
                <div class="flex flex-col items-center justify-between gap-2 border-t px-4 py-3 text-sm text-muted-foreground sm:flex-row">
                    <div>
                        Mostrando página {{ movements.current_page }} de {{ movements.last_page }} ({{ movements.total }} lançamentos)
                    </div>
                    <div class="flex gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!movements.prev_page_url"
                            @click="movements.prev_page_url && router.get(movements.prev_page_url, {}, { preserveState: true, preserveScroll: true })"
                        >
                            Anterior
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!movements.next_page_url"
                            @click="movements.next_page_url && router.get(movements.next_page_url, {}, { preserveState: true, preserveScroll: true })"
                        >
                            Próxima
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Painel Lateral (<Sheet>) Compacto Padronizado -->
        <Sheet v-model:open="isSheetOpen">
            <SheetContent class="flex h-full flex-col p-0 sm:max-w-md">
                <SheetHeader class="border-b px-5 py-3.5">
                    <SheetTitle class="text-base">Registrar Movimentação em Lote</SheetTitle>
                    <SheetDescription class="text-sm">
                        Adicione um ou mais produtos físicos (PRO) e confira o volume total antes de confirmar.
                    </SheetDescription>
                </SheetHeader>

                <form class="flex min-h-0 flex-1 flex-col" autocomplete="off" @submit.prevent="submitForm">
                    <!-- Corpo rolável compacto (gap-3 e px-5 py-4) -->
                    <div class="flex flex-1 flex-col gap-3 overflow-y-auto px-5 py-4">
                        <!-- 1. Tipo de Operação -->
                        <div class="grid gap-1.5">
                            <Label>Tipo de operação</Label>
                            <div class="grid grid-cols-2 gap-2">
                                <Button
                                    type="button"
                                    :variant="form.type === 'in' ? 'default' : 'outline'"
                                    class="w-full"
                                    @click="form.type = 'in'"
                                >
                                    <ArrowDownLeft class="h-4 w-4" />
                                    Entrada (+ Estoque)
                                </Button>
                                <Button
                                    type="button"
                                    :variant="form.type === 'out' ? 'default' : 'outline'"
                                    class="w-full"
                                    @click="form.type = 'out'"
                                >
                                    <ArrowUpRight class="h-4 w-4" />
                                    Saída (- Estoque)
                                </Button>
                            </div>
                            <InputError :message="form.errors.type" />
                        </div>

                        <!-- 2. Identificação da Nota Fiscal / Motivo + Sugestões com 1 Clique -->
                        <div class="grid gap-1.5">
                            <Label for="notes">Identificação da Nota Fiscal / Motivo</Label>
                            <Input
                                id="notes"
                                v-model="form.notes"
                                :placeholder="
                                    form.type === 'in'
                                        ? 'ex: Entrada NF 4510 - Fornecedor'
                                        : 'ex: Ajuste de inventário, Perda ou avaria'
                                "
                            />
                            <div class="flex flex-wrap gap-1.5 pt-0.5">
                                <button
                                    v-for="preset in notePresets"
                                    :key="preset"
                                    type="button"
                                    class="rounded-md border bg-muted/40 px-2.5 py-1 text-xs text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                                    @click="form.notes = preset"
                                >
                                    {{ preset }}
                                </button>
                            </div>
                            <InputError :message="form.errors.notes" />
                        </div>

                        <!-- 3. Quadro de Itens da Movimentação e Totalizador de Unidades -->
                        <div class="flex flex-col gap-2.5 rounded-lg border bg-muted/30 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <div class="text-sm font-medium">
                                        Itens da Movimentação ({{ form.items.length }})
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        Produtos físicos e quantidade movimentada.
                                    </div>
                                </div>
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    :disabled="form.items.length >= simpleProducts.length"
                                    @click="addItemRow"
                                >
                                    <Plus class="h-3.5 w-3.5" />
                                    Adicionar produto
                                </Button>
                            </div>

                            <!-- Lista de Produtos do Lote (Em linha única compacta) -->
                            <div
                                v-for="(item, index) in form.items"
                                :key="index"
                                class="flex items-center gap-2"
                            >
                                <select
                                    v-model="item.product_id"
                                    required
                                    class="h-9 min-w-0 flex-1 truncate rounded-md border border-input bg-background px-2.5 text-xs ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring"
                                >
                                    <option
                                        v-for="simple in simpleProducts"
                                        :key="simple.id"
                                        :value="simple.id"
                                        :disabled="isProductSelected(simple.id, index)"
                                    >
                                        {{ simple.sku }} — {{ simple.name }} (Saldo: {{ simple.stock_quantity }} un.)
                                    </option>
                                </select>

                                <Input
                                    v-model.number="item.quantity"
                                    type="number"
                                    min="1"
                                    required
                                    class="w-16 shrink-0 text-center tabular-nums"
                                />

                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="icon"
                                    class="h-9 w-9 shrink-0 text-muted-foreground hover:text-destructive"
                                    :disabled="form.items.length <= 1"
                                    @click="removeItemRow(index)"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>

                            <InputError :message="form.errors.items" />

                            <!-- Resumo de Conferência do Lote (Total de Peças e SKUs) -->
                            <div class="flex items-center justify-between border-t pt-2 text-xs">
                                <span class="text-muted-foreground">
                                    Total de peças no lote ({{ form.items.length }} {{ form.items.length === 1 ? 'SKU' : 'SKUs' }}):
                                </span>
                                <span class="text-sm font-semibold tabular-nums">
                                    {{ totalBatchUnits }} {{ totalBatchUnits === 1 ? 'unidade' : 'unidades' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Rodapé fixo compacto -->
                    <SheetFooter class="border-t bg-background px-5 py-3.5">
                        <Button type="button" variant="outline" @click="isSheetOpen = false">
                            Cancelar
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{
                                form.processing
                                    ? 'Processando...'
                                    : form.type === 'in'
                                      ? 'Confirmar Entrada do Lote'
                                      : 'Confirmar Saída do Lote'
                            }}
                        </Button>
                    </SheetFooter>
                </form>
            </SheetContent>
        </Sheet>
    </AppLayout>
</template>