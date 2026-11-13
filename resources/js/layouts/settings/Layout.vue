<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Perfil',
        href: '/settings/profile',
    },
    {
        title: 'Senha',
        href: '/settings/password',
    },
    {
        title: 'Aparência',
        href: '/settings/appearance',
    },
];

const page = usePage();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <main class="min-h-full flex-1 bg-[#f5f7f6] p-4 sm:p-6 xl:p-8">
        <div class="mx-auto flex w-full max-w-[1440px] flex-col gap-6">
            <header class="space-y-1">
                <p class="text-sm font-medium text-greenPrincipal1">Conta</p>
                <h1 class="text-2xl font-semibold tracking-tight text-darkTextPrincipal1 sm:text-3xl">Configurações</h1>
                <p class="text-sm text-muted-foreground">Gerencie seu perfil e as definições da conta.</p>
            </header>

            <section class="flex flex-col gap-5 rounded-2xl border border-sidebar-border/70 bg-white p-4 shadow-sm sm:p-6 lg:flex-row lg:gap-8">
                <aside class="w-full shrink-0 lg:w-52">
                    <nav class="flex gap-2 overflow-x-auto lg:flex-col" aria-label="Seções de configurações">
                        <Button
                            v-for="item in sidebarNavItems"
                            :key="item.href"
                            variant="ghost"
                            :class="[
                                'shrink-0 justify-start rounded-lg px-3',
                                currentPath === item.href ? 'bg-greenPrincipal1/15 font-medium text-darkTextPrincipal1 hover:bg-greenPrincipal1/20' : 'text-muted-foreground',
                            ]"
                            as-child
                        >
                            <Link :href="item.href">
                                {{ item.title }}
                            </Link>
                        </Button>
                    </nav>
                </aside>

                <Separator class="lg:hidden" />

                <div class="min-w-0 flex-1 lg:border-l lg:border-sidebar-border/70 lg:pl-8">
                    <section class="max-w-3xl space-y-8">
                        <slot />
                    </section>
                </div>
            </section>
        </div>
    </main>
</template>
