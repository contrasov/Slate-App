<script setup lang="ts">
import { Trash, Pencil } from 'lucide-vue-next';
import HeadingSmall from '@/components/HeadingSmall.vue';
import Checkbox from './ui/checkbox/Checkbox.vue';
import Input from './ui/input/Input.vue';
import Button from './ui/button/Button.vue';
import { ref, defineProps, computed, defineEmits } from 'vue';
import { useForm } from '@inertiajs/vue3';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

const props = defineProps<{
    schedules: Array<{ id: number; weekday: number; start_time: string; end_time: string; duration: string }>;
}>();

const daysOfWeek = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo'];
const selectedDays = ref<Record<number, boolean>>({});

const availableDays = computed(() => {
const scheduledWeekdays = props.schedules.map(schedule => schedule.weekday);
    return daysOfWeek
        .map((day, index) => ({ day, index }))
        .filter(({ index }) => !scheduledWeekdays.includes(index));
});

const form = useForm({
    work_times: daysOfWeek.map(() => ({
        weekday: 0,
        start_time: '',
        end_time: '',
    })),
    duration: ''
});

const dialogOpen = ref(false);

const formatTime = (event: Event) => {
    const input = event.target as HTMLInputElement;
    let value = input.value.replace(/\D/g, '');

    if (value.length > 4) {
        value = value.slice(0, 4);
    }

    if (value.length >= 3) {
        value = value.slice(0, 2) + ':' + value.slice(2);
    }

    input.value = value;
};

const submit = () => {
    const selectedDayIndices = Object.entries(selectedDays.value)
        .filter(([_, isSelected]) => isSelected)
        .map(([index]) => parseInt(index));

    selectedDayIndices.forEach(dayIndex => {
        form.work_times[dayIndex].weekday = dayIndex;
    });

    form.work_times = form.work_times.filter((_, index) => 
        selectedDayIndices.includes(index)
    );

    form.post(route('work-time.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            dialogOpen.value = false;
            form.reset();
            selectedDays.value = {};
            window.location.reload();  /* melhorar isso, pq ele ta dando é f5 */
        }
    });
};

const deleteSchedule = (id: number) => {
    form.delete(route('schedule.destroy', { id }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            window.location.reload();
        }
    });
};

const formatHour = (time: string) => {
  const [hours, minutes] = time.split(':');
  return `${hours}:${minutes}`;
}


</script>

<template>
    <div>
        <div class="flex items-center justify-between">
            <HeadingSmall title="Horário de Atendimento" description="Defina o seu intervalo de horas da semana." />
            <div class="flex flex-row gap-2">
                <Dialog v-model:open="dialogOpen">
                    <DialogTrigger>
                        <Button variant="outline">Adicionar dia</Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Adicionar Horário</DialogTitle>
                            <DialogDescription>
                                Defina o seu intervalo de horas da semana.
                            </DialogDescription>
                        </DialogHeader>
                        <form @submit.prevent="submit">
                            <div class="flex flex-col gap-2">
                                <div v-for="({day, index}) in availableDays" :key="index" class="flex items-center justify-between">
                                    <div class="flex items-center gap-1">
                                        <Checkbox v-model="selectedDays[index]"/>
                                        <p>{{ day }}</p>
                                    </div>
                                    <div class="flex items-center w-48 gap-2">
                                        <Input 
                                            v-model="form.work_times[index].start_time"
                                            placeholder="00:00h" 
                                            type="text" 
                                            maxlength="5" 
                                            @input="formatTime($event)" 
                                            title="Formato: 00:00h"
                                        />
                                        <Input 
                                            v-model="form.work_times[index].end_time"
                                            placeholder="00:00h" 
                                            type="text" 
                                            maxlength="5" 
                                            @input="formatTime($event)" 
                                            title="Formato: 00:00h"
                                        />
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <HeadingSmall title="Tempo do atendimento" description="Defina duração do seu atendimento para calculo." />
                                    <Input 
                                        v-model="form.duration"
                                        placeholder="00:00h" 
                                        type="text" 
                                        maxlength="5" 
                                        @input="formatTime($event)" 
                                        title="Duração do atendimento: 00:00h"
                                    />
                                </div>

                                <div class="flex items-center gap-4">
                                    <Button variant="slateDefault" type="submit">Salvar intervalos</Button>                               
                                </div>
                            </div>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>
        </div>

        <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-400">
            <div v-for="schedule in schedules" :key="schedule.weekday">
                <div class="flex flex-row gap-3 items-center border border-sidebar-border px-3 py-1 rounded-md">
                    <p>{{ (daysOfWeek[schedule.weekday]).slice(0, 3) }}</p>
                    <div class="flex flex-row gap-3 items-center">
                        <p>{{ formatHour(schedule.start_time) }} - {{ formatHour(schedule.end_time) }}</p>
                        <p>D. Atend. {{ formatHour(schedule.duration) }}</p>
                    </div>
                    <Trash class="h-4 w-4 text-gray-400 hover:cursor-pointer hover:text-red-300" @click="deleteSchedule(schedule.id)"/>
                </div>
            </div>
        </div>
    </div>
</template>