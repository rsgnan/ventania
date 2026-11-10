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
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Usuários', href: '/users' },
];

// Estado dos filtros da listagem
const search = ref(props.filters.search ?? '');
const roleFilter = ref(props.filters.role ?? '');
const statusFilter = ref(props.filters.status ?? '');

// Dispara a filtragem no backend com debounce para evitar excesso de requisições ao digitar
let searchTimeout: ReturnType<typeof setTimeout>;
const applyFilters = () => {
    router.get(
        '/users',
        {
            search: search.value || undefined,
            role: roleFilter.value || undefined,
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

watch([roleFilter, statusFilter], applyFilters);

// Controle do painel lateral (<Sheet>) de criação e edição
const isSheetOpen = ref(false);
const editingUser = ref<UserRow | null>(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'user' as 'admin' | 'user',
    is_active: true,
});

// Verifica se o administrador logado está editando a própria conta
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
        <div class="flex flex-1 flex-col gap-6 p-6">
            <!-- Cabeçalho da Página -->
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Usuários</h1>
                    <p class="text-sm text-muted-foreground">
                        Gerencie os operadores e administradores do ERP Ventania.
                    </p>
                </div>
                <Button @click="openCreateSheet">
                    <Plus class="mr-2 h-4 w-4" />
                    Novo Usuário
                </Button>
            </div>

            <!-- Cards Superiores de KPIs -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between">
                            <CardDescription>Total de Usuários</CardDescription>
                            <Badge variant="outline" class="flex items-center gap-1 rounded-lg text-xs font-medium">
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
                            <TrendingUp class="h-4 w-4 text-muted-foreground" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Todos os operadores cadastrados no ERP
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between">
                            <CardDescription>Usuários Ativos</CardDescription>
                            <Badge variant="outline" class="flex items-center gap-1 rounded-lg text-xs font-medium">
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
                            <UserCheck class="h-4 w-4 text-emerald-500" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Contas com permissão de login imediato
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between">
                            <CardDescription>Usuários Inativos</CardDescription>
                            <Badge variant="outline" class="flex items-center gap-1 rounded-lg text-xs font-medium">
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
                            <UserX class="h-4 w-4 text-destructive" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Mantidos intactos para trilha de auditoria
                        </div>
                    </CardFooter>
                </Card>

                <Card class="flex flex-col justify-between">
                    <CardHeader class="pb-2">
                        <div class="flex items-center justify-between">
                            <CardDescription>Administradores</CardDescription>
                            <Badge variant="outline" class="flex items-center gap-1 rounded-lg text-xs font-medium">
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
                            <ShieldCheck class="h-4 w-4 text-primary" />
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Acesso total aos módulos e configurações
                        </div>
                    </CardFooter>
                </Card>
            </div>

            <!-- Barra de Filtros -->
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <Search class="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
                    <Input
                        v-model="search"
                        placeholder="Buscar por nome ou e-mail..."
                        class="pl-9"
                    />
                </div>

                <select
                    v-model="roleFilter"
                    class="h-9 rounded-md border border-input bg-background px-3 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring"
                >
                    <option value="">Todos os perfis</option>
                    <option v-for="role in roles" :key="role.value" :value="role.value">
                        {{ role.label }}
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
            </div>

            <!-- Tabela de Usuários -->
            <div class="rounded-md border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Nome</TableHead>
                            <TableHead>E-mail</TableHead>
                            <TableHead>Perfil</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Último Acesso</TableHead>
                            <TableHead class="text-right">Ações</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in users.data" :key="user.id">
                            <TableCell class="font-medium">{{ user.name }}</TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell>
                                <Badge :variant="user.role === 'admin' ? 'default' : 'secondary'">
                                    {{ user.role === 'admin' ? 'Administrador' : 'Usuário' }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <Badge :variant="user.is_active ? 'outline' : 'destructive'">
                                    {{ user.is_active ? 'Ativo' : 'Inativo' }}
                                </Badge>
                            </TableCell>
                            <TableCell class="text-muted-foreground">
                                {{ formatDate(user.latest_login?.created_at) }}
                            </TableCell>
                            <TableCell class="text-right">
                                <Button variant="ghost" size="sm" @click="openEditSheet(user)">
                                    <Pencil class="mr-1 h-4 w-4" />
                                    Editar
                                </Button>
                            </TableCell>
                        </TableRow>

                        <TableRow v-if="users.data.length === 0">
                            <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                                Nenhum usuário encontrado com os filtros informados.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <!-- Rodapé de Paginação -->
                <div class="flex items-center justify-between border-t px-4 py-3 text-sm text-muted-foreground">
                    <div>
                        Página {{ users.current_page }} de {{ users.last_page }} ({{ users.total }} registros)
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

        <!-- Painel Lateral (<Sheet>) de Criação / Edição -->
        <Sheet v-model:open="isSheetOpen">
            <SheetContent class="sm:max-w-md">
                <SheetHeader>
                    <SheetTitle>
                        {{ editingUser ? 'Editar Usuário' : 'Novo Usuário' }}
                    </SheetTitle>
                    <SheetDescription>
                        {{
                            editingUser
                                ? 'Atualize os dados cadastrais, nível de acesso ou status do operador.'
                                : 'Preencha os dados abaixo para cadastrar um novo operador no ERP.'
                        }}
                    </SheetDescription>
                </SheetHeader>

                <form class="mt-6 flex flex-col gap-4" @submit.prevent="submitForm">
                    <div class="grid gap-2">
                        <Label for="name">Nome completo</Label>
                        <Input id="name" v-model="form.name" required placeholder="Ex: João Silva" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">E-mail</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="nome@ventania.com"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">
                            {{ editingUser ? 'Nova senha (deixe em branco para manter)' : 'Senha inicial' }}
                        </Label>
                        <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            :required="!editingUser"
                            placeholder="Mínimo de 8 caracteres"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="role">Perfil de Acesso</Label>
                        <select
                            id="role"
                            v-model="form.role"
                            :disabled="isEditingSelf"
                            class="h-9 rounded-md border border-input bg-background px-3 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring disabled:opacity-50"
                        >
                            <option v-for="role in roles" :key="role.value" :value="role.value">
                                {{ role.label }}
                            </option>
                        </select>
                        <InputError :message="form.errors.role" />
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input
                            id="is_active"
                            v-model="form.is_active"
                            type="checkbox"
                            :disabled="isEditingSelf"
                            class="h-4 w-4 rounded border-input text-primary focus:ring-primary disabled:opacity-50"
                        />
                        <Label for="is_active" class="cursor-pointer">
                            Conta ativa (permite login e operação no sistema)
                        </Label>
                    </div>

                    <p v-if="isEditingSelf" class="text-xs text-muted-foreground">
                        Por segurança, você não pode remover seu próprio cargo de administrador ou inativar a própria conta.
                    </p>

                    <SheetFooter class="mt-4">
                        <Button type="button" variant="outline" @click="isSheetOpen = false">
                            Cancelar
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Salvando...' : 'Salvar' }}
                        </Button>
                    </SheetFooter>
                </form>
            </SheetContent>
        </Sheet>
    </AppLayout>
</template>