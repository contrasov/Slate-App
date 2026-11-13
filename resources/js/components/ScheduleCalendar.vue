<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ArrowUpRight, CalendarDays, ChevronLeft, ChevronRight, Clock3 } from 'lucide-vue-next';

const props = defineProps<{
    appointments: Array<{
        id: number;
        patient: { id: number; name: string };
        appointment_time: string;
        appointment_date: string;
        patient_id: number;
        status: string;
        phone: string;
    }>;
    weeklyAvailability: Array<{ weekday: number; available_times?: string[] | null }>;
    freeDays: Array<{ free_day: string }>;
}>();

const weekdays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
const currentDate = ref(new Date());

const toDateKey = (date: Date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const today = toDateKey(new Date());
const selectedDate = ref<string | null>(today);
const month = computed(() => currentDate.value.toLocaleDateString('pt-BR', { month: 'long' }));
const year = computed(() => currentDate.value.getFullYear());
const monthKey = computed(() => `${currentDate.value.getFullYear()}-${String(currentDate.value.getMonth() + 1).padStart(2, '0')}`);
const monthlyAppointments = computed(() => props.appointments
    .filter((appointment) => appointment.appointment_date.slice(0, 10).startsWith(monthKey.value))
    .length);

const daysInMonth = computed(() => {
    return new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 0).getDate();
});

const startDayOfWeek = computed(() => {
    return new Date(currentDate.value.getFullYear(), currentDate.value.getMonth(), 1).getDay();
});

const daysGrid = computed<Array<number | null>>(() => {
    const grid: Array<number | null> = Array(startDayOfWeek.value).fill(null);
    for (let day = 1; day <= daysInMonth.value; day += 1) grid.push(day);
    return grid;
});

const selectedAppointments = computed(() => props.appointments
    .filter((appointment) => appointment.appointment_date.slice(0, 10) === selectedDate.value)
    .sort((a, b) => a.appointment_time.localeCompare(b.appointment_time)));
const selectedWeekday = computed(() => selectedDate.value
    ? new Date(`${selectedDate.value}T12:00:00`).getDay()
    : null);
const selectedAvailability = computed(() => props.weeklyAvailability.find((day) => day.weekday === selectedWeekday.value));
const selectedDateIsClosed = computed(() => selectedDate.value !== null
    && props.freeDays.some((day) => day.free_day.slice(0, 10) === selectedDate.value));
const freeTimes = computed(() => {
    if (!selectedDate.value || selectedDateIsClosed.value) return [];

    const occupiedTimes = new Set(selectedAppointments.value
        .filter((appointment) => appointment.status !== 'Cancelado')
        .map((appointment) => appointment.appointment_time.slice(0, 5)));

    return (selectedAvailability.value?.available_times ?? [])
        .filter((time) => !occupiedTimes.has(time.slice(0, 5)))
        .sort((a, b) => a.localeCompare(b));
});

const formatTime = (time: string) => {
    const [hours, minutes] = time.split(':');
    return `${hours}:${minutes}`;
};

const selectDay = (day: number | null) => {
    if (!day) return;

    selectedDate.value = toDateKey(new Date(currentDate.value.getFullYear(), currentDate.value.getMonth(), day));
};

const isSameDay = (date: string, day: number | null) => {
    if (!day) return false;
    return date.slice(0, 10) === toDateKey(new Date(currentDate.value.getFullYear(), currentDate.value.getMonth(), day));
};

const appointmentsForDay = (day: number | null) => day
    ? props.appointments.filter((appointment) => isSameDay(appointment.appointment_date, day))
    : [];

const isWorkingDay = (day: number | null) => {
    if (!day) return false;
    const date = toDateKey(new Date(currentDate.value.getFullYear(), currentDate.value.getMonth(), day));
    if (props.freeDays.some((freeDay) => freeDay.free_day.slice(0, 10) === date)) return false;
    const weekday = new Date(`${date}T12:00:00`).getDay();
    return props.weeklyAvailability.some((availability) => availability.weekday === weekday && (availability.available_times?.length ?? 0) > 0);
};

const changeMonth = (offset: number) => {
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + offset, 1);
    if (selectedDate.value && !selectedDate.value.startsWith(`${currentDate.value.getFullYear()}-${String(currentDate.value.getMonth() + 1).padStart(2, '0')}`)) {
        selectedDate.value = null;
    }
};

const statusClasses = (status: string) => ({
    'bg-greenPrincipal1/15 text-darkTextPrincipal1': status === 'Confirmado' || status === 'Finalizado',
    'bg-amber-100 text-amber-800': status === 'A Confirmar' || status === 'Pendente',
    'bg-muted text-muted-foreground': status === 'Cancelado' || status === 'Não Compareceu',
});
</script>

<template>
    <div class="grid min-w-0 grid-cols-1 items-start gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(14rem,18rem)] lg:gap-6">
        <section class="min-w-0" aria-label="Calendário mensal">
            <div class="flex items-center justify-between gap-4 px-2 py-3 sm:px-4">
                <button type="button" aria-label="Mês anterior" class="flex size-9 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground" @click="changeMonth(-1)">
                    <ChevronLeft class="size-5" />
                </button>
                <div class="text-center">
                    <h2 class="text-lg font-semibold capitalize text-darkTextPrincipal1">{{ month }} {{ year }}</h2>
                    <p class="text-xs text-muted-foreground">{{ monthlyAppointments }} {{ monthlyAppointments === 1 ? 'consulta no mês' : 'consultas no mês' }}</p>
                </div>
                <button type="button" aria-label="Próximo mês" class="flex size-9 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground" @click="changeMonth(1)">
                    <ChevronRight class="size-5" />
                </button>
            </div>

            <div class="grid grid-cols-7 text-center text-xs font-medium text-muted-foreground">
                <div v-for="(weekday, index) in weekdays" :key="index" class="pb-2">{{ weekday }}</div>
            </div>

            <div class="grid grid-cols-7 overflow-hidden rounded-xl border border-sidebar-border/70">
                <button
                    v-for="(day, index) in daysGrid"
                    :key="index"
                    type="button"
                    :disabled="!day"
                    :aria-label="day ? `${day} ${month} ${year}, ${appointmentsForDay(day).length} consulta(s)` : undefined"
                    :aria-pressed="day ? selectedDate === toDateKey(new Date(currentDate.getFullYear(), currentDate.getMonth(), day)) : undefined"
                    class="relative flex min-h-20 w-full flex-col border-b border-r border-sidebar-border/70 bg-white p-1.5 text-left text-xs transition-colors disabled:pointer-events-none sm:min-h-24 sm:p-2 sm:text-sm"
                    :class="day && selectedDate === toDateKey(new Date(currentDate.getFullYear(), currentDate.getMonth(), day)) ? 'bg-greenPrincipal1/10 ring-1 ring-inset ring-greenPrincipal1' : 'hover:bg-muted/30'"
                    @click="selectDay(day)"
                >
                    <span v-if="day" class="font-medium text-darkTextPrincipal1">{{ day }}</span>
                    <span v-if="isWorkingDay(day)" class="absolute bottom-1.5 right-1.5 size-1.5 rounded-full bg-greenPrincipal1" aria-label="Dia de atendimento" />
                    <div class="mt-1 max-h-16 w-full overflow-y-auto scrollbar-hide sm:max-h-20">
                        <span
                            v-for="appointment in appointmentsForDay(day)"
                            :key="appointment.id"
                            class="mb-1 block truncate rounded-md bg-greenPrincipal1/15 px-1.5 py-1 text-[10px] text-darkTextPrincipal1 sm:text-xs"
                        >
                            {{ formatTime(appointment.appointment_time) }} · {{ appointment.patient.name.split(' ')[0] }}
                        </span>
                    </div>
                </button>
            </div>
        </section>

        <aside class="min-w-0 rounded-2xl border border-sidebar-border/70 bg-white p-4 sm:p-5" aria-live="polite" aria-label="Consultas do dia selecionado">
            <div class="flex items-start justify-between gap-3 border-b border-sidebar-border/70 pb-4">
                <h2 class="min-w-0 text-base font-semibold text-darkTextPrincipal1">Consultas do dia</h2>
                <span v-if="selectedDate" class="shrink-0 rounded-full bg-greenPrincipal1/15 px-2.5 py-1 text-xs font-semibold text-darkTextPrincipal1">
                    {{ selectedAppointments.length }}
                </span>
            </div>

            <section v-if="selectedDate" class="border-b border-sidebar-border/70 py-4" aria-label="Horários vagos">
                <h3 class="text-sm font-semibold text-darkTextPrincipal1">Horários vagos</h3>
                <p v-if="selectedDateIsClosed" class="mt-2 text-sm text-muted-foreground">Este dia está fechado.</p>
                <p v-else-if="!selectedAvailability" class="mt-2 text-sm text-muted-foreground">Sem atendimento neste dia.</p>
                <p v-else-if="freeTimes.length === 0" class="mt-2 text-sm text-muted-foreground">Não há horários vagos.</p>
                <div v-else class="mt-2 flex flex-wrap gap-2">
                    <span v-for="time in freeTimes" :key="time" class="rounded-md bg-greenPrincipal1/10 px-2.5 py-1 text-xs font-medium text-darkTextPrincipal1">
                        {{ formatTime(time) }}
                    </span>
                </div>
            </section>

            <p v-if="!selectedDate" class="mt-5 text-sm text-muted-foreground">
                Clique em uma data no calendário para ver as consultas.
            </p>
            <div v-else-if="selectedAppointments.length" class="mt-3 divide-y divide-sidebar-border/70">
                <Link
                    v-for="appointment in selectedAppointments"
                    :key="appointment.id"
                    :href="`${route('patients.service', { id: appointment.patient_id })}?from=schedule&appointment_id=${appointment.id}`"
                    class="group flex items-center gap-3 py-3 first:pt-1 last:pb-1"
                >
                    <Clock3 class="size-5 shrink-0 text-greenPrincipal1" />
                    <span class="min-w-0 flex-1">
                        <span class="block font-semibold text-darkTextPrincipal1">{{ formatTime(appointment.appointment_time) }}</span>
                        <span class="block truncate text-sm text-muted-foreground">{{ appointment.patient.name }}</span>
                        <span class="mt-1 inline-flex rounded-full px-2 py-0.5 text-[11px] font-medium" :class="statusClasses(appointment.status)">
                            {{ appointment.status }}
                        </span>
                    </span>
                    <ArrowUpRight class="size-4 shrink-0 text-muted-foreground transition-colors group-hover:text-greenPrincipal1" />
                </Link>
            </div>
            <div v-else class="mt-5 flex flex-col items-center rounded-xl border border-dashed border-sidebar-border/70 px-4 py-8 text-center">
                <CalendarDays class="size-6 text-muted-foreground" />
                <p class="mt-2 text-sm font-medium text-darkTextPrincipal1">Nenhuma consulta neste dia</p>
                <p class="mt-1 text-xs text-muted-foreground">Selecione outra data para ver a agenda.</p>
            </div>
        </aside>
    </div>
</template>
