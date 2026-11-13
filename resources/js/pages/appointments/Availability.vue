<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import WeeklyAvailabilitySettings from '@/components/WeeklyAvailabilitySettings.vue';
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
        <main class="min-h-full flex-1 bg-[#f5f7f6] p-4 sm:p-6 xl:p-8">
            <div class="mx-auto flex w-full max-w-[1440px] flex-col gap-6">
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('schedule')"
                        aria-label="Voltar para a agenda"
                        class="inline-flex size-10 shrink-0 items-center justify-center rounded-md text-foreground transition-colors hover:bg-muted focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    >
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                    <div>
                        <p class="text-sm font-medium text-greenPrincipal1">Agenda</p>
                        <h1 class="text-2xl font-semibold tracking-tight text-darkTextPrincipal1">Configurar horários</h1>
                        <p class="text-sm text-muted-foreground">Defina sua disponibilidade e os dias fechados.</p>
                    </div>
                </div>
                <WeeklyAvailabilitySettings
                    :weekly-availability="props.weeklyAvailability"
                    :appointment-duration="props.appointmentDuration"
                    :free-days="props.freeDays"
                    :booked-dates="props.bookedDates"
                />
            </div>
        </main>
    </AppLayout>
</template>
