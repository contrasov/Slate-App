<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
</script>

<template>
    <AppLayout>
        <Head title="Fichas" />
        <div class="flex bg-sidebar h-full flex-1 flex-col gap-4 p-4">
            <div class="flex justify-between items-center">
                <h1 class="font-bold" >Lista de Fichas</h1>
                <Button variant="slateDefault"> <Plus class="h-4 w-4" /> Criar ficha</Button>
            </div>
            <div class="bg-white relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>