<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import Input from '@/components/ui/input/Input.vue';
import { CalendarDays, CheckCircle2, Clock } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
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
    weeklyAvailability: Array<{
        id: number;
        weekday: number;
        start_time: string;
        slot_count: number;
        generated_times: string[];
        available_times: string[];
    }> | null;
    freeDays: string[];
    appointments: Array<{
        id: number;
        appointment_time: string;
        appointment_date: string;
    }> | null;
    bookingSuccess: {
        name: string;
        date: string;
        time: string;
    } | null;
}>();

const selectedDate = ref<DateValue | undefined>(undefined);
const selectedTime = ref<string | null>(null);
const availableTimes = ref<string[]>([]);
const availabilityNotice = ref('');
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
    if (!props.weeklyAvailability || !Array.isArray(props.weeklyAvailability)) return;
    availableDays.value = props.weeklyAvailability
        .filter((schedule) => schedule.available_times?.length > 0)
        .map((schedule) => schedule.weekday);
}

calculateAvailableDays();

const isDateUnavailable = (date: DateValue) => {
    const dateString = date.toString();
    const weekday = new Date(`${dateString}T12:00:00`).getDay();
    const today = new Date();
    const todayString = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
    return dateString < todayString
        || props.freeDays.includes(dateString)
        || !availableDays.value.includes(weekday);
};

const calculateAvailableTimes = (date: DateValue) => {
    const dateString = date.toString();
    const systemWeekday = new Date(`${dateString}T12:00:00`).getDay();
    const schedule = props.weeklyAvailability?.find((item) => item.weekday === systemWeekday);

    if (!schedule || props.freeDays.includes(dateString)) {
        availableTimes.value = [];
        return;
    }

    availableTimes.value = (schedule.available_times ?? []).filter((timeString) => {
        const isScheduled = scheduledAppointments.value.some(appointment => 
            appointment.date === dateString && appointment.time === timeString
        );
        return !isScheduled;
    });
};

const dateSelect = (date: DateValue | undefined) => {
    if (!date) return;

    selectedDate.value = date;
    selectedTime.value = null;
    form.schedule_time = '';

    if (isDateUnavailable(date)) {
        form.date = '';
        availableTimes.value = [];
        const dateString = date.toString();
        const today = new Date();
        const todayString = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

        availabilityNotice.value = dateString < todayString
            ? 'Essa data já passou.'
            : props.freeDays.includes(dateString)
                ? 'Não haverá atendimento nesta data: ela está fechada.'
                : 'Não há atendimento neste dia da semana.';
        return;
    }

    availabilityNotice.value = '';
    form.date = date.toString();
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
        preserveScroll: true,
    });
}

const scheduleAnother = () => router.get(
    route('schedule.public', { token: props.user.schedule_token }),
    {},
    { replace: true },
);

const formatBookingDate = (date: string) => new Date(`${date}T12:00:00`).toLocaleDateString('pt-BR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});

const form = useForm({
    name: '',
    phone: '',
    date: '',
    schedule_time: '',
})

</script>

<template>
    <div class="relative min-h-screen bg-slate-50">
        <Head :title="props.bookingSuccess ? 'Agendamento confirmado' : 'Agendamento'" />
        <div class="absolute inset-x-0 top-0 h-64 bg-[url('/images/ufc.jpg')] bg-cover bg-center">
            <div class="absolute inset-0 bg-slate-950/35"></div>
        </div>

        <main v-if="props.bookingSuccess" class="relative mx-auto flex min-h-screen w-full max-w-3xl items-center px-4 py-10 sm:px-6">
            <section class="w-full rounded-2xl border bg-white p-6 text-center shadow-xl shadow-slate-900/10 sm:p-10">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-greenPrincipal1/10 text-greenPrincipal1">
                    <CheckCircle2 class="h-9 w-9" />
                </div>
                <p class="mt-5 text-sm font-semibold uppercase tracking-[0.16em] text-greenPrincipal1">Solicitação enviada</p>
                <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">Agendamento realizado!</h1>
                <p class="mx-auto mt-3 max-w-lg text-sm leading-6 text-muted-foreground">
                    {{ props.bookingSuccess.name }}, sua solicitação de consulta com {{ user.name }} foi registrada. O profissional entrará em contato para confirmar.
                </p>

                <div class="mx-auto mt-6 grid max-w-md gap-3 rounded-xl bg-slate-50 p-4 text-left sm:grid-cols-2">
                    <div class="flex items-start gap-3">
                        <CalendarDays class="mt-0.5 h-5 w-5 shrink-0 text-greenPrincipal1" />
                        <div>
                            <p class="text-xs text-muted-foreground">Data</p>
                            <p class="mt-0.5 text-sm font-medium capitalize">{{ formatBookingDate(props.bookingSuccess.date) }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <Clock class="mt-0.5 h-5 w-5 shrink-0 text-greenPrincipal1" />
                        <div>
                            <p class="text-xs text-muted-foreground">Horário</p>
                            <p class="mt-0.5 text-sm font-medium">{{ props.bookingSuccess.time }}</p>
                        </div>
                    </div>
                </div>

                <Button variant="slateDefault" class="mt-7" @click="scheduleAnother">Agendar outra consulta</Button>
            </section>
        </main>

        <main v-else class="relative mx-auto flex w-full max-w-5xl flex-col gap-5 px-4 py-8 sm:px-6 lg:py-12">
            <header class="rounded-2xl border border-white/70 bg-white p-5 shadow-lg shadow-slate-900/5 sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-greenPrincipal1">Agendamento online</p>
                <h1 class="mt-1 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">Agende sua consulta com {{ user.name }}</h1>
                <p class="mt-2 text-sm text-muted-foreground">Escolha uma data e um horário disponível para continuar.</p>
            </header>

            <section class="overflow-hidden rounded-2xl border bg-white shadow-lg shadow-slate-900/5">
                <div class="grid lg:grid-cols-[minmax(0,1.1fr)_minmax(18rem,0.9fr)]">
                    <div class="flex justify-center border-b p-3 sm:p-6 lg:border-b-0 lg:border-r">
                        <Calendar
                            class="w-full"
                            :model-value="selectedDate"
                            :available-days="availableDays"
                            :is-date-unavailable="isDateUnavailable"
                            @update:model-value="dateSelect"
                        />
                    </div>

                    <div class="flex min-h-72 flex-col gap-4 p-5 sm:p-6">
                        <div>
                            <h2 class="font-semibold text-slate-900">Horários disponíveis</h2>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ selectedDate ? new Date(selectedDate.year, selectedDate.month - 1, selectedDate.day).toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' }) : 'Selecione uma data no calendário.' }}
                            </p>
                        </div>

                        <InputError :message="form.errors.date" />
                        <div v-if="availableTimes.length" class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-2">
                            <Button
                                v-for="time in availableTimes"
                                :key="time"
                                type="button"
                                variant="slateTime"
                                class="h-10 rounded-lg"
                                :class="selectedTime === time ? 'border-greenPrincipal1 bg-greenPrincipal1 text-white hover:bg-[#00929D] hover:text-white' : ''"
                                :aria-pressed="selectedTime === time"
                                @click="timeSelect(time)"
                            >
                                <Clock class="mr-1.5 h-4 w-4" />{{ time }}
                            </Button>
                        </div>
                        <div v-else class="rounded-xl border border-dashed bg-slate-50 px-4 py-6 text-center text-sm text-muted-foreground">
                            {{ availabilityNotice || (selectedDate ? 'Não há horários disponíveis para essa data.' : 'Os horários serão exibidos após selecionar uma data.') }}
                        </div>
                        <InputError :message="form.errors.schedule_time" />
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border bg-white p-5 shadow-lg shadow-slate-900/5 sm:p-6">
                <div class="flex flex-col gap-2 border-b pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-semibold text-slate-900">Seus dados</h2>
                        <p class="text-sm text-muted-foreground">Preencha seus dados para confirmar o agendamento.</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-slate-700">
                            <CalendarDays class="h-3.5 w-3.5" />
                            {{ selectedDate ? new Date(selectedDate.year, selectedDate.month - 1, selectedDate.day).toLocaleDateString('pt-BR') : 'Data não selecionada' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-slate-700">
                            <Clock class="h-3.5 w-3.5" />{{ selectedTime || 'Horário não selecionado' }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="grid gap-2">
                        <label class="text-sm font-medium" for="name">Nome completo</label>
                        <Input id="name" v-model="form.name" required autocomplete="name" placeholder="Seu nome completo" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium" for="phone">Telefone para contato (WhatsApp)</label>
                        <Input
                            id="phone"
                            v-model="form.phone"
                            @keypress="(event: KeyboardEvent) => !/[0-9]/.test(event.key) && event.preventDefault()"
                            required
                            type="tel"
                            maxlength="11"
                            autocomplete="tel"
                            placeholder="(88) 99999-9999"
                        />
                        <InputError :message="form.errors.phone" />
                    </div>
                </div>

                <div class="mt-5 flex flex-col-reverse gap-3 border-t pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-muted-foreground">Seus dados serão usados para confirmar a consulta.</p>
                    <Button variant="slateDefault" :disabled="form.processing" class="w-full sm:w-auto" @click="submit">
                        {{ form.processing ? 'Confirmando…' : 'Confirmar agendamento' }}
                    </Button>
                </div>
            </section>
        </main>
        <Toaster />
    </div>
</template>
