<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Configurações de aparência" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Configurações de aparência" description="Atualize as configurações de aparência da sua conta." />
                <!-- <AppearanceTabs /> -->
                <div class="rounded-xl border border-sidebar-border/70 bg-muted/20 p-5">
                    <p class="text-sm text-muted-foreground">As opções de aparência estarão disponíveis em breve.</p>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
