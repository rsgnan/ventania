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
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Produtos', href: '/products' },
];

// Estado dos filtros da listagem (activeTab garante exclusividade entre Todos, Físicos, Kits e Estoque Baixo)
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
    is_active: true,
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
    form.type = type;

    if (!editingProduct.value) {
        form.sku = type === 'kit' ? props.nextSkus.kit : props.nextSkus.simple;
        form.min_stock = type === 'kit' ? 0 : 5;
    }

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
        <div class="flex flex-1 flex-col gap-6 p-6">
            <!-- Cabeçalho da Página -->
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Produtos e Kits</h1>
                <p class="text-sm text-muted-foreground">
                    Gerencie itens físicos unitários (PRO) e composições de Kits (KIT) do catálogo Ventania.
                </p>
            </div>

            <!-- Cards Superiores de KPIs (Padrão Shadcn Dashboard) -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between">
                            <CardDescription>Valor em Estoque</CardDescription>
                            <Badge variant="outline" class="flex items-center gap-1 rounded-lg text-xs font-medium">
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
                            <TrendingUp class="h-4 w-4 text-muted-foreground" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Soma apenas dos produtos físicos (PRO)
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between">
                            <CardDescription>Unidades em Estoque</CardDescription>
                            <Badge variant="outline" class="flex items-center gap-1 rounded-lg text-xs font-medium">
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
                            <Boxes class="h-4 w-4 text-emerald-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Compartilhado entre Produtos e Kits
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between">
                            <CardDescription>SKUs no Catálogo</CardDescription>
                            <Badge variant="outline" class="flex items-center gap-1 rounded-lg text-xs font-medium">
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
                            <Package class="h-4 w-4 text-primary" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Prontos para vendas físicas e encomendas
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between">
                            <CardDescription>Estoque Baixo</CardDescription>
                            <Badge variant="outline" class="flex items-center gap-1 rounded-lg text-xs font-medium">
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
                            <AlertTriangle class="h-4 w-4 text-amber-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Monitoramento apenas de itens físicos (PRO)
                        </div>
                    </CardFooter>
                </Card>
            </div>

            <!-- Barra de Tabs (Esquerda) + Filtros, Busca e Botão Adicionar (Direita) -->
            <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
                <!-- Tabs no estilo Segmented Pill com Contadores -->
                <div class="inline-flex flex-wrap items-center gap-1 rounded-lg bg-muted/70 p-1">
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

                <!-- Selects + Busca + Botão de Adicionar -->
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

                    <Button class="h-9" @click="openCreateSheet">
                        <Plus class="mr-1.5 h-4 w-4" />
                        Adicionar ao Catálogo
                    </Button>
                </div>
            </div>

            <!-- Tabela de Produtos e Kits -->
            <div class="rounded-md border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-16">ID</TableHead>
                            <TableHead>Produto / Composição</TableHead>
                            <TableHead>Categoria</TableHead>
                            <TableHead>SKU</TableHead>
                            <TableHead>Tipo</TableHead>
                            <TableHead>Preço</TableHead>
                            <TableHead>Disponível</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Ações</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="product in products.data" :key="product.id">
                            <TableCell class="font-mono text-xs text-muted-foreground">
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
                                        <div class="font-medium">{{ product.name }}</div>
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
                            <TableCell class="text-sm text-muted-foreground">
                                {{ product.category?.name }}
                            </TableCell>
                            <TableCell>
                                <span class="rounded border bg-muted/50 px-2 py-0.5 font-mono text-xs">
                                    {{ product.sku }}
                                </span>
                            </TableCell>
                            <TableCell>
                                <Badge :variant="product.type === 'kit' ? 'default' : 'secondary'">
                                    {{ product.type === 'kit' ? 'Kit / Par' : 'Físico' }}
                                </Badge>
                            </TableCell>
                            <TableCell class="font-medium tabular-nums">
                                {{ formatCurrency(product.price) }}
                            </TableCell>
                            <TableCell>
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
                            <TableCell>
                                <Badge :variant="product.is_active ? 'outline' : 'destructive'">
                                    {{ product.is_active ? 'Ativo' : 'Inativo' }}
                                </Badge>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button variant="ghost" size="sm" @click="openEditSheet(product)">
                                    <Pencil class="mr-1 h-4 w-4" />
                                    Editar
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

                <!-- Rodapé de Paginação -->
                <div class="flex items-center justify-between border-t px-4 py-3 text-sm text-muted-foreground">
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

        <!-- Painel Lateral (<Sheet>) -->
        <Sheet v-model:open="isSheetOpen">
            <SheetContent class="overflow-y-auto sm:max-w-lg">
                <SheetHeader>
                    <SheetTitle>
                        {{ editingProduct ? 'Editar Item do Catálogo' : 'Adicionar ao Catálogo' }}
                    </SheetTitle>
                    <SheetDescription>
                        Cadastre um produto físico unitário (PRO) ou monte um Kit composto (KIT).
                    </SheetDescription>
                </SheetHeader>

                <form class="mt-6 flex flex-col gap-4" @submit.prevent="submitForm">
                    <!-- Seletor de Tipo (Produto Físico vs Kit) -->
                    <div class="grid gap-2">
                        <Label>Tipo de cadastro</Label>
                        <div class="grid grid-cols-2 gap-2">
                            <Button
                                type="button"
                                :variant="form.type === 'simple' ? 'default' : 'outline'"
                                class="w-full"
                                @click="selectProductType('simple')"
                            >
                                <Package class="mr-2 h-4 w-4" />
                                Produto Físico (PRO)
                            </Button>
                            <Button
                                type="button"
                                :variant="form.type === 'kit' ? 'default' : 'outline'"
                                class="w-full"
                                @click="selectProductType('kit')"
                            >
                                <Layers class="mr-2 h-4 w-4" />
                                Kit / Par (KIT)
                            </Button>
                        </div>
                        <InputError :message="form.errors.type" />
                    </div>

                    <!-- Categoria -->
                    <div class="grid gap-2">
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

                    <!-- Nome -->
                    <div class="grid gap-2">
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

                    <!-- SKU Único com Botão Gerar -->
                    <div class="grid gap-2">
                        <Label for="sku">SKU Único</Label>
                        <div class="flex gap-2">
                            <Input
                                id="sku"
                                v-model="form.sku"
                                class="font-mono"
                                placeholder="PRO-00001"
                            />
                            <Button type="button" variant="outline" @click="generateSku">
                                <Sparkles class="mr-1.5 h-4 w-4" />
                                Gerar
                            </Button>
                        </div>
                        <InputError :message="form.errors.sku" />
                    </div>

                    <!-- Quadro de Composição do Kit (Visível apenas quando Tipo === 'kit') -->
                    <div
                        v-if="form.type === 'kit'"
                        class="flex flex-col gap-3 rounded-lg border bg-muted/30 p-3.5"
                    >
                        <div class="flex items-center justify-between">
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
                                <Plus class="mr-1 h-3.5 w-3.5" />
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
                                class="h-9 w-16 shrink-0 text-center tabular-nums"
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

                        <div class="flex items-center justify-between border-t pt-2.5 text-xs">
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

                    <!-- Preço de Venda e Estoque Mínimo (Estoque Mínimo apenas para Produto Físico) -->
                    <div :class="form.type === 'simple' ? 'grid grid-cols-2 gap-3' : 'grid gap-2'">
                        <div class="grid gap-2">
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

                        <div v-if="form.type === 'simple'" class="grid gap-2">
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

                    <!-- Descrição (Opcional) -->
                    <div class="grid gap-2">
                        <Label for="description">Descrição (opcional)</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            placeholder="Descreva o produto, aplicação ou detalhes do fornecedor."
                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                        />
                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Imagem (Opcional) -->
                    <div class="grid gap-2">
                        <Label>Imagem (opcional)</Label>
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
                                class="h-12 w-12 shrink-0 overflow-hidden rounded-md border bg-muted"
                            >
                                <img :src="imagePreview" alt="Preview" class="h-full w-full object-cover" />
                            </div>
                            <Button
                                type="button"
                                variant="outline"
                                class="w-full"
                                @click="fileInputRef?.click()"
                            >
                                <ImagePlus class="mr-2 h-4 w-4" />
                                {{ imagePreview ? 'Trocar imagem' : 'Adicionar imagem' }}
                            </Button>
                        </div>
                        <span class="text-xs text-muted-foreground">
                            Formatos JPG, PNG ou WebP de até 2 MB.
                        </span>
                        <InputError :message="form.errors.image" />
                    </div>

                    <!-- Status Ativo/Inativo -->
                    <div class="flex items-center gap-2 pt-1">
                        <input
                            id="is_active"
                            v-model="form.is_active"
                            type="checkbox"
                            class="h-4 w-4 rounded border-input text-primary focus:ring-primary"
                        />
                        <Label for="is_active" class="cursor-pointer">
                            Item ativo no catálogo (visível para vendas e futuro e-commerce)
                        </Label>
                    </div>

                    <SheetFooter class="mt-4">
                        <Button type="button" variant="outline" @click="isSheetOpen = false">
                            Cancelar
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{
                                form.processing
                                    ? 'Salvando...'
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