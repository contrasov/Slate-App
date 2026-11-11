<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import WeeklyAvailabilitySettings from '@/components/WeeklyAvailabilitySettings.vue';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    weeklyAvailability: Array<{ id: number; weekday: number; start_time: string; slot_count: number; generated_times: string[]; available_times: string[] }>;
    appointmentDuration: string;
    freeDays: Array<{ id: number; free_day: string }>;
    bookedDates: Record<string, number | string>;
}>();
</script>

<template>
    <AppLayout>
        <Head title="Configurar horários" />
        <main class="flex min-h-full flex-1 flex-col gap-4 bg-sidebar p-4">
            <div class="flex items-center gap-3">
                <Button variant="outline" size="icon" as-child>
                    <Link :href="route('schedule')" aria-label="Voltar para a agenda">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <div>
                    <h1 class="font-bold">Configurar horários</h1>
                    <p class="text-sm text-muted-foreground">Defina sua disponibilidade e os dias fechados.</p>
                </div>
            </div>
            <WeeklyAvailabilitySettings
                :weekly-availability="props.weeklyAvailability"
                :appointment-duration="props.appointmentDuration"
                :free-days="props.freeDays"
                :booked-dates="props.bookedDates"
            />
        </main>
    </AppLayout>
</template>
