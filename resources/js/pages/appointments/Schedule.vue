<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import { useToast } from '@/components/ui/toast'
import { type SharedData, type User } from '@/types';
import ScheduleCalendar from '@/components/ScheduleCalendar.vue';
import WorkTime from '@/components/WorkTime.vue'

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const props = defineProps<{
    appointments: Array<{ id: number; patient: { id: number; name: string }; appointment_time: string; appointment_date: string; patient_id: number; status: string; phone: string }>;
    weeklyAvailability: Array<{ id: number; weekday: number; start_time: string; slot_count: number; generated_times: string[]; available_times: string[] }>;
    appointmentDuration: string;
    freeDays: Array<{ id: number; free_day: string }>;
    bookedDates: Record<string, number | string>;
    successMessage?: string;
}>();

const { toast } = useToast();

onMounted(() => {
    if (props.successMessage) {
        toast({
            title: 'Agenda atualizada',
            description: props.successMessage,
            variant: 'defaultSlate',
        });
    }
});

const copylink = () => {
    const link = route('schedule.public', { token: user.schedule_token });
    navigator.clipboard.writeText(link);
}
</script>

<template>
    <AppLayout>
        <Head title="Agenda" />
        <main class="min-h-full flex-1 bg-[#f5f7f6] p-4 sm:p-6 xl:p-8">
            <div class="mx-auto flex w-full max-w-[1440px] flex-col gap-6">
                <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-greenPrincipal1">Atendimento</p>
                        <h1 class="text-2xl font-semibold tracking-tight text-darkTextPrincipal1 sm:text-3xl">Agenda</h1>
                        <p class="text-sm text-muted-foreground">Acompanhe as consultas e sua disponibilidade semanal.</p>
                    </div>
                    <Button
                        variant="scheduleLink"
                        class="self-start sm:self-auto"
                        @click="() => {
                            toast({
                                title: 'Link copiado',
                                variant: 'defaultSlate'
                            })
                            copylink();
                        }"
                    >
                        Copiar link
                    </Button>
                </header>
            <WorkTime
                :weekly-availability="props.weeklyAvailability"
                :appointment-duration="props.appointmentDuration"
                :free-days="props.freeDays"
            />
            <section class="min-h-0 overflow-hidden rounded-2xl border border-sidebar-border/70 bg-white p-2 shadow-sm sm:p-4" aria-label="Calendário de consultas">
                <ScheduleCalendar
                    :appointments="props.appointments"
                    :weekly-availability="props.weeklyAvailability"
                    :free-days="props.freeDays"
                />
            </section>
            </div>
        </main>
    </AppLayout>
</template>
