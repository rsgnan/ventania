<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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
import { Head, Link } from '@inertiajs/vue3';
import {
    Activity,
    AlertTriangle,
    ArrowUpRight,
    Boxes,
    Clock,
    Layers,
    Package,
    ShoppingBag,
    TrendingUp,
} from 'lucide-vue-next';

interface TopProductRow {
    id: number;
    name: string;
    sku: string;
    type: 'simple' | 'kit';
    price: string | number;
    total_sold: number | string;
    total_revenue: number | string;
}

interface RecentSaleRow {
    id: number;
    customer_name: string;
    status: 'pending' | 'completed' | 'cancelled';
    total_amount: string | number;
    items_count: number;
    created_at: string;
    user?: {
        id: number;
        name: string;
    };
}

interface LowStockProductRow {
    id: number;
    name: string;
    sku: string;
    stock_quantity: number;
    min_stock: number;
    category?: {
        id: number;
        name: string;
    };
}

interface AuditLogRow {
    id: number;
    user_id: number | null;
    action: string;
    auditable_type: string | null;
    auditable_id: number | null;
    ip_address: string | null;
    created_at: string;
    user?: {
        id: number;
        name: string;
        role: string;
    };
}

interface Props {
    stats: {
        revenue: number;
        completed_sales: number;
        average_ticket: number;
        pending_sales: number;
        pending_amount: number;
        stock_value: number;
        physical_units: number;
        low_stock_count: number;
        total_products: number;
    };
    topProducts: TopProductRow[];
    recentSales: RecentSaleRow[];
    lowStockProducts: LowStockProductRow[];
    recentLogs: AuditLogRow[];
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
];

const formatCurrency = (value: number | string) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(Number(value || 0));
};

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(dateString));
};

const formatAuditAction = (action: string) => {
    return (
        {
            login: 'Login no sistema',
            logout: 'Logout do sistema',
            created: 'Registro criado',
            updated: 'Registro atualizado',
        }[action] ?? action
    );
};

const formatAuditableModel = (type: string | null, id: number | null) => {
    if (!type) return 'Sessão de Autenticação';
    const shortName = type.split('\\').pop() ?? type;
    const label =
        {
            User: 'Usuário',
            Product: 'Produto',
            StockMovement: 'Movimentação',
            Sale: 'Venda',
        }[shortName] ?? shortName;

    return id ? `${label} #${id}` : label;
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-w-0 flex-1 flex-col gap-4 p-4 sm:gap-6 sm:p-6">
            <!-- Cabeçalho da Página -->
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-bold tracking-tight sm:text-2xl">Visão Geral</h1>
                    <p class="text-sm text-muted-foreground">
                        Acompanhe os indicadores comerciais, giro de produtos, estoque e auditoria do ERP Ventania.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="sm" as-child>
                        <Link href="/stock-movements">
                            <Boxes class="h-4 w-4" />
                            Movimentar Estoque
                        </Link>
                    </Button>
                    <Button size="sm" as-child>
                        <Link href="/sales">
                            <ShoppingBag class="h-4 w-4" />
                            Ir para Vendas
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- 1. Cards Executivos Superiores (Padrão Shadcn Dashboard) -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Receita Concluída</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <TrendingUp class="h-3 w-3 text-emerald-500" />
                                Ticket {{ formatCurrency(stats.average_ticket) }}
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ formatCurrency(stats.revenue) }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            {{ stats.completed_sales }} {{ stats.completed_sales === 1 ? 'venda concluída' : 'vendas concluídas' }}
                            <TrendingUp class="h-4 w-4 shrink-0 text-emerald-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Faturamento líquido acumulado no caixa
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Vendas Pendentes</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <Clock class="h-3 w-3 text-amber-500" />
                                {{ stats.pending_sales }} {{ stats.pending_sales === 1 ? 'pedido' : 'pedidos' }}
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ formatCurrency(stats.pending_amount) }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Pedidos em aberto no balcão
                            <Clock class="h-4 w-4 shrink-0 text-amber-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Aguardando confirmação e baixa de estoque
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Valor em Estoque</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <Boxes class="h-3 w-3 text-primary" />
                                {{ stats.physical_units }} un.
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ formatCurrency(stats.stock_value) }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            {{ stats.total_products }} SKUs cadastrados
                            <Package class="h-4 w-4 shrink-0 text-primary" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Capital físico disponível em produtos (PRO)
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Alerta de Reposição</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <AlertTriangle class="h-3 w-3 text-amber-500" />
                                Atenção
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.low_stock_count }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Itens abaixo do mínimo
                            <AlertTriangle class="h-4 w-4 shrink-0 text-amber-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Produtos físicos precisando de compra
                        </div>
                    </CardFooter>
                </Card>
            </div>

            <!-- 2. Grid Comercial: Últimas Vendas (4 colunas) + Top Produtos Mais Vendidos (3 colunas) -->
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-7">
                <!-- Últimas Vendas -->
                <Card class="flex flex-col lg:col-span-4">
                    <CardHeader class="flex flex-row items-center justify-between pb-3">
                        <div>
                            <CardTitle class="text-base">Últimas Vendas</CardTitle>
                            <CardDescription class="text-xs">
                                Pedidos mais recentes registrados no sistema.
                            </CardDescription>
                        </div>
                        <Button variant="ghost" size="sm" as-child>
                            <Link href="/sales">
                                Ver todas
                                <ArrowUpRight class="h-4 w-4" />
                            </Link>
                        </Button>
                    </CardHeader>
                    <CardContent class="p-0">
                        <div class="w-full overflow-x-auto border-t">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead class="w-14 whitespace-nowrap">ID</TableHead>
                                        <TableHead class="whitespace-nowrap">Cliente</TableHead>
                                        <TableHead class="whitespace-nowrap">Itens</TableHead>
                                        <TableHead class="whitespace-nowrap">Status</TableHead>
                                        <TableHead class="whitespace-nowrap text-right">Total</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="sale in recentSales" :key="sale.id">
                                        <TableCell class="whitespace-nowrap font-mono text-xs text-muted-foreground">
                                            #{{ sale.id }}
                                        </TableCell>
                                        <TableCell class="whitespace-nowrap">
                                            <div class="font-medium">{{ sale.customer_name }}</div>
                                            <div class="text-xs text-muted-foreground">
                                                por {{ sale.user?.name }}
                                            </div>
                                        </TableCell>
                                        <TableCell class="whitespace-nowrap text-xs text-muted-foreground tabular-nums">
                                            {{ sale.items_count }} {{ sale.items_count === 1 ? 'item' : 'itens' }}
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
                                        <TableCell class="whitespace-nowrap text-right font-semibold tabular-nums">
                                            {{ formatCurrency(sale.total_amount) }}
                                        </TableCell>
                                    </TableRow>

                                    <TableRow v-if="recentSales.length === 0">
                                        <TableCell colspan="5" class="h-24 text-center text-muted-foreground">
                                            Nenhuma venda registrada até o momento.
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>

                <!-- Top Produtos (Mais Vendidos) -->
                <Card class="flex flex-col lg:col-span-3">
                    <CardHeader class="flex flex-row items-center justify-between pb-3">
                        <div>
                            <CardTitle class="text-base">Top Produtos</CardTitle>
                            <CardDescription class="text-xs">
                                Itens e Kits mais vendidos nas vendas concluídas.
                            </CardDescription>
                        </div>
                        <Button variant="ghost" size="sm" as-child>
                            <Link href="/products">
                                Catálogo
                                <ArrowUpRight class="h-4 w-4" />
                            </Link>
                        </Button>
                    </CardHeader>
                    <CardContent class="p-0">
                        <div class="w-full overflow-x-auto border-t">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead class="whitespace-nowrap">Produto / Kit</TableHead>
                                        <TableHead class="whitespace-nowrap text-center">Vendidos</TableHead>
                                        <TableHead class="whitespace-nowrap text-right">Receita</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="(prod, index) in topProducts" :key="prod.id">
                                        <TableCell>
                                            <div class="flex items-center gap-2.5">
                                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-muted font-mono text-xs font-semibold">
                                                    {{ index + 1 }}º
                                                </span>
                                                <div class="min-w-0">
                                                    <div class="truncate font-medium">{{ prod.name }}</div>
                                                    <div class="flex items-center gap-1.5 text-xs text-muted-foreground">
                                                        <span class="font-mono">{{ prod.sku }}</span>
                                                        <span>•</span>
                                                        <span class="inline-flex items-center gap-0.5">
                                                            <Layers v-if="prod.type === 'kit'" class="h-3 w-3" />
                                                            {{ prod.type === 'kit' ? 'Kit' : 'Físico' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </TableCell>
                                        <TableCell class="whitespace-nowrap text-center">
                                            <Badge variant="secondary" class="tabular-nums">
                                                {{ prod.total_sold }} un.
                                            </Badge>
                                        </TableCell>
                                        <TableCell class="whitespace-nowrap text-right font-semibold tabular-nums">
                                            {{ formatCurrency(prod.total_revenue) }}
                                        </TableCell>
                                    </TableRow>

                                    <TableRow v-if="topProducts.length === 0">
                                        <TableCell colspan="3" class="h-24 text-center text-muted-foreground">
                                            Conclua vendas para visualizar o ranking de produtos.
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- 3. Grid Operacional e Auditoria: Estoque Baixo (3 colunas) + Trilha de Auditoria (4 colunas) -->
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-7">
                <!-- Alertas de Estoque Baixo -->
                <Card class="flex flex-col lg:col-span-3">
                    <CardHeader class="flex flex-row items-center justify-between pb-3">
                        <div>
                            <CardTitle class="text-base">Estoque Baixo (PRO)</CardTitle>
                            <CardDescription class="text-xs">
                                Produtos físicos com saldo abaixo ou igual ao mínimo.
                            </CardDescription>
                        </div>
                        <Button variant="ghost" size="sm" as-child>
                            <Link href="/products?tab=low_stock">
                                Ver todos
                                <ArrowUpRight class="h-4 w-4" />
                            </Link>
                        </Button>
                    </CardHeader>
                    <CardContent class="p-0">
                        <div class="w-full overflow-x-auto border-t">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead class="whitespace-nowrap">Produto</TableHead>
                                        <TableHead class="whitespace-nowrap text-right">Saldo / Mín.</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="item in lowStockProducts" :key="item.id">
                                        <TableCell>
                                            <div class="font-medium">{{ item.name }}</div>
                                            <div class="font-mono text-xs text-muted-foreground">
                                                {{ item.sku }} • {{ item.category?.name }}
                                            </div>
                                        </TableCell>
                                        <TableCell class="whitespace-nowrap text-right">
                                            <Badge variant="secondary" class="inline-flex items-center gap-1 tabular-nums">
                                                <AlertTriangle class="h-3 w-3 text-amber-500" />
                                                {{ item.stock_quantity }} / {{ item.min_stock }} un.
                                            </Badge>
                                        </TableCell>
                                    </TableRow>

                                    <TableRow v-if="lowStockProducts.length === 0">
                                        <TableCell colspan="2" class="h-24 text-center text-muted-foreground">
                                            Todos os produtos físicos estão com estoque saudável.
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>

                <!-- Trilha de Auditoria Recente (Audit Log) -->
                <Card class="flex flex-col lg:col-span-4">
                    <CardHeader class="pb-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle class="text-base">Trilha de Auditoria Recente</CardTitle>
                                <CardDescription class="text-xs">
                                    Rastreio imutável das últimas operações realizadas no sistema.
                                </CardDescription>
                            </div>
                            <Activity class="h-4 w-4 text-muted-foreground" />
                        </div>
                    </CardHeader>
                    <CardContent class="p-0">
                        <div class="w-full overflow-x-auto border-t">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead class="whitespace-nowrap">Evento</TableHead>
                                        <TableHead class="whitespace-nowrap">Alvo</TableHead>
                                        <TableHead class="whitespace-nowrap">Operador</TableHead>
                                        <TableHead class="whitespace-nowrap text-right">Data / Hora</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="log in recentLogs" :key="log.id">
                                        <TableCell class="whitespace-nowrap">
                                            <Badge variant="outline" class="font-normal">
                                                {{ formatAuditAction(log.action) }}
                                            </Badge>
                                        </TableCell>
                                        <TableCell class="whitespace-nowrap font-mono text-xs text-muted-foreground">
                                            {{ formatAuditableModel(log.auditable_type, log.auditable_id) }}
                                        </TableCell>
                                        <TableCell class="whitespace-nowrap text-sm">
                                            {{ log.user?.name ?? 'Sistema / Seeder' }}
                                        </TableCell>
                                        <TableCell class="whitespace-nowrap text-right text-xs text-muted-foreground tabular-nums">
                                            {{ formatDate(log.created_at) }}
                                        </TableCell>
                                    </TableRow>

                                    <TableRow v-if="recentLogs.length === 0">
                                        <TableCell colspan="4" class="h-24 text-center text-muted-foreground">
                                            Nenhum evento de auditoria registrado.
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>