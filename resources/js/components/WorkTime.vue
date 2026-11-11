<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';

type WeeklyAvailability = {
    id: number;
    weekday: number;
    start_time: string;
    slot_count: number;
    generated_times: string[];
    available_times: string[];
};

const props = defineProps<{
    weeklyAvailability: WeeklyAvailability[];
    freeDays: Array<{ id: number; free_day: string }>;
    appointmentDuration: string;
}>();

const weekdays = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
const activeDays = [...props.weeklyAvailability].sort((a, b) => a.weekday - b.weekday);
const daySummary = (times: string[]) => {
    if (!times.length) return 'Sem horários ativos';
    const first = times[0].slice(0, 5);
    const last = times[times.length - 1].slice(0, 5);
    return `${times.length} horário(s) · ${first}${first === last ? '' : `–${last}`}`;
};
</script>

<template>
    <section class="space-y-4 rounded-md border border-sidebar-border bg-white p-4 md:p-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <HeadingSmall title="Horário de atendimento" description="Veja sua disponibilidade semanal e ajuste os horários oferecidos para agendamento." />
            <Button variant="outline" as-child>
                <Link :href="route('schedule.availability')">{{ activeDays.length ? 'Editar Agenda' : 'Adicionar dia' }}</Link>
            </Button>
        </div>

        <div v-if="activeDays.length" class="grid grid-cols-2 gap-2 sm:grid-cols-3 xl:grid-cols-4">
            <div v-for="day in activeDays" :key="day.weekday" class="flex min-w-0 items-center justify-between gap-2 rounded-lg border bg-muted/20 px-3 py-2.5">
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium">{{ weekdays[day.weekday] }}</p>
                    <p class="truncate text-xs text-muted-foreground">{{ daySummary(day.available_times ?? []) }}</p>
                </div>
            </div>
        </div>
        <p v-else class="text-sm text-muted-foreground">Nenhum dia de atendimento configurado.</p>

        <div class="flex flex-wrap items-center gap-2 border-t pt-4 text-sm text-muted-foreground">
            <span>Duração média: {{ appointmentDuration }}.</span>
            <span v-if="freeDays.length">{{ freeDays.length }} data(s) fechada(s).</span>
        </div>
    </section>
</template>
