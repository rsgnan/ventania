<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Configurações de Aparência',
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Configurações de Aparência" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    title="Aparência do sistema"
                    description="Personalize o tema visual do ERP neste dispositivo"
                />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
