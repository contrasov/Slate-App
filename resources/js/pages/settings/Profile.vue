<script setup lang="ts">
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
import { formatBrazilianPhone } from '@/lib/phone';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    email: user.email,
    public_name: user.public_name ?? '',
    phone: user.phone ?? '',
    specialty: user.specialty ?? '',
    timezone: user.timezone ?? 'America/Fortaleza',
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};

const formatPhoneInput = () => {
    form.phone = formatBrazilianPhone(form.phone);
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Configurações do Perfil" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Informações do perfil" description="Atualizar seu nome e e-mail." />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">Nome e sobrenome</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Seu nome e sobrenome" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2"><Label for="public_name">Nome público</Label><Input id="public_name" v-model="form.public_name" required/><InputError :message="form.errors.public_name" /></div>
                    <div class="grid gap-2"><Label for="phone">Telefone de contato</Label><Input id="phone" v-model="form.phone" @input="formatPhoneInput" type="tel" required/><InputError :message="form.errors.phone" /></div>
                    <div class="grid gap-2"><Label for="specialty">Área de atuação</Label><Input id="specialty" v-model="form.specialty" required/><InputError :message="form.errors.specialty" /></div>
                    <div class="grid gap-2"><Label for="timezone">Fuso horário</Label><select id="timezone" v-model="form.timezone" class="h-10 rounded-md border border-input bg-background px-3 text-sm"><option value="America/Fortaleza">Fortaleza (UTC−03:00)</option><option value="America/Sao_Paulo">São Paulo (UTC−03:00)</option><option value="America/Manaus">Manaus (UTC−04:00)</option><option value="America/Rio_Branco">Rio Branco (UTC−05:00)</option></select><InputError :message="form.errors.timezone" /></div>

                    <div class="grid gap-2">
                        <Label for="email">Endereço de e-mail</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Endereço de e-mail"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:!decoration-current dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button variant="slateDefault" :disabled="form.processing">Salvar</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Salvo.</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
