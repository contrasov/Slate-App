<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import Input from '@/components/ui/input/Input.vue';
import { CalendarDays, Clock } from 'lucide-vue-next';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { CalendarDate, DateValue } from '@internationalized/date';

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
}>();

console.log('Schedules: ', props.schedules);
const selectedDate = ref<DateValue | undefined>(undefined);
const selectedTime = ref<string | null>(null);
const availableTimes = ref<string[]>([]);

const calculateAvailableTimes = (date: DateValue) => {
    const jsDate = new Date(date.toString());
    const jsWeekday = jsDate.getDay();
    const systemWeekday = (jsWeekday) % 7;
    
    console.log('Schedules disponíveis:', props.schedules);

    if(!props.schedules || !Array.isArray(props.schedules)){
        console.log('Nenhuma schedule disponivel');
        availableTimes.value = [];
        return;
    }
    
    const schedule = props.schedules.find(s => s.weekday === systemWeekday);

    if(!schedule){
        console.log('Nenhum horário encontrado para este dia');
        availableTimes.value = [];
        return;
    }

    console.log('Horário encontrado:', schedule);

    const start = new Date(`2000-01-01T${schedule.start_time}`);
    const end = new Date(`2000-01-01T${schedule.end_time}`);
    const duration = parseInt(schedule.duration.split(':')[0]) * 60 + parseInt(schedule.duration.split(':')[1]);

    const times: string[] = [];
    let current = start;

    while(current < end){
        times.push(current.toTimeString().slice(0, 5));
        current.setMinutes(current.getMinutes() + duration);
    }

    availableTimes.value = times;
};

const dateSelect = (date: DateValue | undefined) => {
    if (!date) return;

    selectedDate.value = date;
    const jsDate = new Date(date.toString());
    form.date = jsDate.toISOString().split('T')[0];
    
    console.log('Data selecionada:', jsDate);
    
    const jsWeekday = jsDate.getDay();
    form.weekday = (jsWeekday) % 7;
    
    console.log('Dia da semana (Sistema):', form.weekday);
    
    calculateAvailableTimes(date);
};

const timeSelect = (time: string) => {
    selectedTime.value = time;
    form.time = time;
}

const submit = () => {
    if (!selectedDate.value || !selectedTime.value) {
        alert('Por favor, selecione uma data e um horário');
        return;
    }

    form.post(route('appointments.store'), {
        onSuccess: () => {
            form.reset();
            selectedDate.value = undefined;
            selectedDate.value = undefined;
            alert('Agendamento realizado com sucesso!');
        },
        onError: (errors) => {
            alert('Erro ao agendar. Por favor, tente novamente.');
        }
    });
}

const form = useForm({
    user_id: props.user.id,
    name: '',
    phone: '',
    date: '',
    time: '',
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
                    <Calendar @update:modelValue="dateSelect" />
                    <div class="flex flex-col gap-2 w-full p-4">
                        <h1>Horários disponíveis:</h1>
                        <Button
                            v-for="time in availableTimes"
                            :key="time"
                            variant="slateDefault"
                            @click="timeSelect(time)"
                            :class="{ 'bg-primary' : selectedTime == time }"
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
                        <Input v-model="form.name" required />
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm" for="phone">Número para contato (Whatsapp)</label>
                        <Input v-model="form.phone" required type="tel" />
                    </div>

                    <Button variant="slateDefault" @click="submit">Confirmar Agendamento</Button>
                </div>
            </div>
        </main>
    </div>
</template>