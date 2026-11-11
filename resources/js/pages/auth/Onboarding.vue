<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import AuthBase from '@/layouts/AuthLayout.vue';
import { formatBrazilianPhone } from '@/lib/phone';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps<{ accountType: 'professional' | 'clinic' }>();
const weekdays = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
const form = useForm({
    public_name: '',
    phone: '',
    specialty: '',
    timezone: 'America/Fortaleza',
    duration: '00:30',
    work_times: weekdays.map((_, weekday) => ({ weekday, start_time: '', end_time: '', enabled: false })),
});

const formatPhoneInput = () => {
    form.phone = formatBrazilianPhone(form.phone);
};

const submit = () => {
    form.transform((data) => {
        const workTimes = data.work_times
            .filter((period) => period.enabled)
            .map(({ weekday, start_time, end_time }) => ({ weekday, start_time, end_time }));

        return {
            ...data,
            work_times: workTimes,
            duration: workTimes.length ? data.duration : null,
        };
    }).post(route('onboarding.store'));
};
</script>

<template>
    <AuthBase wide title="Configure sua conta" description="Essas informações ajudam seus pacientes a encontrar e agendar com você.">
        <Head title="Configuração inicial" />
        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="rounded-md bg-muted p-3 text-sm text-muted-foreground">
                {{ props.accountType === 'clinic' ? 'Perfil de consultório ou clínica' : 'Perfil de profissional autônomo' }}
            </div>

            <div class="grid gap-8 lg:grid-cols-2">
                <section class="space-y-4">
                    <div>
                        <h2 class="font-medium">Informações do perfil</h2>
                        <p class="text-sm text-muted-foreground">Dados que serão exibidos para seus pacientes.</p>
                    </div>
                    <div class="grid gap-2">
                        <Label for="public_name">Nome público</Label>
                        <Input id="public_name" v-model="form.public_name" required placeholder="Como você quer aparecer para seus pacientes" />
                        <InputError :message="form.errors.public_name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="phone">Telefone de contato</Label>
                        <Input id="phone" v-model="form.phone" @input="formatPhoneInput" type="tel" required autocomplete="tel" placeholder="(00) 00000-0000" />
                        <InputError :message="form.errors.phone" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="specialty">Área de atuação</Label>
                        <Input id="specialty" v-model="form.specialty" required placeholder="Ex.: Psicologia" />
                        <InputError :message="form.errors.specialty" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="timezone">Fuso horário</Label>
                        <select id="timezone" v-model="form.timezone" class="h-10 rounded-md border border-input bg-background px-3 text-sm">
                            <option value="America/Fortaleza">Fortaleza (UTC−03:00)</option>
                            <option value="America/Sao_Paulo">São Paulo (UTC−03:00)</option>
                            <option value="America/Manaus">Manaus (UTC−04:00)</option>
                            <option value="America/Rio_Branco">Rio Branco (UTC−05:00)</option>
                        </select>
                        <InputError :message="form.errors.timezone" />
                    </div>
                </section>

                <section class="space-y-4 border-t pt-5 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
                    <div>
                        <h2 class="font-medium">Horários de atendimento <span class="text-muted-foreground">(opcional)</span></h2>
                        <p class="text-sm text-muted-foreground">Você pode configurar isso depois. Ative os dias em que atende.</p>
                    </div>
                    <div class="grid gap-3">
                        <div v-for="(day, weekday) in weekdays" :key="day" class="grid grid-cols-[minmax(7rem,1fr)_1fr_1fr] items-center gap-2 text-sm">
                            <div class="flex items-center gap-2">
                                <Switch v-model:checked="form.work_times[weekday].enabled" :aria-label="`Atender ${day}`" />
                                <span>{{ day }}</span>
                            </div>
                            <Input v-model="form.work_times[weekday].start_time" type="time" :aria-label="`Início de ${day}`" />
                            <Input v-model="form.work_times[weekday].end_time" type="time" :aria-label="`Fim de ${day}`" />
                        </div>
                    </div>
                    <div v-if="form.work_times.some((period) => period.enabled)" class="grid gap-2">
                        <Label for="duration">Duração padrão das consultas</Label>
                        <Input id="duration" v-model="form.duration" type="time" required />
                        <InputError :message="form.errors.duration" />
                    </div>
                    <InputError :message="form.errors.work_times" />
                </section>
            </div>

            <Button variant="slateDefault" type="submit" class="w-full" :disabled="form.processing">
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                Concluir configuração
            </Button>
        </form>
    </AuthBase>
</template>
