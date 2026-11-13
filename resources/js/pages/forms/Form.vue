<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input'
import { Pencil, Eye } from 'lucide-vue-next';
</script>

<template>
    <AppLayout>
        <Head title="Fichas" />
        <main class="min-h-full flex-1 bg-[#f5f7f6] p-4 sm:p-6 xl:p-8">
            <div class="mx-auto flex w-full max-w-[1440px] flex-col gap-6">
                <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-greenPrincipal1">Atendimento</p>
                        <h1 class="text-2xl font-semibold tracking-tight text-darkTextPrincipal1 sm:text-3xl">Fichas</h1>
                        <p class="text-sm text-muted-foreground">Organize os documentos e registros dos pacientes.</p>
                    </div>
                    <Button variant="slateDefault" class="self-start sm:self-auto"><Plus class="h-4 w-4" /> Criar ficha</Button>
                </header>
                <section class="rounded-2xl border border-sidebar-border/70 bg-white p-4 shadow-sm sm:p-6">
                    <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <Input type="text" placeholder="Pesquisar ficha" class="sm:max-w-sm" />
                        <Button variant="outline" class="h-10 self-start sm:self-auto">Buscar</Button>
                    </div>
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="text-sm font-medium text-muted-foreground">5 fichas</h2>
                    </div>

                    <!-- fichas -->
                    <div class="flex max-h-[70vh] flex-col gap-3 overflow-y-auto">
                        <article
                            v-for="index in 5"
                            :key="index"
                            class="flex w-full flex-col justify-between gap-4 rounded-xl border border-sidebar-border/70 bg-white p-4 transition-colors hover:bg-muted/20 sm:flex-row sm:items-center"
                        >
                            <div class="flex min-w-0 flex-col gap-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-medium text-darkTextPrincipal1">Nome da ficha {{ index }}</h3>
                                    <span class="text-sm text-muted-foreground">·</span>
                                    <time class="text-sm text-muted-foreground">07/05/2025</time>
                                </div>
                                <p class="max-w-3xl text-sm text-muted-foreground">
                                    Et aspernatur deserunt ab consectetur ipsum quo voluptatum fugit vel galisum quasi ut illo laborum.
                                </p>
                            </div>

                            <div class="flex shrink-0 items-center gap-2">
                                <Button variant="ghost" size="icon" aria-label="Visualizar ficha">
                                    <Eye class="h-5 w-5 text-muted-foreground" />
                                </Button>
                                <Button variant="ghost" size="icon" aria-label="Editar ficha">
                                    <Pencil class="h-5 w-5 text-muted-foreground" />
                                </Button>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </main>
    </AppLayout>
</template>
