<script setup lang="ts">
import type {
  ColumnDef,
  ColumnFiltersState,
  ExpandedState,
  SortingState,
  VisibilityState,
} from '@tanstack/vue-table'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import {
  FlexRender,
  getCoreRowModel,
  getExpandedRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  useVueTable,
} from '@tanstack/vue-table'

const appointments = [
  {
    id: 1,
    horario: '08:00',
    nome: 'João Silva Santos',
    telefone: '(11) 99999-9999',
    status: 'Confirmado'
  },
  {
    id: 2,
    horario: '09:30',
    nome: 'Maria Oliveira Costa',
    telefone: '(11) 98888-8888',
    status: 'Pendente'
  },
  {
    id: 3,
    horario: '11:00',
    nome: 'Pedro Almeida Lima',
    telefone: '(11) 97777-7777',
    status: 'Confirmado'
  },
  {
    id: 4,
    horario: '14:00',
    nome: 'Ana Pereira Souza',
    telefone: '(11) 96666-6666',
    status: 'Cancelado'
  }
]

</script>

<template>
    <div class="flex flex-col p-4 gap-3">
        <div>
            <h1 class="font-medium">Consultas para hoje</h1>
        </div>
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Horário</TableHead>
                    <TableHead>Nome completo</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Telefone</TableHead>
                    <TableHead>Ações</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="appointment in appointments" :key="appointment.id">
                    <TableCell>{{ appointment.horario }}</TableCell>
                    <TableCell>{{ appointment.nome }}</TableCell>
                    <TableCell>{{ appointment.status }}</TableCell>
                    <TableCell>{{ appointment.telefone }}</TableCell>
                    <TableCell>...</TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>