<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input'
import { Trash2, Pencil, Eye } from 'lucide-vue-next';
</script>

<template>
    <AppLayout>
        <Head title="Fichas" />
        <div class="flex bg-sidebar h-full flex-1 flex-col gap-4 p-4">
            <div class="flex justify-between items-center">
                <h1 class="font-bold" >Lista de Fichas</h1>
                <Button variant="slateDefault"> <Plus class="h-4 w-4" /> Criar ficha</Button>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl dark:border-sidebar-border md:min-h-min">
                <div class="flex items-center gap-2 max-w-md mb-4"> <!-- vai bom mb mesmo -->
                    <Input type="text" placeholder="Pesquisar ficha" />
                </div>
                <div class="flex flex-col gap-2">
                    <h1 class="text-gray-400 text-xs" >123 fichas</h1>

                    <!-- fichas -->
                    <div class="flex flex-col gap-2 overflow-y-auto max-h-[70vh]">
                        <div 
                            v-for="index in 5" 
                            :key="index" 
                            class="flex flex-row justify-between items-center w-full bg-white rounded-xl p-4 border border-sidebar-border"
                        >
                            <div class="flex flex-col gap-1">
                                <div class="flex flex-row gap-2 items-center">
                                    <h1>Nome da ficha {{ index }}</h1>
                                    <span class="text-gray-400 text-sm"> - </span>
                                    <h2 class="text-sm">07/05/2025</h2>
                                </div>
                                <span class="flex flex-row gap-2">
                                    <h2 class="text-gray-400 text-sm max-w-[80%]">
                                        Et aspernatur deserunt ab consectetur ipsum quo 
                                        voluptatum fugit vel galisum quasi ut illo laborum.</h2>
                                </span>
                            </div>

                            <div class="flex flex-row gap-2 items-center">
                                <Eye class="text-gray-400 h-5 w-5 hover:text-greenPrincipal1 cursor-pointer" />
                                <Pencil class="text-gray-400 h-5 w-5 hover:text-greenPrincipal1 cursor-pointer" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>