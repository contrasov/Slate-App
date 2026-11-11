<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { computed } from 'vue';

const form = useForm({
    name: '',
    email: '',
    account_type: 'professional',
    password: '',
    password_confirmation: '',
});
const passwordConfirmationMatches = computed(
    () => form.password_confirmation.length > 0 && form.password_confirmation === form.password,
);

const submit = () => {
    form.post(route('user.register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Crie uma conta" description="Insira seus dados abaixo para criar sua conta.">
        <Head title="Registrar" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-3">
                <div class="grid gap-2">
                    <Label for="name">Nome e sobrenome</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Seu nome e sobrenome" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="account_type">Tipo de atendimento</Label>
                    <select id="account_type" v-model="form.account_type" class="h-10 rounded-md border border-input bg-background px-3 text-sm" required :tabindex="2">
                        <option value="professional">Profissional autônomo</option>
                        <option value="clinic">Consultório ou clínica</option>
                    </select>
                    <InputError :message="form.errors.account_type" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">E-mail</Label>
                    <Input id="email" type="email" required :tabindex="3" autocomplete="email" v-model="form.email" placeholder="email@exemplo.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Senha</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Senha"
                    />
                    <p class="text-xs text-muted-foreground">Mínimo de 8 caracteres.</p>
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirmar Senha</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :class="passwordConfirmationMatches ? 'border-green-500 focus-visible:ring-green-500' : ''"
                        :tabindex="5"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Confirmar senha"
                    />
                    <p class="text-xs text-muted-foreground">Mínimo de 8 caracteres.</p>
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button variant="slateDefault" type="submit" class="mt-2 w-full" tabindex="6" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Criar Conta
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Você já tem uma conta?
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="7">Login</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
