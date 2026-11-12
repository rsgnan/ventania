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
    AlertTriangle,
    Boxes,
    Check,
    Copy,
    ImagePlus,
    Layers,
    Package,
    Pencil,
    Plus,
    Search,
    Sparkles,
    Trash2,
    TrendingUp,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface CategoryOption {
    id: number;
    name: string;
}

interface SimpleProductOption {
    id: number;
    name: string;
    sku: string;
    price: string | number;
    stock_quantity: number;
}

interface KitComponent extends SimpleProductOption {
    pivot: {
        quantity: number;
    };
}

interface ProductRow {
    id: number;
    category_id: number;
    name: string;
    sku: string;
    type: 'simple' | 'kit';
    price: string | number;
    stock_quantity: number;
    min_stock: number;
    available_stock: number;
    is_low_stock: boolean;
    description: string | null;
    image_url: string | null;
    is_active: boolean;
    category?: CategoryOption;
    components?: KitComponent[];
}

interface Props {
    products: {
        data: ProductRow[];
        current_page: number;
        last_page: number;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    filters: {
        search?: string;
        category_id?: string | number;
        tab?: string;
        status?: string;
    };
    categories: CategoryOption[];
    simpleProducts: SimpleProductOption[];
    nextSkus: {
        simple: string;
        kit: string;
    };
    stats: {
        total: number;
        simple: number;
        kits: number;
        low_stock: number;
        physical_units: number;
        stock_value: number;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Produtos', href: '/products' },
];

// Estado dos filtros da listagem
const search = ref(props.filters.search ?? '');
const categoryFilter = ref(props.filters.category_id ? String(props.filters.category_id) : '');
const activeTab = ref(props.filters.tab ?? '');
const statusFilter = ref(props.filters.status ?? '');

let searchTimeout: ReturnType<typeof setTimeout>;
const applyFilters = () => {
    router.get(
        '/products',
        {
            search: search.value || undefined,
            category_id: categoryFilter.value || undefined,
            tab: activeTab.value || undefined,
            status: statusFilter.value || undefined,
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

watch([categoryFilter, activeTab, statusFilter], applyFilters);

// Controle do painel lateral (<Sheet>) e preview de imagem
const isSheetOpen = ref(false);
const editingProduct = ref<ProductRow | null>(null);
const imagePreview = ref<string | null>(null);
const fileInputRef = ref<HTMLInputElement | null>(null);

const form = useForm({
    _method: 'post' as 'post' | 'put',
    category_id: '' as number | string,
    name: '',
    sku: '',
    type: 'simple' as 'simple' | 'kit',
    price: '' as number | string,
    min_stock: 5 as number | string,
    description: '',
    image: null as File | null,
    is_active: true as boolean,
    components: [] as Array<{ product_id: number | string; quantity: number }>,
});

// Verifica se um produto simples já foi selecionado em outra linha da composição do Kit
const isComponentSelected = (productId: number, currentIndex: number): boolean => {
    return form.components.some(
        (item, idx) => idx !== currentIndex && Number(item.product_id) === productId,
    );
};

// Adiciona uma nova linha no Kit escolhendo automaticamente o primeiro produto simples ainda não usado
const addComponentRow = () => {
    const nextAvailable = props.simpleProducts.find(
        (p) => !form.components.some((c) => Number(c.product_id) === p.id),
    );

    if (!nextAvailable) return;

    form.components.push({
        product_id: nextAvailable.id,
        quantity: 1,
    });
};

const removeComponentRow = (index: number) => {
    form.components.splice(index, 1);
};

const selectProductType = (type: 'simple' | 'kit') => {
    if (editingProduct.value) return;

    form.type = type;
    form.sku = type === 'kit' ? props.nextSkus.kit : props.nextSkus.simple;
    form.min_stock = type === 'kit' ? 0 : 5;

    if (type === 'kit' && form.components.length === 0) {
        addComponentRow();
    }
};

const generateSku = () => {
    form.sku = form.type === 'kit' ? props.nextSkus.kit : props.nextSkus.simple;
};

const handleImageChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    form.image = file;
    imagePreview.value = file ? URL.createObjectURL(file) : editingProduct.value?.image_url ?? null;
};

// Calcula em tempo real o valor somado dos itens simples que compõem o Kit
const kitComponentsTotal = computed(() => {
    return form.components.reduce((sum, item) => {
        const found = props.simpleProducts.find((p) => p.id === Number(item.product_id));
        return sum + (found ? Number(found.price) * Number(item.quantity || 0) : 0);
    }, 0);
});

const openCreateSheet = () => {
    editingProduct.value = null;
    form.reset();
    form.clearErrors();
    form._method = 'post';
    form.category_id = props.categories[0]?.id ?? '';
    form.type = 'simple';
    form.sku = props.nextSkus.simple;
    form.min_stock = 5;
    form.description = '';
    form.image = null;
    form.is_active = true;
    form.components = [];
    imagePreview.value = null;
    isSheetOpen.value = true;
};

const openEditSheet = (product: ProductRow) => {
    editingProduct.value = product;
    form.clearErrors();
    form._method = 'put';
    form.category_id = product.category_id;
    form.name = product.name;
    form.sku = product.sku;
    form.type = product.type;
    form.price = product.price;
    form.min_stock = product.min_stock;
    form.description = product.description ?? '';
    form.image = null;
    form.is_active = product.is_active;
    form.components =
        product.components?.map((c) => ({
            product_id: c.id,
            quantity: c.pivot.quantity,
        })) ?? [];
    imagePreview.value = product.image_url;
    isSheetOpen.value = true;
};

const submitForm = () => {
    const url = editingProduct.value ? `/products/${editingProduct.value.id}` : '/products';

    form.post(url, {
        preserveScroll: true,
        onSuccess: () => {
            isSheetOpen.value = false;
            form.reset();
            imagePreview.value = null;
        },
    });
};

// Cópia rápida de SKU com feedback visual temporário
const copiedSku = ref<string | null>(null);

const copySku = (sku: string) => {
    navigator.clipboard.writeText(sku);
    copiedSku.value = sku;
    setTimeout(() => {
        if (copiedSku.value === sku) copiedSku.value = null;
    }, 1500);
};

const formatCurrency = (value: number | string) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(Number(value || 0));
};
</script>

<template>
    <Head title="Catálogo de Produtos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-w-0 flex-1 flex-col gap-4 p-4 sm:gap-6 sm:p-6">
            <!-- Cabeçalho da Página -->
            <div>
                <h1 class="text-xl font-bold tracking-tight sm:text-2xl">Produtos e Kits</h1>
                <p class="text-sm text-muted-foreground">
                    Gerencie itens físicos unitários (PRO) e composições de Kits (KIT) do catálogo Ventania.
                </p>
            </div>

            <!-- Cards Superiores de KPIs (Padrão Shadcn Dashboard) -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Valor em Estoque</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <TrendingUp class="h-3 w-3" />
                                Base PRO
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ formatCurrency(stats.stock_value) }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Capital físico imobilizado
                            <TrendingUp class="h-4 w-4 shrink-0 text-muted-foreground" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Soma apenas dos produtos físicos (PRO)
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Unidades em Estoque</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <Boxes class="h-3 w-3 text-emerald-500" />
                                Físico
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.physical_units }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Saldo centralizado disponível
                            <Boxes class="h-4 w-4 shrink-0 text-emerald-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Compartilhado entre Produtos e Kits
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">SKUs no Catálogo</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <Layers class="h-3 w-3 text-primary" />
                                {{ stats.kits }} Kits
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.total }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Produtos simples e composições
                            <Package class="h-4 w-4 shrink-0 text-primary" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Prontos para vendas físicas e encomendas
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Estoque Baixo</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <AlertTriangle class="h-3 w-3 text-amber-500" />
                                Atenção
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.low_stock }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Abaixo do estoque mínimo
                            <AlertTriangle class="h-4 w-4 shrink-0 text-amber-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Monitoramento apenas de itens físicos (PRO)
                        </div>
                    </CardFooter>
                </Card>
            </div>

            <!-- Barra de Tabs (Esquerda) + Filtros, Busca e Botão Adicionar (Direita) -->
            <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
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
                        Todos
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.total }}
                        </span>
                    </button>

                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                        :class="
                            activeTab === 'simple'
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="activeTab = 'simple'"
                    >
                        Físicos (PRO)
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.simple }}
                        </span>
                    </button>

                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                        :class="
                            activeTab === 'kit'
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="activeTab = 'kit'"
                    >
                        Kits (KIT)
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.kits }}
                        </span>
                    </button>

                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                        :class="
                            activeTab === 'low_stock'
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="activeTab = 'low_stock'"
                    >
                        Estoque Baixo
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.low_stock }}
                        </span>
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <select
                        v-model="categoryFilter"
                        class="h-9 rounded-md border border-input bg-background px-3 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring"
                    >
                        <option value="">Todas as categorias</option>
                        <option v-for="cat in categories" :key="cat.id" :value="String(cat.id)">
                            {{ cat.name }}
                        </option>
                    </select>

                    <select
                        v-model="statusFilter"
                        class="h-9 rounded-md border border-input bg-background px-3 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring"
                    >
                        <option value="">Todos os status</option>
                        <option value="active">Apenas Ativos</option>
                        <option value="inactive">Apenas Inativos</option>
                    </select>

                    <div class="relative min-w-[220px] flex-1 sm:flex-initial">
                        <Search class="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
                        <Input
                            v-model="search"
                            placeholder="Filtrar SKU ou produto..."
                            class="h-9 pl-9"
                        />
                    </div>

                    <Button class="h-9 w-full sm:w-auto" @click="openCreateSheet">
                        <Plus class="h-4 w-4" />
                        Adicionar ao Catálogo
                    </Button>
                </div>
            </div>

            <!-- Tabela de Produtos e Kits com Scroll Horizontal Isolado -->
            <div class="w-full overflow-hidden rounded-md border bg-card">
                <div class="w-full overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-16 whitespace-nowrap">ID</TableHead>
                                <TableHead class="whitespace-nowrap">Produto / Composição</TableHead>
                                <TableHead class="whitespace-nowrap">Categoria</TableHead>
                                <TableHead class="whitespace-nowrap">SKU</TableHead>
                                <TableHead class="whitespace-nowrap">Tipo</TableHead>
                                <TableHead class="whitespace-nowrap">Preço</TableHead>
                                <TableHead class="whitespace-nowrap">Disponível</TableHead>
                                <TableHead class="whitespace-nowrap">Status</TableHead>
                                <TableHead class="whitespace-nowrap text-right">Ações</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="product in products.data" :key="product.id">
                                <TableCell class="whitespace-nowrap font-mono text-xs text-muted-foreground">
                                    #{{ product.id }}
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-md border bg-muted/40"
                                        >
                                            <img
                                                v-if="product.image_url"
                                                :src="product.image_url"
                                                :alt="product.name"
                                                class="h-full w-full object-cover"
                                            />
                                            <Layers
                                                v-else-if="product.type === 'kit'"
                                                class="h-4 w-4 text-muted-foreground"
                                            />
                                            <Package v-else class="h-4 w-4 text-muted-foreground" />
                                        </div>
                                        <div>
                                            <div class="whitespace-nowrap font-medium">{{ product.name }}</div>
                                            <div
                                                v-if="product.type === 'kit' && product.components?.length"
                                                class="mt-1 flex flex-wrap gap-1.5"
                                            >
                                                <span
                                                    v-for="comp in product.components"
                                                    :key="comp.id"
                                                    class="inline-flex items-center rounded bg-muted px-2 py-0.5 font-mono text-xs text-muted-foreground"
                                                >
                                                    {{ comp.pivot.quantity }}x {{ comp.sku }} ({{ comp.name }})
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </TableCell>
                                <TableCell class="whitespace-nowrap text-sm text-muted-foreground">
                                    {{ product.category?.name }}
                                </TableCell>
                                <TableCell class="whitespace-nowrap">
                                    <button
                                        type="button"
                                        title="Clique para copiar o SKU"
                                        class="inline-flex items-center gap-1.5 rounded-full border bg-muted/60 px-2.5 py-0.5 font-mono text-xs text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                                        @click="copySku(product.sku)"
                                    >
                                        <span>{{ product.sku }}</span>
                                        <Check
                                            v-if="copiedSku === product.sku"
                                            class="h-3 w-3 text-emerald-500"
                                        />
                                        <Copy v-else class="h-3 w-3 opacity-60" />
                                    </button>
                                </TableCell>
                                <TableCell class="whitespace-nowrap">
                                    <Badge :variant="product.type === 'kit' ? 'default' : 'secondary'">
                                        {{ product.type === 'kit' ? 'Kit / Par' : 'Físico' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="whitespace-nowrap font-medium tabular-nums">
                                    {{ formatCurrency(product.price) }}
                                </TableCell>
                                <TableCell class="whitespace-nowrap">
                                    <div v-if="product.type === 'kit'" class="leading-tight">
                                        <div class="font-semibold tabular-nums">{{ product.available_stock }}</div>
                                        <div class="text-[10px] text-muted-foreground">virtual</div>
                                    </div>
                                    <Badge
                                        v-else
                                        :variant="product.is_low_stock ? 'secondary' : 'outline'"
                                        class="flex w-fit items-center gap-1 tabular-nums"
                                    >
                                        <AlertTriangle
                                            v-if="product.is_low_stock"
                                            class="h-3 w-3 text-amber-500"
                                        />
                                        {{ product.available_stock }} un. (mín: {{ product.min_stock }})
                                    </Badge>
                                </TableCell>
                                <TableCell class="whitespace-nowrap">
                                    <Badge
                                        :variant="product.is_active ? 'outline' : 'destructive'"
                                        class="inline-flex items-center gap-1.5"
                                    >
                                        <span
                                            class="h-1.5 w-1.5 rounded-full"
                                            :class="product.is_active ? 'bg-emerald-500' : 'bg-current'"
                                        />
                                        {{ product.is_active ? 'Ativo' : 'Inativo' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="whitespace-nowrap text-right">
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="h-8 w-8 text-muted-foreground hover:text-foreground"
                                        @click="openEditSheet(product)"
                                    >
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="products.data.length === 0">
                                <TableCell colspan="9" class="h-24 text-center text-muted-foreground">
                                    Nenhum produto encontrado no catálogo.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- Rodapé de Paginação -->
                <div class="flex flex-col items-center justify-between gap-2 border-t px-4 py-3 text-sm text-muted-foreground sm:flex-row">
                    <div>
                        Mostrando página {{ products.current_page }} de {{ products.last_page }} ({{ products.total }} itens)
                    </div>
                    <div class="flex gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!products.prev_page_url"
                            @click="products.prev_page_url && router.get(products.prev_page_url, {}, { preserveState: true, preserveScroll: true })"
                        >
                            Anterior
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!products.next_page_url"
                            @click="products.next_page_url && router.get(products.next_page_url, {}, { preserveState: true, preserveScroll: true })"
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
                        {{ editingProduct ? `Editar item #${editingProduct.id}` : 'Adicionar ao Catálogo' }}
                    </SheetTitle>
                    <SheetDescription class="text-sm">
                        Cadastre um produto físico unitário (PRO) ou monte um Kit/Par composto (KIT).
                    </SheetDescription>
                </SheetHeader>

                <form class="flex min-h-0 flex-1 flex-col" autocomplete="off" @submit.prevent="submitForm">
                    <!-- Corpo rolável compacto (gap-3 e px-5 py-4) -->
                    <div class="flex flex-1 flex-col gap-3 overflow-y-auto px-5 py-4">
                        <!-- 1. Seletor de Tipo (Imutável na edição) -->
                        <div class="grid gap-1.5">
                            <Label>Tipo de cadastro</Label>
                            <div class="grid grid-cols-2 gap-2">
                                <Button
                                    type="button"
                                    :variant="form.type === 'simple' ? 'default' : 'outline'"
                                    class="w-full"
                                    :disabled="!!editingProduct"
                                    @click="selectProductType('simple')"
                                >
                                    <Package class="h-4 w-4" />
                                    Produto Físico (PRO)
                                </Button>
                                <Button
                                    type="button"
                                    :variant="form.type === 'kit' ? 'default' : 'outline'"
                                    class="w-full"
                                    :disabled="!!editingProduct"
                                    @click="selectProductType('kit')"
                                >
                                    <Layers class="h-4 w-4" />
                                    Kit / Par (KIT)
                                </Button>
                            </div>
                            <InputError :message="form.errors.type" />
                        </div>

                        <!-- 2. Categoria -->
                        <div class="grid gap-1.5">
                            <Label for="category_id">Categoria</Label>
                            <select
                                id="category_id"
                                v-model="form.category_id"
                                required
                                class="h-9 rounded-md border border-input bg-background px-3 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring"
                            >
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.category_id" />
                        </div>

                        <!-- 3. Nome -->
                        <div class="grid gap-1.5">
                            <Label for="name">
                                {{ form.type === 'kit' ? 'Nome do Kit / Par' : 'Nome do produto' }}
                            </Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                required
                                :placeholder="
                                    form.type === 'kit'
                                        ? 'ex: Kit Limpeza Energética (Incenso + Sabonete)'
                                        : 'ex: Incenso Natural de Arruda (Unidade)'
                                "
                            />
                            <InputError :message="form.errors.name" />
                        </div>

                        <!-- 4. SKU Único com Botão Gerar (h-9 alinhado) -->
                        <div class="grid gap-1.5">
                            <Label for="sku">SKU Único</Label>
                            <div class="flex items-center gap-2">
                                <Input
                                    id="sku"
                                    v-model="form.sku"
                                    class="font-mono"
                                    placeholder="PRO-00001"
                                />
                                <Button type="button" variant="outline" class="shrink-0" @click="generateSku">
                                    <Sparkles class="h-4 w-4" />
                                    Gerar
                                </Button>
                            </div>
                            <InputError :message="form.errors.sku" />
                        </div>

                        <!-- 5. Quadro de Composição do Kit -->
                        <div
                            v-if="form.type === 'kit'"
                            class="flex flex-col gap-2.5 rounded-lg border bg-muted/30 p-3"
                        >
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <div class="text-sm font-medium">Composição do Kit / Par</div>
                                    <div class="text-xs text-muted-foreground">
                                        Produtos físicos e quantidade por venda.
                                    </div>
                                </div>
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    :disabled="form.components.length >= simpleProducts.length"
                                    @click="addComponentRow"
                                >
                                    <Plus class="h-3.5 w-3.5" />
                                    Adicionar item
                                </Button>
                            </div>

                            <div
                                v-for="(comp, index) in form.components"
                                :key="index"
                                class="flex items-center gap-2"
                            >
                                <select
                                    v-model="comp.product_id"
                                    required
                                    class="h-9 min-w-0 flex-1 truncate rounded-md border border-input bg-background px-2.5 text-xs ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring"
                                >
                                    <option
                                        v-for="simple in simpleProducts"
                                        :key="simple.id"
                                        :value="simple.id"
                                        :disabled="isComponentSelected(simple.id, index)"
                                    >
                                        {{ simple.sku }} — {{ simple.name }} ({{ formatCurrency(simple.price) }})
                                    </option>
                                </select>

                                <Input
                                    v-model.number="comp.quantity"
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
                                    :disabled="form.components.length <= 1"
                                    @click="removeComponentRow(index)"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>

                            <InputError :message="form.errors.components" />

                            <div class="flex items-center justify-between border-t pt-2 text-xs">
                                <span class="text-muted-foreground">Valor total calculado do Kit:</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold tabular-nums">
                                        {{ formatCurrency(kitComponentsTotal) }}
                                    </span>
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="sm"
                                        class="h-6 px-2 text-xs"
                                        @click="form.price = kitComponentsTotal.toFixed(2)"
                                    >
                                        Aplicar no preço
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Preço de Venda e Estoque Mínimo -->
                        <div :class="form.type === 'simple' ? 'grid grid-cols-2 gap-3' : 'grid gap-1.5'">
                            <div class="grid gap-1.5">
                                <Label for="price">Preço de venda (R$)</Label>
                                <Input
                                    id="price"
                                    v-model="form.price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    required
                                    placeholder="0.00"
                                />
                                <InputError :message="form.errors.price" />
                            </div>

                            <div v-if="form.type === 'simple'" class="grid gap-1.5">
                                <Label for="min_stock">Estoque mínimo para alerta</Label>
                                <Input
                                    id="min_stock"
                                    v-model.number="form.min_stock"
                                    type="number"
                                    min="0"
                                    required
                                    placeholder="5"
                                />
                                <InputError :message="form.errors.min_stock" />
                            </div>
                        </div>

                        <!-- 7. Descrição (Opcional) -->
                        <div class="grid gap-1.5">
                            <Label for="description">Descrição (opcional)</Label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="2"
                                placeholder="Descreva o produto, aplicação ou detalhes do fornecedor."
                                class="w-full rounded-md border border-input bg-background px-3 py-1.5 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                            />
                            <InputError :message="form.errors.description" />
                        </div>

                        <!-- 8. Imagem (Opcional) -->
                        <div class="grid gap-1.5">
                            <Label>
                                {{ editingProduct ? 'Substituir imagem (opcional)' : 'Imagem (opcional)' }}
                            </Label>
                            <input
                                ref="fileInputRef"
                                type="file"
                                accept="image/jpeg,image/png,image/webp"
                                class="hidden"
                                @change="handleImageChange"
                            />
                            <div class="flex items-center gap-3">
                                <div
                                    v-if="imagePreview"
                                    class="h-9 w-9 shrink-0 overflow-hidden rounded-md border bg-muted"
                                >
                                    <img :src="imagePreview" alt="Preview" class="h-full w-full object-cover" />
                                </div>
                                <Button
                                    type="button"
                                    variant="outline"
                                    class="w-full"
                                    @click="fileInputRef?.click()"
                                >
                                    <ImagePlus class="h-4 w-4" />
                                    {{ imagePreview ? 'Trocar imagem' : 'Adicionar imagem' }}
                                </Button>
                            </div>
                            <span class="text-[11px] text-muted-foreground">
                                Formatos JPG, PNG ou WebP de até 2 MB.
                            </span>
                            <InputError :message="form.errors.image" />
                        </div>

                        <!-- 9. Status Ativo/Inativo -->
                        <div class="flex items-center gap-2 pt-0.5">
                            <input
                                id="is_active"
                                v-model="form.is_active"
                                type="checkbox"
                                class="h-4 w-4 rounded border-input text-primary focus:ring-primary"
                            />
                            <Label for="is_active" class="cursor-pointer text-xs">
                                Item ativo no catálogo (visível para vendas e futuro e-commerce)
                            </Label>
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
                                    ? 'Salvando...'
                                    : editingProduct
                                      ? 'Salvar alterações'
                                      : form.type === 'kit'
                                        ? 'Salvar Kit / Par'
                                        : 'Salvar produto'
                            }}
                        </Button>
                    </SheetFooter>
                </form>
            </SheetContent>
        </Sheet>
    </AppLayout>
</template>