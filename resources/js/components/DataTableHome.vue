<script setup lang="ts">
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
  appointments: Array<{ id: number, patient: {id: number; name: string}, appointment_time: string, appointment_date: string, patient_id: number, status: string, phone: string }>;
}>();

const formatDate = (date: string) => {
  const [year, month, day] = date.split('-');
  return `${day}/${month}/${year}`;
}

const formatTime = (time: string) => {
  const [hours, minutes] = time.split(':');
  return `${hours}:${minutes}h`;
}

const formatPhone = (phone: string) => {
  return phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
}

const openPatient = (appointmentId: number, patientId: number) => {
  router.visit(`${route('patients.service', { id: patientId })}?from=dashboard&appointment_id=${appointmentId}`);
}

/* não sei se é o melhor */
const statusStyle: { [key: string]: string } = {
  'A Confirmar': 'confimarStyle',
  'Confirmado': 'confirmadoStyle',
  'Cancelado': 'canceladoStyle',
  'Finalizado': 'finalizadoStyle',
  'Não Compareceu': 'naoCompareceuStyle'
}

</script>

<template>
    <div class="flex flex-col gap-4 p-4 sm:p-6">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-base font-semibold tracking-tight text-darkTextPrincipal1">Próximas consultas</h2>
                <p class="text-sm text-muted-foreground">Acompanhe os atendimentos da sua agenda.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <Table class="min-w-[700px]">
                <TableHeader>
                    <TableRow>
                        <TableHead>Horário</TableHead>
                        <TableHead>Data</TableHead>
                        <TableHead>Nome completo</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Telefone</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-if="appointments.length === 0">
                        <TableCell colspan="5" class="h-28 text-center text-sm text-muted-foreground">
                            Nenhuma consulta encontrada.
                        </TableCell>
                    </TableRow>
                    <TableRow
                        v-for="appointment in appointments"
                        :key="appointment.id"
                        tabindex="0"
                        :aria-label="`Abrir atendimento de ${appointment.patient.name}`"
                        class="cursor-pointer transition-colors hover:bg-muted/30 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-greenPrincipal1"
                        @click="openPatient(appointment.id, appointment.patient_id)"
                        @keydown.enter.prevent="openPatient(appointment.id, appointment.patient_id)"
                        @keydown.space.prevent="openPatient(appointment.id, appointment.patient_id)"
                    >
                        <TableCell>{{ formatTime(appointment.appointment_time) }}</TableCell>
                        <TableCell>{{ formatDate(appointment.appointment_date) }}</TableCell>
                        <TableCell>{{ appointment.patient.name }}</TableCell>
                        <TableCell>
                            <p class="flex flex-row items-center gap-2" :class="statusStyle[appointment.status]">
                                <span class="w-2 h-2 rounded-full"
                                    :class="{
                                        'bg-[#4A4AFF]': appointment.status === 'A Confirmar',
                                        'bg-green-500': appointment.status === 'Confirmado' || appointment.status === 'Finalizado',
                                        'bg-red-500': appointment.status === 'Cancelado' || appointment.status === 'Não Compareceu'
                                    }"
                                ></span>
                                {{ appointment.status }}
                            </p>
                        </TableCell>
                        <TableCell>{{ formatPhone(appointment.phone) }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
