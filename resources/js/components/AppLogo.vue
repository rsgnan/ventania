<script setup lang="ts">
import { Warehouse } from 'lucide-vue-next';

interface Props {
    class?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground">
        <Warehouse class="size-4" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-semibold">Ventania</span>
        <span class="text-muted-foreground truncate text-xs">ERP & Hub</span>
    </div>
</template>
