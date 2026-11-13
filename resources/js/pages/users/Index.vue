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
import { type BreadcrumbItem, type SharedData, type User } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import {
    Pencil,
    Plus,
    Search,
    ShieldCheck,
    TrendingDown,
    TrendingUp,
    User as UserIcon,
    UserCheck,
    Users,
    UserX,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface UserRow extends User {
    latest_login?: {
        id: number;
        created_at: string;
    } | null;
}

interface Props {
    users: {
        data: UserRow[];
        current_page: number;
        last_page: number;
        from: number | null;
        to: number | null;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    filters: {
        search?: string;
        role?: string;
        status?: string;
    };
    roles: Array<{ value: string; label: string }>;
    stats: {
        total: number;
        active: number;
        inactive: number;
        admins: number;
    };
}

const props = defineProps<Props>();
const page = usePage<SharedData>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Usuários', href: '/users' },
];

// Estado dos filtros (Busca + Aba Pill de Status: Todos, Ativos, Desativados)
const search = ref(props.filters.search ?? '');
const statusTab = ref(props.filters.status ?? '');

let searchTimeout: ReturnType<typeof setTimeout>;
const applyFilters = () => {
    router.get(
        '/users',
        {
            search: search.value || undefined,
            status: statusTab.value || undefined,
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

watch(statusTab, applyFilters);

// Controle do painel lateral (<Sheet>) de criação e edição
const isSheetOpen = ref(false);
const editingUser = ref<UserRow | null>(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'user' as 'admin' | 'user',
    is_active: true as boolean,
});

const isEditingSelf = computed(() => editingUser.value?.id === page.props.auth.user.id);

const openCreateSheet = () => {
    editingUser.value = null;
    form.reset();
    form.clearErrors();
    isSheetOpen.value = true;
};

const openEditSheet = (user: UserRow) => {
    editingUser.value = user;
    form.clearErrors();
    form.name = user.name;
    form.email = user.email;
    form.password = '';
    form.role = user.role;
    form.is_active = user.is_active;
    isSheetOpen.value = true;
};

const submitForm = () => {
    if (editingUser.value) {
        form.put(`/users/${editingUser.value.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                isSheetOpen.value = false;
                form.reset();
            },
        });
        return;
    }

    form.post('/users', {
        preserveScroll: true,
        onSuccess: () => {
            isSheetOpen.value = false;
            form.reset();
        },
    });
};

const formatDate = (dateString?: string) => {
    if (!dateString) return 'Nunca acessou';
    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(dateString));
};

const getPercentage = (value: number) => {
    if (!props.stats.total) return '0%';
    return `${((value / props.stats.total) * 100).toFixed(1)}%`;
};
</script>

<template>
    <Head title="Gestão de Usuários" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-w-0 flex-1 flex-col gap-4 p-4 sm:gap-6 sm:p-6">
            <!-- Cabeçalho da Página -->
            <div>
                <h1 class="text-xl font-bold tracking-tight sm:text-2xl">Usuários</h1>
                <p class="text-sm text-muted-foreground">
                    Gerencie os operadores e administradores do ERP Ventania.
                </p>
            </div>

            <!-- Cards Superiores de KPIs -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Total de Usuários</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <Users class="h-3 w-3" />
                                100%
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.total }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Base histórica preservada
                            <TrendingUp class="h-4 w-4 shrink-0 text-muted-foreground" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Todos os operadores cadastrados no ERP
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Usuários Ativos</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <TrendingUp class="h-3 w-3 text-emerald-500" />
                                {{ getPercentage(stats.active) }}
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.active }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Operadores habilitados
                            <UserCheck class="h-4 w-4 shrink-0 text-emerald-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Contas com permissão de login imediato
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Usuários Inativos</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <TrendingDown class="h-3 w-3 text-destructive" />
                                {{ getPercentage(stats.inactive) }}
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.inactive }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Acessos revogados
                            <UserX class="h-4 w-4 shrink-0 text-destructive" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Mantidos intactos para trilha de auditoria
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between gap-2">
                            <CardDescription class="truncate">Administradores</CardDescription>
                            <Badge variant="outline" class="flex shrink-0 items-center gap-1 rounded-lg text-xs font-medium">
                                <ShieldCheck class="h-3 w-3 text-primary" />
                                {{ getPercentage(stats.admins) }}
                            </Badge>
                        </div>
                        <CardTitle class="text-3xl font-semibold tabular-nums">
                            {{ stats.admins }}
                        </CardTitle>
                    </CardHeader>
                    <CardFooter class="flex-col items-start gap-1 text-sm">
                        <div class="flex items-center gap-1.5 font-medium">
                            Gestão e governança
                            <ShieldCheck class="h-4 w-4 shrink-0 text-primary" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Acesso total aos módulos e configurações
                        </div>
                    </CardFooter>
                </Card>
            </div>

            <!-- Barra de Ferramentas: Pills (Esquerda) + Busca e Botão Novo Usuário (Direita) -->
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="inline-flex w-fit flex-wrap items-center gap-1 rounded-lg bg-muted/70 p-1">
                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                        :class="
                            statusTab === ''
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="statusTab = ''"
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
                            statusTab === 'active'
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="statusTab = 'active'"
                    >
                        Ativos
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.active }}
                        </span>
                    </button>

                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                        :class="
                            statusTab === 'inactive'
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        "
                        @click="statusTab = 'inactive'"
                    >
                        Desativados
                        <span class="rounded-full bg-muted-foreground/15 px-1.5 py-0.5 text-[10px] font-semibold tabular-nums">
                            {{ stats.inactive }}
                        </span>
                    </button>
                </div>

                <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                    <div class="relative w-full sm:w-64">
                        <Search class="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
                        <Input
                            v-model="search"
                            placeholder="Buscar por nome ou e-mail..."
                            class="h-9 pl-9"
                        />
                    </div>

                    <Button class="h-9 w-full sm:w-auto" @click="openCreateSheet">
                        <Plus class="h-4 w-4" />
                        Novo Usuário
                    </Button>
                </div>
            </div>

            <!-- Tabela de Usuários com Scroll Horizontal Isolado -->
            <div class="w-full overflow-hidden rounded-md border bg-card">
                <div class="w-full overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-14 whitespace-nowrap">ID</TableHead>
                                <TableHead class="whitespace-nowrap">Nome</TableHead>
                                <TableHead class="whitespace-nowrap">E-mail</TableHead>
                                <TableHead class="whitespace-nowrap">Cargo</TableHead>
                                <TableHead class="whitespace-nowrap">Status de Acesso</TableHead>
                                <TableHead class="whitespace-nowrap">Último Acesso</TableHead>
                                <TableHead class="whitespace-nowrap">Data de Cadastro</TableHead>
                                <TableHead class="whitespace-nowrap text-right">Ações</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="user in users.data" :key="user.id">
                                <TableCell class="whitespace-nowrap font-mono text-xs text-muted-foreground">
                                    #{{ user.id }}
                                </TableCell>
                                <TableCell class="whitespace-nowrap font-medium">
                                    <div class="flex items-center gap-2">
                                        <span>{{ user.name }}</span>
                                        <span
                                            v-if="user.id === page.props.auth.user.id"
                                            class="rounded-full bg-muted px-2 py-0.5 text-[10px] font-semibold text-muted-foreground"
                                        >
                                            Você
                                        </span>
                                    </div>
                                </TableCell>
                                <TableCell class="whitespace-nowrap text-muted-foreground">
                                    {{ user.email }}
                                </TableCell>
                                <TableCell class="whitespace-nowrap">
                                    <Badge :variant="user.role === 'admin' ? 'default' : 'secondary'">
                                        {{ user.role === 'admin' ? 'Administrador' : 'Usuário Comum' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="whitespace-nowrap">
                                    <Badge
                                        :variant="user.is_active ? 'outline' : 'destructive'"
                                        class="inline-flex items-center gap-1.5"
                                    >
                                        <span
                                            class="h-1.5 w-1.5 rounded-full"
                                            :class="user.is_active ? 'bg-emerald-500' : 'bg-current'"
                                        />
                                        {{ user.is_active ? 'Ativo' : 'Desativado' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="whitespace-nowrap text-xs text-muted-foreground tabular-nums">
                                    {{ formatDate(user.latest_login?.created_at) }}
                                </TableCell>
                                <TableCell class="whitespace-nowrap text-xs text-muted-foreground tabular-nums">
                                    {{ formatDate(user.created_at) }}
                                </TableCell>
                                <TableCell class="whitespace-nowrap text-right">
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="h-8 w-8 text-muted-foreground hover:text-foreground"
                                        @click="openEditSheet(user)"
                                    >
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="users.data.length === 0">
                                <TableCell colspan="8" class="h-24 text-center text-muted-foreground">
                                    Nenhum usuário encontrado com os filtros informados.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- Rodapé de Paginação -->
                <div class="flex flex-col items-center justify-between gap-2 border-t px-4 py-3 text-sm text-muted-foreground sm:flex-row">
                    <div>
                        Mostrando página {{ users.current_page }} de {{ users.last_page }} ({{ users.total }} usuários)
                    </div>
                    <div class="flex gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!users.prev_page_url"
                            @click="users.prev_page_url && router.get(users.prev_page_url, {}, { preserveState: true, preserveScroll: true })"
                        >
                            Anterior
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!users.next_page_url"
                            @click="users.next_page_url && router.get(users.next_page_url, {}, { preserveState: true, preserveScroll: true })"
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
                        {{ editingUser ? `Editar usuário #${editingUser.id}` : 'Novo usuário' }}
                    </SheetTitle>
                    <SheetDescription class="text-sm">
                        Configure as credenciais, o cargo e o status de acesso ao ERP.
                    </SheetDescription>
                </SheetHeader>

                <form class="flex min-h-0 flex-1 flex-col" autocomplete="off" @submit.prevent="submitForm">
                    <!-- Corpo rolável compacto (gap-3 e px-5 py-4) -->
                    <div class="flex flex-1 flex-col gap-3 overflow-y-auto px-5 py-4">
                        <!-- 1. Cargo / Permissão em Botões no Topo -->
                        <div class="grid gap-1.5">
                            <Label>Cargo / Permissão</Label>
                            <div class="grid grid-cols-2 gap-2">
                                <Button
                                    type="button"
                                    :variant="form.role === 'user' ? 'default' : 'outline'"
                                    class="w-full"
                                    :disabled="isEditingSelf"
                                    @click="form.role = 'user'"
                                >
                                    <UserIcon class="h-4 w-4" />
                                    Usuário Comum
                                </Button>
                                <Button
                                    type="button"
                                    :variant="form.role === 'admin' ? 'default' : 'outline'"
                                    class="w-full"
                                    :disabled="isEditingSelf"
                                    @click="form.role = 'admin'"
                                >
                                    <ShieldCheck class="h-4 w-4" />
                                    Administrador
                                </Button>
                            </div>
                            <InputError :message="form.errors.role" />
                        </div>

                        <!-- 2. Nome completo -->
                        <div class="grid gap-1.5">
                            <Label for="name">Nome completo</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                required
                                placeholder="Ex: João Silva"
                            />
                            <InputError :message="form.errors.name" />
                        </div>

                        <!-- 3. E-mail -->
                        <div class="grid gap-1.5">
                            <Label for="email">Endereço de e-mail</Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                autocomplete="off"
                                placeholder="ex: joao@ventania.com"
                            />
                            <InputError :message="form.errors.email" />
                        </div>

                        <!-- 4. Senha -->
                        <div class="grid gap-1.5">
                            <Label for="password">
                                {{ editingUser ? 'Nova senha (deixe em branco para manter)' : 'Senha de acesso' }}
                            </Label>
                            <Input
                                id="password"
                                v-model="form.password"
                                type="password"
                                autocomplete="new-password"
                                :required="!editingUser"
                                placeholder="Mínimo de 8 caracteres"
                            />
                            <InputError :message="form.errors.password" />
                        </div>

                        <!-- 5. Checkbox de Conta Ativa -->
                        <div class="flex items-center gap-2 pt-0.5">
                            <input
                                id="is_active"
                                v-model="form.is_active"
                                type="checkbox"
                                :disabled="isEditingSelf"
                                class="h-4 w-4 rounded border-input text-primary focus:ring-primary disabled:opacity-50"
                            />
                            <Label for="is_active" class="cursor-pointer text-xs">
                                Conta ativa (permite login e operação no sistema)
                            </Label>
                        </div>

                        <p v-if="isEditingSelf" class="text-xs text-muted-foreground">
                            Por segurança, você não pode remover seu próprio cargo de administrador ou inativar a própria conta.
                        </p>
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
                                    : editingUser
                                      ? 'Salvar alterações'
                                      : 'Cadastrar usuário'
                            }}
                        </Button>
                    </SheetFooter>
                </form>
            </SheetContent>
        </Sheet>
    </AppLayout>
</template>