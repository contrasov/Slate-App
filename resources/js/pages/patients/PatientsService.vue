<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ChevronDown, ArrowLeft, ClipboardPlus, Pencil } from 'lucide-vue-next';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import TabHistory from '@/components/TabHistory.vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from '@/components/ui/toast';

const props = defineProps<{
    patient: {
        id: number;
        name: string;
        phone: string;
        email: string | null;
        birth_date: string | null;
        gender: string | null;
        address: string | null;
        city: string | null;
        state: string | null;
        cep_code: string | null;
        note: string | null;
    };
    appointments: {
        id: number;
        appointment_time: string;
        appointment_date: string;
        status: string;
    };
}>();

const editPatientOpen = ref(false);
const finishConfirmOpen = ref(false);
const isStatusUpdating = ref(false);
const { toast } = useToast();
const patientForm = useForm({
    name: props.patient.name ?? '',
    phone: props.patient.phone ?? '',
    email: props.patient.email ?? '',
    birth_date: props.patient.birth_date?.slice(0, 10) ?? '',
    gender: props.patient.gender ?? '',
    address: props.patient.address ?? '',
    city: props.patient.city ?? '',
    cep_code: props.patient.cep_code ?? '',
    state: props.patient.state ?? '',
});
const noteForm = useForm({ note: props.patient.note ?? '' });

watch(editPatientOpen, (isOpen) => {
    if (!isOpen && !patientForm.processing) {
        patientForm.reset();
        patientForm.clearErrors();
    }
});

const savePatient = () => {
    patientForm.patch(route('patients.update', { id: props.patient.id }), {
        preserveScroll: true,
        onSuccess: () => {
            patientForm.defaults();
            editPatientOpen.value = false;
        },
    });
};

const saveNotes = () => {
    noteForm.patch(route('patients.update', { id: props.patient.id }), {
        preserveScroll: true,
    });
};

function updateStatus(status: string) {
  if (isStatusUpdating.value || (status === 'Finalizado' && props.appointments.status === 'Finalizado')) return;

  isStatusUpdating.value = true;
  router.post(
    route('patient.status', { id: props.appointments.id }),
    { status },
    {
      preserveScroll: true,
      onSuccess: () => { finishConfirmOpen.value = false; },
      onError: () => toast({
        title: 'Não foi possível atualizar a consulta',
        description: 'Confira a conexão e tente novamente.',
        variant: 'defaultSlate',
      }),
      onFinish: () => { isStatusUpdating.value = false; },
    }
  );
}

const statusStyle: { [key: string]: string } = {
  'A Confirmar': 'confimarStyle',
  'Confirmado': 'confirmadoStyle',
  'Cancelado': 'canceladoStyle',
  'Finalizado': 'finalizadoStyle',
  'Não Compareceu': 'naoCompareceuStyle'
}

const formatPhone = (phone: string) => {
    return phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
}

const formatBirthDate = (birthDate: string | null) => {
    if (!birthDate) return 'N/A';
    const [year, month, day] = birthDate.slice(0, 10).split('-');
    return `${day}/${month}/${year}`;
}

const page = usePage();
const from = page.url.includes('from=schedule') ? 'schedule' : 'dashboard';

const goBack = () => {
  if (from === 'dashboard') {
    router.visit(route('dashboard'));
  } else if (from === 'schedule') {
    router.visit(route('schedule'));
  } else {
    router.visit('/');
  }
}

</script>

<template>
    <AppLayout>
        <Head title="Atendimento" />
        <main class="min-h-full flex-1 bg-[#f5f7f6] p-4 sm:p-6 xl:p-8">
            <div class="mx-auto flex w-full max-w-[1440px] flex-col gap-6">
                <header class="flex items-center gap-3">
                    <button type="button" aria-label="Voltar" class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground" @click="goBack()">
                        <ArrowLeft class="size-4" />
                    </button>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-greenPrincipal1">Pacientes</p>
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-2xl font-semibold tracking-tight text-darkTextPrincipal1">Atendimento do paciente</h1>
                            <p class="flex flex-row items-center gap-2" :class="statusStyle[appointments.status]">
                                <span class="h-2 w-2 rounded-full"
                                    :class="{
                                        'bg-[#4A4AFF]': appointments.status === 'A Confirmar',
                                        'bg-green-500': appointments.status === 'Confirmado' || appointments.status === 'Finalizado',
                                        'bg-red-500': appointments.status === 'Cancelado' || appointments.status === 'Não Compareceu',
                                    }"
                                ></span>
                                {{ appointments.status }}
                            </p>
                        </div>
                    </div>
                </header>
                <section class="grid gap-6 rounded-2xl border border-sidebar-border/70 bg-white p-4 shadow-sm sm:p-6 xl:grid-cols-[minmax(0,1fr)_auto]">
                    <div class="grid min-w-0 gap-x-6 gap-y-5 sm:grid-cols-2 xl:grid-cols-3">
                        <div class="flex min-w-0 flex-col gap-1">
                            <label class="text-sm text-muted-foreground">Nome completo</label>
                            <p class="break-words font-semibold text-darkTextPrincipal1">{{ patient.name || 'N/A' }}</p>
                        </div>
                        <div class="flex min-w-0 flex-col gap-1">
                            <label class="text-sm text-muted-foreground">Número de telefone</label>
                            <p class="break-words font-semibold text-darkTextPrincipal1">{{ formatPhone(patient.phone) || 'N/A' }}</p>
                        </div>
                        <div class="flex min-w-0 flex-col gap-1">
                            <label class="text-sm text-muted-foreground">E-mail</label>
                            <p class="break-words font-semibold text-darkTextPrincipal1">{{ patient.email || 'N/A' }}</p>
                        </div>
                        <div class="flex min-w-0 flex-col gap-1">
                            <label class="text-sm text-muted-foreground">Data de nascimento</label>
                            <p class="font-semibold text-darkTextPrincipal1">{{ formatBirthDate(patient.birth_date) }}</p>
                        </div>
                        <div class="flex min-w-0 flex-col gap-1">
                            <label class="text-sm text-muted-foreground">Gênero</label>
                            <p class="font-semibold text-darkTextPrincipal1">{{ patient.gender || 'N/A' }}</p>
                        </div>
                        <div class="flex min-w-0 flex-col gap-1">
                            <label class="text-sm text-muted-foreground">Endereço</label>
                            <p class="break-words font-semibold text-darkTextPrincipal1">{{ patient.address || 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="flex w-full flex-col items-stretch gap-2 border-t border-sidebar-border/70 pt-4 xl:w-56 xl:border-l xl:border-t-0 xl:pl-6 xl:pt-0">
                        <Dialog v-model:open="editPatientOpen">
                            <DialogTrigger as-child>
                                <Button variant="outline" class="w-full"><Pencil class="h-4 w-4" />Editar dados</Button>
                            </DialogTrigger>
                            <DialogContent class="max-h-[90vh] overflow-y-auto sm:max-w-3xl">
                                <DialogHeader>
                                    <DialogTitle>Editar dados do paciente</DialogTitle>
                                    <DialogDescription>Atualize as informações cadastrais de {{ patient.name }}.</DialogDescription>
                                </DialogHeader>
                                <form class="space-y-5" @submit.prevent="savePatient">
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="grid gap-2">
                                            <Label for="patient-name">Nome completo</Label>
                                            <Input id="patient-name" v-model="patientForm.name" required autocomplete="name" />
                                            <InputError :message="patientForm.errors.name" />
                                        </div>
                                        <div class="grid gap-2">
                                            <Label for="patient-phone">Telefone</Label>
                                            <Input id="patient-phone" v-model="patientForm.phone" type="tel" required autocomplete="tel" />
                                            <InputError :message="patientForm.errors.phone" />
                                        </div>
                                        <div class="grid gap-2 sm:col-span-2">
                                            <Label for="patient-email">E-mail</Label>
                                            <Input id="patient-email" v-model="patientForm.email" type="email" autocomplete="email" />
                                            <InputError :message="patientForm.errors.email" />
                                        </div>
                                        <div class="grid gap-2">
                                            <Label for="patient-birth-date">Data de nascimento</Label>
                                            <Input id="patient-birth-date" v-model="patientForm.birth_date" type="date" />
                                            <InputError :message="patientForm.errors.birth_date" />
                                        </div>
                                        <div class="grid gap-2">
                                            <Label for="patient-gender">Gênero</Label>
                                            <Input id="patient-gender" v-model="patientForm.gender" />
                                            <InputError :message="patientForm.errors.gender" />
                                        </div>
                                        <div class="grid gap-2 sm:col-span-2">
                                            <Label for="patient-address">Endereço</Label>
                                            <Input id="patient-address" v-model="patientForm.address" autocomplete="street-address" />
                                            <InputError :message="patientForm.errors.address" />
                                        </div>
                                        <div class="grid gap-2">
                                            <Label for="patient-city">Cidade</Label>
                                            <Input id="patient-city" v-model="patientForm.city" autocomplete="address-level2" />
                                            <InputError :message="patientForm.errors.city" />
                                        </div>
                                        <div class="grid gap-2">
                                            <Label for="patient-state">Estado</Label>
                                            <Input id="patient-state" v-model="patientForm.state" autocomplete="address-level1" />
                                            <InputError :message="patientForm.errors.state" />
                                        </div>
                                        <div class="grid gap-2">
                                            <Label for="patient-cep">CEP</Label>
                                            <Input id="patient-cep" v-model="patientForm.cep_code" autocomplete="postal-code" />
                                            <InputError :message="patientForm.errors.cep_code" />
                                        </div>
                                    </div>
                                    <DialogFooter>
                                        <Button type="button" variant="outline" @click="editPatientOpen = false">Cancelar</Button>
                                        <Button type="submit" variant="slateDefault" :disabled="patientForm.processing">
                                            {{ patientForm.processing ? 'Salvando…' : 'Salvar alterações' }}
                                        </Button>
                                    </DialogFooter>
                                </form>
                            </DialogContent>
                        </Dialog>
                        <Button variant="slateDefault" class="w-full"><ClipboardPlus class="h-4 w-4" />Gerar ficha</Button>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child class="w-full">
                                <Button variant="outline" class="flex w-full flex-row items-center justify-center gap-2" :disabled="isStatusUpdating || appointments.status === 'Finalizado'">
                                    Alterar status
                                    <ChevronDown class="h-4 w-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent class="w-44">
                                <DropdownMenuItem @click="() => updateStatus('Confirmado')">Confirmado</DropdownMenuItem>
                                <DropdownMenuItem @click="() => updateStatus('Cancelado')">Cancelado</DropdownMenuItem>
                                <DropdownMenuItem @click="() => updateStatus('Não Compareceu')">Não Compareceu</DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                        <Dialog v-model:open="finishConfirmOpen">
                            <DialogTrigger as-child>
                                <Button class="w-full" variant="outline" :disabled="isStatusUpdating || appointments.status === 'Finalizado'">
                                    Encerrar atendimento
                                </Button>
                            </DialogTrigger>
                            <DialogContent class="sm:max-w-md">
                                <DialogHeader>
                                    <DialogTitle>Tem certeza que deseja encerrar?</DialogTitle>
                                    <DialogDescription>
                                        Ao confirmar, a consulta será marcada como finalizada e passará a contar no total de atendimentos.
                                    </DialogDescription>
                                </DialogHeader>
                                <DialogFooter>
                                    <Button type="button" variant="outline" :disabled="isStatusUpdating" @click="finishConfirmOpen = false">Cancelar</Button>
                                    <Button type="button" variant="slateDefault" :disabled="isStatusUpdating" @click="updateStatus('Finalizado')">
                                        {{ isStatusUpdating ? 'Encerrando…' : 'Sim, encerrar' }}
                                    </Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>
                    </div>
                </section>
                <section class="rounded-2xl border border-sidebar-border/70 bg-white p-4 shadow-sm sm:p-6">
                    <div class="mb-4 space-y-1">
                        <h2 class="font-semibold text-darkTextPrincipal1">Notas do paciente</h2>
                        <p class="text-sm text-muted-foreground">Registre observações importantes para acompanhar este paciente.</p>
                    </div>
                    <form class="space-y-3" @submit.prevent="saveNotes">
                        <textarea
                            v-model="noteForm.note"
                            class="min-h-28 w-full resize-y rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-greenPrincipal1"
                            placeholder="Escreva uma nota para o paciente"
                        ></textarea>
                        <InputError :message="noteForm.errors.note" />
                        <div class="flex flex-wrap items-center gap-3">
                            <Button type="submit" variant="slateDefault" :disabled="noteForm.processing">
                                {{ noteForm.processing ? 'Salvando…' : 'Salvar notas' }}
                            </Button>
                            <p v-if="noteForm.recentlySuccessful" class="text-sm text-muted-foreground" role="status">Notas salvas.</p>
                        </div>
                    </form>
                </section>
                <section class="min-h-[55vh] rounded-2xl border border-sidebar-border/70 bg-white p-4 shadow-sm sm:p-6">
                    <TabHistory />
                </section>
            </div>
        </main>
    </AppLayout>
</template>
