<script setup lang="ts">
import { Check, ChevronsUpDown, Search } from 'lucide-vue-next';
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';

export interface ComboboxProductOption {
    id: number;
    name: string;
    sku: string;
    meta?: string;
}

interface Props {
    modelValue: number | string;
    options: ComboboxProductOption[];
    disabledIds?: Array<number | string>;
    placeholder?: string;
}

const props = withDefaults(defineProps<Props>(), {
    disabledIds: () => [],
    placeholder: 'Selecione um produto...',
});

const emit = defineEmits<{
    'update:modelValue': [value: number];
}>();

const isOpen = ref(false);
const searchQuery = ref('');
const containerRef = ref<HTMLElement | null>(null);
const searchInputRef = ref<HTMLInputElement | null>(null);

const selectedOption = computed(() =>
    props.options.find((opt) => opt.id === Number(props.modelValue)),
);

const filteredOptions = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();
    if (!query) return props.options;

    return props.options.filter(
        (opt) =>
            opt.name.toLowerCase().includes(query) ||
            opt.sku.toLowerCase().includes(query),
    );
});

const isOptionDisabled = (id: number): boolean => {
    return (
        id !== Number(props.modelValue) &&
        props.disabledIds.some((disabledId) => Number(disabledId) === id)
    );
};

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;

    if (isOpen.value) {
        searchQuery.value = '';
        nextTick(() => {
            searchInputRef.value?.focus();
        });
    }
};

const selectOption = (option: ComboboxProductOption) => {
    if (isOptionDisabled(option.id)) return;
    emit('update:modelValue', option.id);
    isOpen.value = false;
};

const handleClickOutside = (event: MouseEvent) => {
    if (containerRef.value && !containerRef.value.contains(event.target as Node)) {
        isOpen.value = false;
    }
};

onMounted(() => document.addEventListener('mousedown', handleClickOutside));
onBeforeUnmount(() => document.removeEventListener('mousedown', handleClickOutside));
</script>

<template>
    <div ref="containerRef" class="relative min-w-0 flex-1">
        <!-- Botão Gatilho (Mesma altura h-9 e comportamento Flexbox min-w-0) -->
        <button
            type="button"
            class="flex h-9 w-full min-w-0 items-center justify-between gap-2 rounded-md border border-input bg-background px-2.5 text-left text-xs ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring"
            @click="toggleDropdown"
        >
            <span v-if="selectedOption" class="truncate">
                <strong class="font-mono text-muted-foreground">{{ selectedOption.sku }}</strong>
                — {{ selectedOption.name }}
                <span v-if="selectedOption.meta" class="text-muted-foreground">
                    ({{ selectedOption.meta }})
                </span>
            </span>
            <span v-else class="truncate text-muted-foreground">
                {{ placeholder }}
            </span>
            <ChevronsUpDown class="h-3.5 w-3.5 shrink-0 opacity-50" />
        </button>

        <!-- Painel Flutuante com Busca Instantânea -->
        <div
            v-if="isOpen"
            class="absolute left-0 right-0 top-full z-50 mt-1 overflow-hidden rounded-md border bg-popover text-popover-foreground shadow-md"
        >
            <!-- Barra de Busca Interna -->
            <div class="flex items-center border-b px-2.5">
                <Search class="mr-2 h-3.5 w-3.5 shrink-0 text-muted-foreground" />
                <input
                    ref="searchInputRef"
                    v-model="searchQuery"
                    type="text"
                    placeholder="Buscar por SKU ou nome..."
                    class="flex h-8 w-full bg-transparent text-xs outline-none placeholder:text-muted-foreground"
                    @keydown.esc.stop="isOpen = false"
                />
            </div>

            <!-- Lista Rolável de Resultados -->
            <div class="max-h-52 overflow-y-auto p-1">
                <button
                    v-for="opt in filteredOptions"
                    :key="opt.id"
                    type="button"
                    :disabled="isOptionDisabled(opt.id)"
                    class="flex w-full items-center justify-between gap-2 rounded-sm px-2 py-1.5 text-left text-xs transition-colors hover:bg-accent hover:text-accent-foreground disabled:pointer-events-none disabled:opacity-40"
                    @click="selectOption(opt)"
                >
                    <div class="flex min-w-0 flex-1 items-center gap-1.5">
                        <Check
                            class="h-3.5 w-3.5 shrink-0"
                            :class="
                                Number(modelValue) === opt.id
                                    ? 'text-primary opacity-100'
                                    : 'opacity-0'
                            "
                        />
                        <span class="shrink-0 font-mono text-muted-foreground">{{ opt.sku }}</span>
                        <span class="truncate font-medium">{{ opt.name }}</span>
                    </div>

                    <span
                        v-if="opt.meta"
                        class="shrink-0 rounded bg-muted px-1.5 py-0.5 font-mono text-[10px] text-muted-foreground tabular-nums"
                    >
                        {{ opt.meta }}
                    </span>
                </button>

                <div
                    v-if="filteredOptions.length === 0"
                    class="py-4 text-center text-xs text-muted-foreground"
                >
                    Nenhum produto encontrado para "{{ searchQuery }}".
                </div>
            </div>
        </div>
    </div>
</template>