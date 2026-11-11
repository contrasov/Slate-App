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
        <div class="flex bg-sidebar h-full flex-1 flex-col gap-4 p-4">
            <div class="flex justify-between items-center">
                <h1 class="font-bold" >Agenda de Consultas</h1>
                <div>
                    <Button
                        variant="scheduleLink" @click="() => {
                        toast({
                            title: 'Link copiado',
                            variant: 'defaultSlate'
                        })
                        copylink();
                        }"
                    >
                        Copiar link
                    </Button>
                </div>
            </div>
            <WorkTime
                :weekly-availability="props.weeklyAvailability"
                :appointment-duration="props.appointmentDuration"
                :free-days="props.freeDays"
            />
            <div class="bg-white flex-1 rounded-xl border border-sidebar-border dark:border-sidebar-border md:min-h-min">
                <ScheduleCalendar :appointments="props.appointments"/>
            </div>
        </div>
    </AppLayout>
</template>
