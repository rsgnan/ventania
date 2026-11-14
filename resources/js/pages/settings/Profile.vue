<script setup lang="ts">
import { TransitionRoot } from '@headlessui/vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    className?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Configurações de Perfil',
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Configurações de Perfil" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="Informações do perfil"
                    description="Atualize o seu nome e endereço de e-mail"
                />

                <form class="space-y-6" @submit.prevent="submit">
                    <div class="grid gap-1.5">
                        <Label for="name">Nome completo</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            class="mt-1 block w-full"
                            required
                            autocomplete="name"
                            placeholder="Seu nome completo"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-1.5">
                        <Label for="email">Endereço de e-mail</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full"
                            required
                            autocomplete="username"
                            placeholder="Seu e-mail"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">
                            {{ form.processing ? 'Salvando...' : 'Salvar alterações' }}
                        </Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-emerald-600">
                                Salvo com sucesso.
                            </p>
                        </Transition>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
