<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { 
    Sidebar, 
    SidebarContent, 
    SidebarFooter, 
    SidebarHeader, 
    SidebarMenu, 
    SidebarMenuButton, 
    SidebarMenuItem
} from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { 
    ArrowDownUp, 
    FolderGit2, 
    LayoutGrid, 
    Package, 
    Users
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage<SharedData>();

// Monta o menu dinamicamente: apenas administradores enxergam o módulo de Usuários
const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: '/dashboard',
            icon: LayoutGrid,
        },
        {
            title: 'Produtos',
            href: '/products',
            icon: Package,
        },
        {
            title: 'Movimentações',
            href: '/stock-movements',
            icon: ArrowDownUp,
        },
    ];

    if (page.props.auth.user?.role === 'admin') {
        items.push({
            title: 'Usuários',
            href: '/users',
            icon: Users,
        });
    }

    return items;
});

const footerNavItems: NavItem[] = [
    {
        title: 'Repositório Github',
        href: 'https://github.com/rsgnan/ventania',
        icon: FolderGit2,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
