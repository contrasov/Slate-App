<script setup lang="ts">
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { CalendarOff, LoaderCircle, Trash } from 'lucide-vue-next';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import Input from './ui/input/Input.vue';
import Button from './ui/button/Button.vue';
import { useToast } from '@/components/ui/toast';
import { CustomCalendar } from '@/components/ui/calendar';
import type { DateValue } from '@internationalized/date';

type StoredDay = {
    id: number;
    weekday: number;
    start_time: string;
    slot_count: number;
    generated_times: string[];
    available_times: string[];
};

type DayConfig = {
    weekday: number;
    enabled: boolean;
    start_time: string;
    slot_count: number;
    generated_times: string[];
    available_times: string[];
};

type DraftClosedDay = { id: number | null; free_day: string };

const props = defineProps<{
    weeklyAvailability: StoredDay[];
    appointmentDuration: string;
    freeDays: Array<{ id: number; free_day: string }>;
    bookedDates: Record<string, number | string>;
}>();

const weekdays = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
const duration = ref(props.appointmentDuration || '00:30');
const storedByWeekday = new Map(props.weeklyAvailability.map((day) => [day.weekday, day]));
const slotCount = ref(props.weeklyAvailability[0]?.slot_count ?? 8);

const toMinutes = (time: string) => {
    const [hours, minutes] = time.split(':').map(Number);
    return (hours * 60) + minutes;
};

const toTime = (minutes: number) => `${String(Math.floor(minutes / 60)).padStart(2, '0')}:${String(minutes % 60).padStart(2, '0')}`;

const generateTimes = (day: DayConfig) => {
    const appointmentMinutes = toMinutes(duration.value);
    const startMinutes = toMinutes(day.start_time);
    if (!day.start_time || !duration.value || appointmentMinutes < 1 || !day.slot_count) {
        day.generated_times = [];
        day.available_times = [];
        return;
    }

    const generated: string[] = [];
    for (let index = 0; index < day.slot_count; index += 1) {
        const current = startMinutes + (appointmentMinutes * index);
        if (current + appointmentMinutes > 24 * 60) break;
        generated.push(toTime(current));
    }
    day.generated_times = generated;
    day.available_times = [...generated];
};

const days = ref<DayConfig[]>(weekdays.map((_, weekday) => {
    const stored = storedByWeekday.get(weekday);
    return {
        weekday,
        enabled: !!stored,
        start_time: stored?.start_time.slice(0, 5) || '09:00',
        slot_count: stored?.slot_count || 8,
        generated_times: stored?.generated_times ?? [],
        available_times: stored?.available_times ?? [],
    };
}));
const selectedDays = computed(() => days.value.filter((day) => day.enabled));

const scheduleForm = useForm({
    appointment_duration: duration.value,
    availability: [] as Array<Record<string, unknown>>,
    closed_days: [] as string[],
});
const closureForm = useForm({ free_day: '' });
const selectedClosedDate = ref<DateValue>();
const { toast } = useToast();
const draftClosedDays = ref<DraftClosedDay[]>(props.freeDays.map((day) => ({ id: day.id, free_day: day.free_day.slice(0, 10) })));

const toggleDay = (day: DayConfig, enabled: boolean) => {
    day.enabled = enabled;
    if (enabled && day.generated_times.length === 0) generateTimes(day);
};

const regenerateDay = (day: DayConfig) => {
    if (day.enabled) generateTimes(day);
};

const regenerateAll = () => days.value.forEach(regenerateDay);

const applySlotCount = () => {
    const count = Number(slotCount.value);
    if (!Number.isInteger(count) || count < 1 || count > 1440) return;

    days.value.forEach((day) => {
        day.slot_count = count;
        regenerateDay(day);
    });
};

const toggleTime = (day: DayConfig, time: string, enabled: boolean) => {
    if (enabled && !day.available_times.includes(time)) {
        day.available_times = [...day.available_times, time].sort();
    } else if (!enabled) {
        day.available_times = day.available_times.filter((available) => available !== time);
    }
};

const saveAvailability = () => {
    scheduleForm.appointment_duration = duration.value;
    scheduleForm.availability = days.value.map((day) => ({
        weekday: day.weekday,
        enabled: day.enabled,
        start_time: day.start_time,
        slot_count: day.slot_count,
        generated_times: day.generated_times,
        available_times: day.available_times,
    }));
    scheduleForm.closed_days = draftClosedDays.value.map((day) => day.free_day);

    scheduleForm.put(route('schedule.availability.save'), {
        preserveScroll: true,
        onError: (errors) => {
            const message = Object.values(errors)[0];
            toast({
                title: 'Não foi possível salvar',
                description: typeof message === 'string' ? message : 'Confira os dados dos horários e tente novamente.',
                variant: 'destructive',
            });
        },
    });
};

const bookedCount = (date: string) => Number(props.bookedDates[date] ?? 0);

const closeDate = () => {
    if (!closureForm.free_day) return;

    const date = closureForm.free_day;
    if (isAlreadyClosed(date)) return;

    draftClosedDays.value = [...draftClosedDays.value, { id: null, free_day: date }].sort((a, b) => a.free_day.localeCompare(b.free_day));
    toast({
        title: `${formatDate(date)} foi adicionada. Clique em “Salvar tudo” para aplicar.`,
        variant: 'defaultSlate',
    });
    closureForm.reset();
    selectedClosedDate.value = undefined;
};

const selectClosedDate = (date: DateValue | undefined) => {
    selectedClosedDate.value = date;
    closureForm.free_day = date?.toString() ?? '';
    closureForm.clearErrors();
};

const closedDateClass = (date: DateValue) => draftClosedDays.value.some((closedDay) => closedDay.free_day === date.toString())
    ? "relative after:absolute after:bottom-1 after:right-1 after:h-1.5 after:w-1.5 after:rounded-full after:bg-darkTextPrincipal1 after:content-[''] data-[selected]:after:bg-white"
    : '';

const isPastDate = (date: DateValue) => {
    const now = new Date();
    const today = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
    return date.toString() < today;
};

const isAlreadyClosed = (date: string) => draftClosedDays.value.some((closedDay) => closedDay.free_day === date);

const reopenDate = (date: string) => {
    draftClosedDays.value = draftClosedDays.value.filter((closedDay) => closedDay.free_day !== date);
    toast({
        title: `${formatDate(date)} foi removida. Clique em “Salvar tudo” para aplicar.`,
        variant: 'defaultSlate',
    });
};

const formatDate = (date: string) => {
    const normalized = date.slice(0, 10);
    return new Date(`${normalized}T12:00:00`).toLocaleDateString('pt-BR');
};

const isDayInvalid = (day: DayConfig) => day.enabled
    && (!day.start_time || Number(day.slot_count) < 1 || day.generated_times.length !== Number(day.slot_count));
const hasInvalidGeneratedDay = () => !duration.value || toMinutes(duration.value) < 1 || days.value.some(isDayInvalid);
</script>

<template>
    <div class="space-y-6 rounded-2xl border border-sidebar-border/70 bg-white p-4 shadow-sm md:p-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <HeadingSmall title="Horários de atendimento" description="Configure horários semanais e datas fechadas. Salve tudo de uma vez." />
            <Button variant="slateDefault" :disabled="scheduleForm.processing || hasInvalidGeneratedDay()" @click="saveAvailability">
                <LoaderCircle v-if="scheduleForm.processing" class="h-4 w-4 animate-spin" />
                {{ scheduleForm.processing ? 'Salvando…' : 'Salvar tudo' }}
            </Button>
        </div>
        <p v-if="scheduleForm.recentlySuccessful" class="rounded-md border border-greenPrincipal1/30 bg-greenPrincipal1/10 px-3 py-2 text-sm text-darkTextPrincipal1">Horários salvos.</p>

        <div class="grid gap-4 rounded-md bg-muted/40 p-4 sm:grid-cols-[minmax(0,1fr)_12rem] sm:items-end">
            <div class="grid gap-1">
                <label for="appointment_duration" class="text-sm font-medium">Duração média de cada atendimento</label>
                <p class="text-xs text-muted-foreground">A prévia de todos os dias será recalculada com essa duração.</p>
            </div>
            <div class="grid gap-1">
                <Input id="appointment_duration" v-model="duration" type="time" required @input="regenerateAll" />
                <InputError :message="scheduleForm.errors.appointment_duration" />
            </div>
        </div>

        <section class="space-y-3">
            <div>
                <h2 class="text-sm font-medium">Dias de atendimento</h2>
                <p class="text-xs text-muted-foreground">Toque nos dias em que atende. Você pode alterar os horários abaixo.</p>
            </div>
            <div class="flex gap-2 overflow-x-auto pb-1" role="group" aria-label="Selecionar dias de atendimento">
                <button
                    v-for="(day, weekday) in days"
                    :key="day.weekday"
                    type="button"
                    :aria-pressed="day.enabled"
                    :aria-label="`${weekdays[day.weekday]}${day.enabled ? ', selecionado' : ''}`"
                    class="h-10 min-w-12 rounded-full border px-3 text-sm font-medium transition-colors"
                    :class="day.enabled ? 'border-greenPrincipal1 bg-greenPrincipal1 text-white hover:bg-[#00929D]' : 'border-border bg-white text-muted-foreground hover:bg-muted'"
                    @click="toggleDay(day, !day.enabled)"
                >
                    {{ ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'][weekday] }}
                </button>
            </div>
        </section>

        <section class="space-y-3">
            <h2 class="text-sm font-medium">Horários por dia</h2>
            <div class="grid gap-1 rounded-md bg-muted/40 p-4 sm:grid-cols-[minmax(0,1fr)_12rem] sm:items-end">
                <div class="grid gap-1">
                    <label for="global_slot_count" class="text-sm font-medium">Quantidade de horários por dia</label>
                    <p class="text-xs text-muted-foreground">Aplique a mesma quantidade a todos os dias de atendimento.</p>
                </div>
                <Input id="global_slot_count" v-model.number="slotCount" class="h-9" type="number" min="1" max="1440" @change="applySlotCount" />
            </div>
            <div v-if="selectedDays.length" class="divide-y rounded-lg border">
                <div v-for="day in selectedDays" :key="day.weekday" class="space-y-2 p-3 sm:p-4">
                    <div class="grid gap-2 sm:grid-cols-[minmax(8rem,1fr)_9rem] sm:items-center">
                        <h3 class="text-sm font-semibold">{{ weekdays[day.weekday] }}</h3>
                        <label class="grid gap-1 text-xs text-muted-foreground">
                            Início
                            <Input :id="`start-${day.weekday}`" v-model="day.start_time" class="h-9" type="time" @change="regenerateDay(day)" />
                        </label>
                    </div>
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="mr-1 text-xs text-muted-foreground">Horários</span>
                        <label v-for="time in day.generated_times" :key="time" class="inline-flex cursor-pointer items-center gap-1 rounded-full border px-2 py-1 text-xs hover:bg-muted/60">
                            <input class="accent-greenPrincipal1" type="checkbox" :checked="day.available_times.includes(time)" @change="toggleTime(day, time, ($event.target as HTMLInputElement).checked)" />
                            {{ time }}
                        </label>
                        <p v-if="!day.generated_times.length" class="text-xs text-muted-foreground">Informe início, quantidade e duração.</p>
                    </div>
                    <p v-if="isDayInvalid(day)" class="text-xs text-destructive">Essa quantidade não cabe em um único dia.</p>
                </div>
            </div>
            <p v-else class="rounded-lg border border-dashed px-4 py-5 text-sm text-muted-foreground">
                Selecione um ou mais dias acima para configurar a disponibilidade.
            </p>
        </section>
        <InputError :message="scheduleForm.errors.availability" />

        <section class="space-y-5 border-t pt-6">
            <div class="flex items-start gap-3">
                <CalendarOff class="mt-0.5 h-5 w-5 shrink-0 text-muted-foreground" />
                <div>
                    <h2 class="font-medium">Fechar uma data</h2>
                    <p class="text-sm text-muted-foreground">Escolha um dia específico para bloquear novos agendamentos, sem alterar seus horários semanais.</p>
                </div>
            </div>

            <div class="grid overflow-hidden rounded-xl border bg-white lg:grid-cols-[minmax(360px,440px)_minmax(0,1fr)]">
                <div class="flex justify-center border-b p-3 sm:p-5 lg:border-b-0 lg:border-r">
                    <CustomCalendar
                        :model-value="selectedClosedDate"
                        :is-date-unavailable="isPastDate"
                        :custom-day-class="closedDateClass"
                        class="w-full"
                        @update:model-value="selectClosedDate"
                    />
                </div>

                <div class="flex min-h-64 flex-col justify-between gap-6 p-5 sm:p-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Data selecionada</p>
                            <p class="mt-1 text-xl font-semibold">
                                {{ closureForm.free_day ? formatDate(closureForm.free_day) : 'Selecione no calendário' }}
                            </p>
                        </div>

                        <div v-if="closureForm.free_day && isAlreadyClosed(closureForm.free_day)" class="rounded-lg border border-darkTextPrincipal1/20 bg-darkTextPrincipal1/[0.04] p-3 text-sm text-darkTextPrincipal1" role="status">
                            Esta data já está fechada. Você pode reabri-la na lista abaixo; as mudanças serão aplicadas ao salvar tudo.
                        </div>
                        <div v-else-if="closureForm.free_day && bookedCount(closureForm.free_day) > 0" class="rounded-lg border border-darkTextPrincipal1/20 border-l-4 border-l-greenPrincipal1 bg-darkTextPrincipal1/[0.04] p-3 text-sm text-darkTextPrincipal1" role="alert">
                            Há {{ bookedCount(closureForm.free_day) }} consulta(s) agendada(s) nessa data. Elas serão mantidas; o fechamento bloqueia apenas novos agendamentos.
                        </div>
                        <div v-else-if="closureForm.free_day" class="rounded-lg border border-border bg-muted/40 p-3 text-sm text-muted-foreground" role="status">
                            Fechar esta data não altera sua disponibilidade nos outros dias.
                        </div>
                        <p v-else class="text-sm text-muted-foreground">Selecione uma data futura no calendário para ver os detalhes antes de fechar.</p>
                        <InputError :message="closureForm.errors.free_day" />
                    </div>

                    <Button
                        variant="slateDefault"
                        class="w-full sm:w-auto sm:self-start"
                        :disabled="!closureForm.free_day || isAlreadyClosed(closureForm.free_day)"
                        @click="closeDate"
                    >
                        <CalendarOff class="h-4 w-4" />
                        Adicionar data fechada
                    </Button>
                </div>
            </div>
            <p class="flex items-center gap-2 text-xs text-muted-foreground">
                <span class="h-3 w-3 rounded-sm border-2 border-darkTextPrincipal1" aria-hidden="true"></span>
                Contorno escuro indica uma data fechada
            </p>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-medium">Datas fechadas</h3>
                    <span class="text-sm text-muted-foreground">{{ draftClosedDays.length }}</span>
                </div>
                <div v-if="draftClosedDays.length" class="grid gap-2 sm:grid-cols-2">
                    <div v-for="closedDay in draftClosedDays" :key="closedDay.free_day" class="flex items-center justify-between gap-3 rounded-lg border bg-white p-3">
                        <div class="min-w-0">
                            <p class="font-medium">{{ formatDate(closedDay.free_day) }}</p>
                            <p v-if="bookedCount(closedDay.free_day)" class="mt-0.5 text-xs text-muted-foreground">
                                {{ bookedCount(closedDay.free_day) }} consulta(s) continuam agendadas
                            </p>
                            <p v-else class="mt-0.5 text-xs text-muted-foreground">Sem consultas existentes</p>
                        </div>
                        <Button variant="outline" size="sm" @click="reopenDate(closedDay.free_day)">
                            <Trash class="h-4 w-4" />
                            Reabrir
                        </Button>
                    </div>
                </div>
                <p v-else class="rounded-lg border border-dashed px-4 py-6 text-center text-sm text-muted-foreground">
                    Nenhuma data fechada. As datas adicionadas serão salvas junto com os horários.
                </p>
            </div>
        </section>
    </div>
</template>
