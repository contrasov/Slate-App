<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import Input from '@/components/ui/input/Input.vue';
import { CalendarDays, Clock } from 'lucide-vue-next';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { DateValue } from '@internationalized/date';
import { useToast } from '@/components/ui/toast';
import Toaster from '@/components/ui/toast/Toaster.vue'

const {toast} = useToast();

const props = defineProps<{
    user: {
        id: number;
        name: string;
        schedule_token: string;
    },
    schedules: Array<{
        id: number;
        weekday: number;
        start_time: string;
        end_time: string;
        duration: string;
    }> | null;
    appointments: Array<{
        id: number;
        appointment_time: string;
        appointment_date: string;
    }> | null;
}>();

const selectedDate = ref<DateValue | undefined>(undefined);
const selectedTime = ref<string | null>(null);
const availableTimes = ref<string[]>([]);
const scheduledAppointments = ref<{ time: string; date: string }[]>([]);
const availableDays = ref<number[]>([]);

/* ele ja remove os horarios que ja existe em appointments  */
if (props.appointments) {
    scheduledAppointments.value = props.appointments.map(appointment => ({
        time: appointment.appointment_time.slice(0, 5),
        date: appointment.appointment_date
    }));
}

const calculateAvailableDays = () => {
    if (!props.schedules || !Array.isArray(props.schedules)) return; 
    availableDays.value = props.schedules.map(schedule => schedule.weekday);
}

calculateAvailableDays();

const calculateAvailableTimes = (date: DateValue) => {
    const jsDate = new Date(date.toString());
    const jsWeekday = jsDate.getDay();
    const systemWeekday = (jsWeekday) % 7;
    
    const schedule = props.schedules?.find(s => s.weekday === systemWeekday);

    if(!schedule){
        availableTimes.value = [];
        return;
    }

    const start = new Date(`2000-01-01T${schedule.start_time}`);
    const end = new Date(`2000-01-01T${schedule.end_time}`);
    const duration = parseInt(schedule.duration.split(':')[0]) * 60 + parseInt(schedule.duration.split(':')[1]);

    const times: string[] = [];
    let current = start;

    while(current < end){
        const timeString = current.toTimeString().slice(0, 5);
        
        const isScheduled = scheduledAppointments.value.some(appointment => 
            appointment.date === jsDate.toISOString().split('T')[0] && appointment.time === timeString
        );

        if (!isScheduled) {
            times.push(timeString);
        }
        
        current.setMinutes(current.getMinutes() + duration);
    }

    availableTimes.value = times;
};

const dateSelect = (date: DateValue | undefined) => {
    if (!date) return;

    selectedDate.value = date;
    const jsDate = new Date(date.toString());
    form.date = jsDate.toISOString().split('T')[0];
    
    const jsWeekday = jsDate.getDay();
    form.weekday = (jsWeekday) % 7;
    
    calculateAvailableTimes(date);
};

const timeSelect = (time: string) => {
    selectedTime.value = time;
    form.schedule_time = time;
}

const submit = () => {
    if (!selectedDate.value || !selectedTime.value) {
        toast({
            title: 'Erro',
            description: 'Não é permitido deixar o campo vazio',
            variant: 'destructive'
        });
        return;
    }

    form.post(route('schedule.public.create', { token: props.user.schedule_token }), {
        onSuccess: () => {
            form.reset();
            selectedDate.value = undefined;
            selectedDate.value = undefined;
            toast({
                title: 'Agendamento realizado',
                description: 'Seu agendamento foi realizado com sucesso. Eles entrarão em contato em breve.',
                variant: 'defaultSlate'
            });
        }
    });
}

const form = useForm({
    user_id: props.user.id,
    name: '',
    phone: '',
    date: '',
    schedule_time: '',
    weekday: 0,
})

</script>

<template>
    <div class="relative h-[40vh] bg-[url('/images/ufc.jpg')] bg-cover bg-center">
        <main class="pt-[10vh] md:mx-[50vh] h-full">
            <Head title="Agendamento" />
            <div class="flex flex-1 flex-col gap-4 p-4">
                <div class="bg-white p-4 rounded-xl border border-sidebar-border">
                    <span class="flex gap-1 items-center">
                        <h1 class="font-bold">Agendar consulta com {{user.name}}</h1>
                    </span>
                    <h2 class="text-sm md:text-bm">Rua Endereço do local do estabelecimento, algum canto ai, 990099-399, Russas-CE</h2>
                </div>
                <div class="bg-white flex md:flex-row flex-col h-fit rounded-xl border border-sidebar-border">
                    <Calendar 
                    @update:modelValue="dateSelect" 
                    :availableDays="availableDays"
                    />
                    <div class="flex flex-col gap-2 w-full p-4">
                        <h1>Horarios disponíveis</h1>
                        <Button
                        v-for="time in availableTimes"
                        :key="time"
                        variant="slateTime"
                        @click="timeSelect(time)"
                        :class="{ 'bg-[#0db97f] border-none text-white' : selectedTime == time }"
                            >
                            {{ time }} 
                        </Button>
                    </div>
                </div>
                <div class="bg-white flex flex-col gap-4 p-4 rounded-xl border border-sidebar-border">
                    <div class="flex flex-col font-medium">
                        <div class="flex flex-row gap-1 text-[13px]">
                            <h1>Agendamento para:</h1>
                            <span class="flex items-center">
                                <CalendarDays class="h-4 p-0" />
                                <h1>{{ selectedDate ? new Date(selectedDate.year, selectedDate.month - 1, selectedDate.day).toLocaleDateString() : '(Data)' }}</h1>
                            </span>
                            <span class="flex items-center">
                                <Clock class="h-4 p-0" />
                                <h1>{{ selectedTime || '(Hora)' }}</h1>
                            </span>
                        </div>
                        <h3 class="text-[13px] py-1 text-gray-500">Insira suas informações de contato para agendar o atendimento.</h3>
                    </div>
                    
                    <div class="grid gap-2">
                        <label class="text-sm" for="name">Nome completo</label>
                        <Input v-model="form.name" 
                        required 
                        placeholder="Alvaro George Veras dos Santos"
                        />
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm" for="phone">Número para contato (Whatsapp)</label>
                        <Input 
                        v-model="form.phone" 
                        @keypress="(event: KeyboardEvent) => !/[0-9]/.test(event.key) && event.preventDefault()" 
                        required type="tel" 
                        maxlength="11" 
                        placeholder="88998462350"
                        />
                    </div>

                    <Button variant="slateDefault" 
                    @click="submit" 
                    >Confirmar Agendamento</Button>
                    <Toaster/>
                </div>
            </div>
        </main>
    </div>
</template>