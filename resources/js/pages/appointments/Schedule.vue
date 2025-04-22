<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
</script>

<template>
    <AppLayout>
        <Head title="Agenda" />
        <div class="flex bg-sidebar h-full flex-1 flex-col gap-4 p-4">
            <div class="flex justify-between items-center">
                <h1 class="font-bold" >Agenda de Consultas</h1>
                <Button variant="scheduleLink">Copiar link</Button>
            </div>
            <div class="relative min-h-[100vh] bg-white flex-1 rounded-xl border border-sidebar-border dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>