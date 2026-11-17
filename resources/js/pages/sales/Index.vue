<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import ProductCombobox from '@/components/ProductCombobox.vue';
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
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import {
    Ban,
    CheckCircle2,
    Clock,
    CreditCard,
    Eye,
    Layers,
    Pencil,
    Plus,
    Search,
    Trash2,
    TrendingUp,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface KitComponentInfo {
    id: number;
    name: string;
    sku: string;
    stock_quantity: number;
    pivot: {
        quantity: number;
    };
}

interface CatalogProductOption {
    id: number;
    name: string;
    sku: string;
    type: 'simple' | 'kit';
    price: string | number;
    available_stock: number;
    components?: KitComponentInfo[];
}

interface SaleItemRow {
    id: number;
    product_id: number;
    quantity: number;
    unit_price: string | number;
    subtotal: string | number;
    product?: {
        id: number;
        name: string;
        sku: string;
        type: 'simple' | 'kit';
        price: string | number;
        components?: KitComponentInfo[];
    };
}

interface SaleRow {
    id: number;
    user_id: number;
    customer_name: string;
    status: 'pending' | 'completed' | 'cancelled';
    payment_method: 'pix' | 'credit_card' | 'debit_card' | 'cash';
    discount: string | number;
    total_amount: string | number;
    notes: string | null;
    completed_at: string | null;
    cancelled_at: string | null;
    created_at: string;
    user?: {
        id: number;
        name: string;
    };
    items: SaleItemRow[];
}

interface Props {
    sales: {
        data: SaleRow[];
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
    catalogProducts: CatalogProductOption[];
    paymentMethods: Array<{ value: string; label: string }>;
    stats: {
        total: number;
        pending: number;
        completed: number;
        cancelled: number;
        revenue: number;
        pending_amount: number;
    };
}

const props = defineProps<Props>();
const page = usePage<SharedData>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Vendas', href: '/sales' },
];

// Estado dos filtros da listagem
const search = ref(props.filters.search ?? '');
const activeTab = ref(props.filters.tab ?? '');

let searchTimeout: ReturnType<typeof setTimeout>;
const applyFilters = () => {
    router.get(
        '/sales',
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

// Controle do painel lateral (<Sheet>)
const isSheetOpen = ref(false);
const editingSale = ref<SaleRow | null>(null);

// Vendas Concluídas ou Canceladas são abertas em modo Somente Leitura (Recibo)
const isReadOnly = computed(
    () => editingSale.value !== null && editingSale.value.status !== 'pending',
);

const form = useForm({
    customer_name: '',
    status: 'pending' as 'pending' | 'completed' | 'cancelled',
    payment_method: 'pix' as 'pix' | 'credit_card' | 'debit_card' | 'cash',
    discount: 0 as number | string,
    notes: '',
    items: [] as Array<{ product_id: number | string; quantity: number }>,
});

const customerPresets = ['Consumidor Final', 'Cliente Balcão'];

const getProductById = (productId: number | string): CatalogProductOption | undefined => {
    return props.catalogProducts.find((p) => p.id === Number(productId));
};

const addItemRow = () => {
    const nextAvailable = props.catalogProducts.find(
        (p) => !form.items.some((item) => Number(item.product_id) === p.id),
    );

    if (!nextAvailable) return;

    form.items.push({
        product_id: nextAvailable.id,
        quantity: 1,
    });
};

const removeItemRow = (index: number) => {
    if (form.items.length <= 1) return;
    form.items.splice(index, 1);
};

// Cálculos financeiros em tempo real no <Sheet>
const itemsSubtotal = computed(() => {
    if (isReadOnly.value && editingSale.value) {
        return editingSale.value.items.reduce((sum, item) => sum + Number(item.subtotal), 0);
    }

    return form.items.reduce((sum, item) => {
        const product = getProductById(item.product_id);
        return sum + (product ? Number(product.price) * Math.max(1, Number(item.quantity || 0)) : 0);
    }, 0);
});

const finalTotal = computed(() => {
    return Math.max(0, itemsSubtotal.value - Math.max(0, Number(form.discount || 0)));
});

const totalUnitsInSale = computed(() => {
    return form.items.reduce((sum, item) => sum + Math.max(0, Number(item.quantity || 0)), 0);
});

const openCreateSheet = () => {
    editingSale.value = null;
    form.reset();
    form.clearErrors();
    form.customer_name = '';
    form.status = 'pending';
    form.payment_method = 'pix';
    form.discount = 0;
    form.notes = '';
    form.items = [];
    addItemRow();
    isSheetOpen.value = true;
};

const openSaleSheet = (sale: SaleRow) => {
    editingSale.value = sale;
    form.clearErrors();
    form.customer_name = sale.customer_name;
    form.status = sale.status;
    form.payment_method = sale.payment_method;
    form.discount = Number(sale.discount);
    form.notes = sale.notes ?? '';
    form.items = sale.items.map((item) => ({
        product_id: item.product_id,
        quantity: item.quantity,
    }));
    isSheetOpen.value = true;
};

const submitForm = () => {
    if (isReadOnly.value) {
        isSheetOpen.value = false;
        return;
    }

    if (editingSale.value) {
        form.put(`/sales/${editingSale.value.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                isSheetOpen.value = false;
                form.reset();
            },
        });
        return;
    }

    form.post('/sales', {
        preserveScroll: true,
        onSuccess: () => {
            isSheetOpen.value = false;
            form.reset();
        },
    });
};

const formatCurrency = (value: number | string) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(Number(value || 0));
};

// Opções formatadas para o ProductCombobox exibindo Preço e Estoque Disponível
const comboboxCatalogProducts = computed(() =>
    props.catalogProducts.map((p) => ({
        id: p.id,
        name: p.name,
        sku: p.sku,
        meta: `${formatCurrency(p.price)} | Disp: ${p.available_stock}`,
    })),
);

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(dateString));
};

const getPaymentLabel = (method: string) => {
    return props.paymentMethods.find((m) => m.value === method)?.label ?? method;
};

const averageTicket = computed(() => {
    if (!props.stats.completed) return formatCurrency(0);
    return formatCurrency(props.stats.revenue / props.stats.completed);
});

const getPercentage = (value: number) => {
    if (!props.stats.total) return '0%';
    return `${((value / props.stats.total) * 100).toFixed(1)}%`;
};
</script>

<template>
    <Head title="Gestão de Vendas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-w-0 flex-1 flex-col gap-4 p-4 sm:gap-6 sm:p-6">
            <!-- Cabeçalho da Página -->
            <div>
                <h1 class="text-xl font-bold tracking-tight sm:text-2xl">Vendas</h1>
                <p class="text-sm text-muted-foreground">
                    Registre pedidos de balcão, acompanhe pendências e conclua vendas com baixa automática de estoque.
                </p>
            </div>

            <!-- Cards Superiores de KPIs (Padrão Shadcn Dashboard) -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Receita Concluída</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <TrendingUp class="h-3 w-3 text-emerald-500" />
                                Ticket {{ averageTicket }}
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ formatCurrency(stats.revenue) }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Faturamento líquido efetivado
                            <TrendingUp class="h-4 w-4 shrink-0 text-emerald-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Soma das vendas concluídas (com descontos)
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Em Aberto (Pendentes)</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <Clock class="h-3 w-3 text-amber-500" />
                                {{ stats.pending }} {{ stats.pending === 1 ? 'pedido' : 'pedidos' }}
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ formatCurrency(stats.pending_amount) }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Aguardando fechamento
                            <Clock class="h-4 w-4 shrink-0 text-amber-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Permite edição livre antes da baixa de estoque
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Vendas Concluídas</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <CheckCircle2 class="h-3 w-3 text-emerald-500" />
                                {{ getPercentage(stats.completed) }}
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.completed }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Pedidos finalizados no balcão
                            <CheckCircle2 class="h-4 w-4 shrink-0 text-emerald-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Baixa automática registrada no Kardex
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Vendas Canceladas</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <Ban class="h-3 w-3 text-destructive" />
                                {{ getPercentage(stats.cancelled) }}
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.cancelled }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Pedidos desistidos
                            <Ban class="h-4 w-4 shrink-0 text-destructive" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Mantidos intactos para trilha de auditoria
                        </div>
                    </CardFooter>
                </Card>
            </div>

            <!-- Barra de Ferramentas: Pills (Esquerda) + Busca e Botão Nova Venda (Direita) -->
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
                            activeTab === 'pending'
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="activeTab = 'pending'"
                    >
                        Pendentes
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.pending }}
                        </span>
                    </button>

                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                        :class="
                            activeTab === 'completed'
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="activeTab = 'completed'"
                    >
                        Concluídas
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.completed }}
                        </span>
                    </button>

                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                        :class="
                            activeTab === 'cancelled'
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="activeTab = 'cancelled'"
                    >
                        Canceladas
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.cancelled }}
                        </span>
                    </button>
                </div>

                <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                    <div class="relative w-full sm:w-72">
                        <Search class="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
                        <Input
                            v-model="search"
                            placeholder="Filtrar cliente, produto ou SKU..."
                            class="h-9 pl-9"
                        />
                    </div>

                    <Button class="h-9 w-full sm:w-auto" @click="openCreateSheet">
                        <Plus class="h-4 w-4" />
                        Nova Venda
                    </Button>
                </div>
            </div>

            <!-- Tabela de Vendas com Scroll Horizontal Isolado -->
            <div class="w-full overflow-hidden rounded-md border bg-card">
                <div class="w-full overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-14 whitespace-nowrap">ID</TableHead>
                                <TableHead class="whitespace-nowrap">Cliente</TableHead>
                                <TableHead class="whitespace-nowrap">Itens do Pedido</TableHead>
                                <TableHead class="whitespace-nowrap">Pagamento</TableHead>
                                <TableHead class="whitespace-nowrap">Valor Total</TableHead>
                                <TableHead class="whitespace-nowrap">Vendedor</TableHead>
                                <TableHead class="whitespace-nowrap">Status</TableHead>
                                <TableHead class="whitespace-nowrap">Data</TableHead>
                                <TableHead class="w-16 whitespace-nowrap text-center">Ações</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="sale in sales.data" :key="sale.id">
                                <TableCell class="whitespace-nowrap font-mono text-xs text-muted-foreground">
                                    #{{ sale.id }}
                                </TableCell>
                                <TableCell class="whitespace-nowrap">
                                    <div class="font-medium">{{ sale.customer_name }}</div>
                                    <div v-if="sale.notes" class="max-w-[200px] truncate text-xs text-muted-foreground">
                                        {{ sale.notes }}
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <!-- Exibe até 2 itens + contador (+N itens) para manter a linha compacta -->
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span
                                            v-for="item in sale.items.slice(0, 2)"
                                            :key="item.id"
                                            :title="
                                                item.product?.type === 'kit' && item.product.components?.length
                                                    ? 'Kit composto por: ' +
                                                      item.product.components
                                                          .map((c) => `${c.pivot.quantity * item.quantity}x ${c.sku} (${c.name})`)
                                                          .join(', ')
                                                    : undefined
                                            "
                                            class="inline-flex items-center gap-1 rounded border bg-muted/40 px-2 py-0.5 text-xs"
                                        >
                                            <Layers
                                                v-if="item.product?.type === 'kit'"
                                                class="h-3 w-3 text-primary"
                                            />
                                            <strong class="font-mono tabular-nums">{{ item.quantity }}x</strong>
                                            <span class="font-mono text-muted-foreground">{{ item.product?.sku }}</span>
                                            <span class="max-w-[140px] truncate font-medium">{{ item.product?.name }}</span>
                                        </span>

                                        <Badge
                                            v-if="sale.items.length > 2"
                                            variant="secondary"
                                            :title="
                                                sale.items
                                                    .slice(2)
                                                    .map((i) => `${i.quantity}x ${i.product?.sku} (${i.product?.name})`)
                                                    .join(', ')
                                            "
                                            class="text-[10px] tabular-nums"
                                        >
                                            +{{ sale.items.length - 2 }} {{ sale.items.length - 2 === 1 ? 'item' : 'itens' }}
                                        </Badge>
                                    </div>
                                </TableCell>
                                <TableCell class="whitespace-nowrap">
                                    <Badge variant="secondary" class="font-normal">
                                        {{ getPaymentLabel(sale.payment_method) }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="whitespace-nowrap">
                                    <div class="font-semibold tabular-nums">
                                        {{ formatCurrency(sale.total_amount) }}
                                    </div>
                                    <div
                                        v-if="Number(sale.discount) > 0"
                                        class="text-[10px] text-emerald-600 dark:text-emerald-400 tabular-nums"
                                    >
                                        desc: -{{ formatCurrency(sale.discount) }}
                                    </div>
                                </TableCell>
                                <TableCell class="whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-1.5">
                                        <span>{{ sale.user?.name }}</span>
                                        <span
                                            v-if="sale.user_id === page.props.auth.user.id"
                                            class="rounded-full bg-muted px-2 py-0.5 text-[10px] font-semibold text-muted-foreground"
                                        >
                                            Você
                                        </span>
                                    </div>
                                </TableCell>
                                <TableCell class="whitespace-nowrap">
                                    <Badge
                                        :variant="
                                            sale.status === 'completed'
                                                ? 'outline'
                                                : sale.status === 'pending'
                                                  ? 'secondary'
                                                  : 'destructive'
                                        "
                                        class="inline-flex items-center gap-1.5"
                                    >
                                        <span
                                            class="h-1.5 w-1.5 rounded-full"
                                            :class="
                                                sale.status === 'completed'
                                                    ? 'bg-emerald-500'
                                                    : sale.status === 'pending'
                                                      ? 'bg-amber-500'
                                                      : 'bg-current'
                                            "
                                        />
                                        {{
                                            sale.status === 'completed'
                                                ? 'Concluída'
                                                : sale.status === 'pending'
                                                  ? 'Pendente'
                                                  : 'Cancelada'
                                        }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="whitespace-nowrap text-xs text-muted-foreground tabular-nums">
                                    {{ formatDate(sale.created_at) }}
                                </TableCell>
                                <TableCell class="w-16 whitespace-nowrap text-center">
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="h-8 w-8 text-muted-foreground hover:text-foreground"
                                        :title="sale.status === 'pending' ? 'Editar ou concluir venda' : 'Visualizar recibo da venda'"
                                        @click="openSaleSheet(sale)"
                                    >
                                        <Pencil v-if="sale.status === 'pending'" class="h-4 w-4" />
                                        <Eye v-else class="h-4 w-4" />
                                    </Button>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="sales.data.length === 0">
                                <TableCell colspan="9" class="h-24 text-center text-muted-foreground">
                                    Nenhuma venda encontrada com os filtros informados.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- Rodapé de Paginação -->
                <div class="flex flex-col items-center justify-between gap-2 border-t px-4 py-3 text-sm text-muted-foreground sm:flex-row">
                    <div>
                        Mostrando página {{ sales.current_page }} de {{ sales.last_page }} ({{ sales.total }} vendas)
                    </div>
                    <div class="flex gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!sales.prev_page_url"
                            @click="sales.prev_page_url && router.get(sales.prev_page_url, {}, { preserveState: true, preserveScroll: true })"
                        >
                            Anterior
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!sales.next_page_url"
                            @click="sales.next_page_url && router.get(sales.next_page_url, {}, { preserveState: true, preserveScroll: true })"
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
                    <SheetTitle class="text-base">
                        {{
                            !editingSale
                                ? 'Nova Venda'
                                : isReadOnly
                                  ? `Detalhes da Venda #${editingSale.id}`
                                  : `Gerenciar Venda #${editingSale.id}`
                        }}
                    </SheetTitle>
                    <SheetDescription class="text-sm">
                        {{
                            !editingSale
                                ? 'Toda venda nasce como Pendente e só baixa o estoque ao ser Concluída.'
                                : isReadOnly
                                  ? 'Venda finalizada e imutável para garantia da trilha de auditoria.'
                                  : 'Edite os itens livremente ou altere o status para Concluir (baixar estoque) ou Cancelar.'
                        }}
                    </SheetDescription>
                </SheetHeader>

                <form class="flex min-h-0 flex-1 flex-col" autocomplete="off" @submit.prevent="submitForm">
                    <!-- Corpo rolável compacto (gap-3 e px-5 py-4) -->
                    <div class="flex flex-1 flex-col gap-3 overflow-y-auto px-5 py-4">
                        <!-- 1. Seletor de Status (Visível APENAS ao editar ou visualizar uma venda existente) -->
                        <div v-if="editingSale" class="grid gap-1.5">
                            <Label>Status da venda</Label>
                            <div class="grid grid-cols-3 gap-1.5">
                                <Button
                                    type="button"
                                    size="sm"
                                    :variant="form.status === 'pending' ? 'default' : 'outline'"
                                    class="w-full px-2 text-xs"
                                    :disabled="isReadOnly"
                                    @click="form.status = 'pending'"
                                >
                                    <Clock class="h-3.5 w-3.5" />
                                    Pendente
                                </Button>
                                <Button
                                    type="button"
                                    size="sm"
                                    :variant="form.status === 'completed' ? 'default' : 'outline'"
                                    class="w-full px-2 text-xs"
                                    :disabled="isReadOnly"
                                    @click="form.status = 'completed'"
                                >
                                    <CheckCircle2 class="h-3.5 w-3.5" />
                                    Concluída
                                </Button>
                                <Button
                                    type="button"
                                    size="sm"
                                    :variant="form.status === 'cancelled' ? 'destructive' : 'outline'"
                                    class="w-full px-2 text-xs"
                                    :disabled="isReadOnly"
                                    @click="form.status = 'cancelled'"
                                >
                                    <Ban class="h-3.5 w-3.5" />
                                    Cancelada
                                </Button>
                            </div>
                            <InputError :message="form.errors.status" />
                        </div>

                        <!-- 2. Nome do Cliente + Sugestões com 1 Clique -->
                        <div class="grid gap-1.5">
                            <Label for="customer_name">Nome do cliente</Label>
                            <Input
                                id="customer_name"
                                v-model="form.customer_name"
                                required
                                :disabled="isReadOnly"
                                placeholder="ex: Maria Clara Oliveira"
                            />
                            <div v-if="!isReadOnly" class="flex flex-wrap gap-1.5 pt-0.5">
                                <button
                                    v-for="preset in customerPresets"
                                    :key="preset"
                                    type="button"
                                    class="rounded-md border bg-muted/40 px-2.5 py-1 text-xs text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                                    @click="form.customer_name = preset"
                                >
                                    {{ preset }}
                                </button>
                            </div>
                            <InputError :message="form.errors.customer_name" />
                        </div>

                        <!-- 3. Forma de Pagamento em Botões -->
                        <div class="grid gap-1.5">
                            <Label>Forma de pagamento</Label>
                            <div class="grid grid-cols-2 gap-2">
                                <Button
                                    v-for="method in paymentMethods"
                                    :key="method.value"
                                    type="button"
                                    size="sm"
                                    :variant="form.payment_method === method.value ? 'default' : 'outline'"
                                    class="w-full px-2.5 text-xs sm:text-sm"
                                    :disabled="isReadOnly"
                                    @click="form.payment_method = method.value as any"
                                >
                                    <CreditCard class="h-3.5 w-3.5" />
                                    {{ method.label }}
                                </Button>
                            </div>
                            <InputError :message="form.errors.payment_method" />
                        </div>

                        <!-- 4. Quadro de Itens da Venda (Produtos Simples PRO e Kits KIT) -->
                        <div class="flex flex-col gap-2.5 rounded-lg border bg-muted/30 p-3">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <div class="text-sm font-medium">
                                        Itens do Pedido ({{ form.items.length }})
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        Produtos físicos (PRO) ou Kits (KIT) com preço oficial.
                                    </div>
                                </div>
                                <Button
                                    v-if="!isReadOnly"
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    :disabled="form.items.length >= catalogProducts.length"
                                    @click="addItemRow"
                                >
                                    <Plus class="h-3.5 w-3.5" />
                                    Adicionar item
                                </Button>
                            </div>

                            <!-- Lista Editável (Quando Pendente ou Nova Venda) -->
                            <template v-if="!isReadOnly">
                                <div
                                    v-for="(item, index) in form.items"
                                    :key="index"
                                    class="flex flex-col gap-1"
                                >
                                    <div class="flex items-center gap-2">
                                        <ProductCombobox
                                            v-model="item.product_id"
                                            :options="comboboxCatalogProducts"
                                            :disabled-ids="form.items.map((i) => i.product_id)"
                                        />

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
                                            class="h-9 w-9 shrink-0"
                                            :title="
                                                form.items.length <= 1
                                                    ? 'A venda precisa conter pelo menos 1 item.'
                                                    : 'Remover item da venda'
                                            "
                                            :class="
                                                form.items.length <= 1
                                                    ? 'cursor-not-allowed opacity-40 hover:bg-transparent hover:text-muted-foreground'
                                                    : 'text-muted-foreground hover:text-destructive'
                                            "
                                            @click="removeItemRow(index)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>

                                    <!-- Exibe a composição física caso o item selecionado seja um Kit -->
                                    <div
                                        v-if="getProductById(item.product_id)?.type === 'kit'"
                                        class="pl-1 font-mono text-[10px] text-muted-foreground"
                                    >
                                        └ Kit baixa do estoque:
                                        {{
                                            getProductById(item.product_id)
                                                ?.components?.map(
                                                    (c) => `${c.pivot.quantity * Math.max(1, Number(item.quantity || 1))}x ${c.sku} (${c.name})`,
                                                )
                                                .join(' + ')
                                        }}
                                    </div>
                                </div>
                            </template>

                            <!-- Lista Somente Leitura (Recibo quando Concluída ou Cancelada) -->
                            <template v-else-if="editingSale">
                                <div
                                    v-for="saleItem in editingSale.items"
                                    :key="saleItem.id"
                                    class="flex flex-col gap-1 rounded border bg-background px-3 py-2 text-xs"
                                >
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="min-w-0 flex-1 truncate font-medium">
                                            <span class="font-mono text-muted-foreground">{{ saleItem.quantity }}x {{ saleItem.product?.sku }}</span>
                                            — {{ saleItem.product?.name }}
                                        </div>
                                        <div class="shrink-0 font-semibold tabular-nums">
                                            {{ formatCurrency(saleItem.subtotal) }}
                                        </div>
                                    </div>
                                    <div
                                        v-if="saleItem.product?.type === 'kit' && saleItem.product.components?.length"
                                        class="font-mono text-[10px] text-muted-foreground"
                                    >
                                        └ Composição baixada:
                                        {{
                                            saleItem.product.components
                                                .map((c) => `${c.pivot.quantity * saleItem.quantity}x ${c.sku} (${c.name})`)
                                                .join(' + ')
                                        }}
                                    </div>
                                </div>
                            </template>

                            <InputError :message="form.errors.items" />
                        </div>

                        <!-- 5. Desconto (R$) e Observações -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="grid gap-1.5">
                                <Label for="discount">Desconto (R$)</Label>
                                <Input
                                    id="discount"
                                    v-model="form.discount"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    :disabled="isReadOnly"
                                    placeholder="0.00"
                                />
                                <InputError :message="form.errors.discount" />
                            </div>

                            <div class="grid gap-1.5">
                                <Label for="notes">Observação</Label>
                                <Input
                                    id="notes"
                                    v-model="form.notes"
                                    :disabled="isReadOnly"
                                    placeholder="Opcional..."
                                />
                                <InputError :message="form.errors.notes" />
                            </div>
                        </div>

                        <!-- 6. Resumo Financeiro da Venda -->
                        <div class="flex flex-col gap-1.5 rounded-lg border bg-muted/20 p-3 text-xs">
                            <div class="flex items-center justify-between text-muted-foreground">
                                <span>Subtotal dos itens ({{ totalUnitsInSale }} un.):</span>
                                <span class="tabular-nums">{{ formatCurrency(itemsSubtotal) }}</span>
                            </div>
                            <div
                                v-if="Number(form.discount) > 0"
                                class="flex items-center justify-between text-emerald-600 dark:text-emerald-400"
                            >
                                <span>Desconto concedido:</span>
                                <span class="tabular-nums">- {{ formatCurrency(form.discount) }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t pt-2 text-sm font-bold">
                                <span>Valor Total da Venda:</span>
                                <span class="tabular-nums">{{ formatCurrency(finalTotal) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rodapé fixo compacto -->
                    <SheetFooter class="border-t bg-background px-5 py-3.5">
                        <Button type="button" variant="outline" @click="isSheetOpen = false">
                            {{ isReadOnly ? 'Fechar' : 'Cancelar' }}
                        </Button>
                        <Button
                            v-if="!isReadOnly"
                            type="submit"
                            :variant="form.status === 'cancelled' ? 'destructive' : 'default'"
                            :disabled="form.processing"
                        >
                            {{
                                form.processing
                                    ? 'Processando...'
                                    : !editingSale
                                      ? 'Abrir Venda (Pendente)'
                                      : form.status === 'completed'
                                        ? 'Concluir e Baixar Estoque'
                                        : form.status === 'cancelled'
                                          ? 'Confirmar Cancelamento'
                                          : 'Salvar Alterações'
                            }}
                        </Button>
                    </SheetFooter>
                </form>
            </SheetContent>
        </Sheet>
    </AppLayout>
</template>