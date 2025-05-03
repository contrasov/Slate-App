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
import { Button } from '@/components/ui/button';

const props = defineProps<{
  appointments: Array<{ id: number, patient: {id: number; name: string}, appointment_time: string, appointment_date: string, status: string, phone: string }>;
}>();

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('pt-BR');
}

const formatTime = (time: string) => {
  const [hours, minutes] = time.split(':');
  return `${hours}:${minutes}h`;
}

const formatPhone = (phone: string) => {
  return phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
}


</script>

<template>
    <div class="flex flex-col p-4 gap-3">
        <div>
            <h1 class="font-medium">Proximas consultas</h1>
        </div>
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Horário</TableHead>
                    <TableHead>Data</TableHead>
                    <TableHead>Nome completo</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Telefone</TableHead>
                    <TableHead>Ações</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="appointment in appointments" :key="appointment.id">
                    <TableCell>{{ formatTime(appointment.appointment_time) }}</TableCell>
                    <TableCell>{{ formatDate(appointment.appointment_date) }}</TableCell>
                    <TableCell>{{ appointment.patient.name }}</TableCell>
                    <TableCell>{{ appointment.status }}</TableCell>
                    <TableCell>{{ formatPhone(appointment.phone) }}</TableCell>
                    <TableCell>
                        <Button size="sm" variant="filter">
                          Ver mais
                        </Button>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>