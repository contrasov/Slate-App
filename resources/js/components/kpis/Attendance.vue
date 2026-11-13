<script setup lang="ts">
import { UserRound } from 'lucide-vue-next';

defineProps<{
    completedAppointments: number;
}>();
</script>

<template>
    <div class="flex min-w-0 flex-col gap-3">
        <div class="flex items-center gap-2">
            <UserRound class="size-4 shrink-0 text-greenPrincipal1" />
            <p class="text-sm font-medium text-muted-foreground">Total de atendimentos</p>
        </div>
        <p class="text-3xl font-semibold tracking-tight text-darkTextPrincipal1">{{ completedAppointments }}</p>
        <p class="text-xs text-muted-foreground">Consultas com atendimento encerrado</p>
    </div>
</template>
