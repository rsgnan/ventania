<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase
        title="Acesse sua conta"
        description="Informe seu e-mail e senha abaixo para entrar no ERP Ventania"
    >
        <Head title="Acessar o Sistema" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-emerald-600">
            {{ status }}
        </div>

        <form class="flex flex-col gap-6" @submit.prevent="submit">
            <div class="grid gap-4">
                <div class="grid gap-1.5">
                    <Label for="email">Endereço de e-mail</Label>
                    <Input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="nome@ventania.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-1.5">
                    <Label for="password">Senha de acesso</Label>
                    <Input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Sua senha"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between" :tabindex="3">
                    <Label for="remember" class="flex cursor-pointer items-center space-x-2.5 text-xs">
                        <Checkbox id="remember" v-model:checked="form.remember" :tabindex="4" />
                        <span>Manter conectado neste computador</span>
                    </Label>
                </div>

                <Button type="submit" class="mt-2 w-full" :tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    {{ form.processing ? 'Autenticando...' : 'Entrar no sistema' }}
                </Button>
            </div>
        </form>
    </AuthBase>
</template>
