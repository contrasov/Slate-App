<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <AppLayout>
        <Head title="Pacientes" />
        <div class="flex bg-sidebar h-full flex-1 flex-col gap-4 p-4">
            <h1 class="font-bold" >Lista de Pacientes</h1>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>