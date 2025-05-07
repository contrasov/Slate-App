<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetDescription,
    SheetTrigger,
} from '@/components/ui/sheet'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { defineProps } from 'vue';
import { Trash2 } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    patients: any[]
}>();

const formatPhone = (phone: string) => {
  return phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
}

const deletePatient = (id: number) => {
    router.delete(route('patients.delete', { id }), {
        onSuccess: () => {
            console.log('Paciente deletado com sucesso');
        }
    });
}
</script>

<template>
    <AppLayout>
        <Head title="Pacientes" />
        <div class="flex bg-sidebar h-full flex-1 flex-col gap-4 p-4">
            <h1 class="font-bold" >Lista de Pacientes</h1>
            <div class="bg-white p-4 relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border md:min-h-min">
                <div class="flex items-center gap-2 max-w-md mb-4"> <!-- vai bom mb mesmo -->
                    <Input type="text" placeholder="Pesquisar paciente" />
                    <Button class="h-10" variant="filter">Filtro*</Button>
                </div>
                
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>ID</TableHead>
                            <TableHead>Nome Completo</TableHead>
                            <TableHead>Telefone/Whatsapp</TableHead>
                            <TableHead>E-mail</TableHead>
                            <TableHead>Data de nascimento</TableHead>
                            <TableHead>Ações</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="patient in patients" :key="patient.id">
                            <TableCell>#{{patient.id}}</TableCell>
                            <TableCell>{{patient.name}}</TableCell>
                            <TableCell>{{formatPhone(patient.phone)}}</TableCell>
                            <TableCell>{{patient.email || 'Não cadastrado'}}</TableCell>
                            <TableCell>{{patient.birth_date || 'Não cadastrado'}}</TableCell>
                            <TableCell>
                                <Sheet>
                                    <SheetTrigger as-child>
                                        <Button size="sm" variant="filter">Ver mais</Button>
                                    </SheetTrigger>
                                    <SheetContent>
                                        <SheetHeader>
                                            <SheetTitle>
                                                Informações do paciente
                                            </SheetTitle>
                                        </SheetHeader>
                                        <SheetDescription class="mt-2 flex flex-col gap-2">
                                            <div class="flex flex-col gap-2 border border-sidebar-border p-3 rounded-xl">
                                                <h1>Nome do paciente</h1>
                                                <span class="flex flex-row gap-2 items-center justify-between">
                                                    <p class="text-xl font-bold text-darkTextPrincipal1">{{patient.name}}</p>
                                                    <Trash2 @click="deletePatient(patient.id)" class="w-4 h-4 hover:text-red-500 cursor-pointer" />
                                                </span>
                                            </div>

                                            <h1 class="text-lg font-bold text-darkTextPrincipal1">Informações gerais</h1>
                                            <div class="flex flex-col gap-6 px-5">
                                                <div class="flex flex-row gap-6 justify-between">
                                                    <div class="flex flex-col gap-2">
                                                        <h1>Gênero</h1>
                                                        <p class="text-darkTextPrincipal1">{{patient.gender || 'Neutro' }}</p>
                                                    </div>
                                                    <div class="flex flex-col gap-2">
                                                        <h1>Data de nascimento</h1>
                                                        <p class="text-darkTextPrincipal1">{{patient.birth_date || '06/06/2025' }}</p>
                                                    </div>
                                                    <div class="flex flex-col gap-2">
                                                        <h1>E-mail</h1>
                                                        <p class="text-darkTextPrincipal1">{{patient.email || 'exemplo@email.com' }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex flex-row gap-[46px]">
                                                    <div class="flex flex-col gap-2">
                                                        <h1>Telefone</h1>
                                                        <p class="text-darkTextPrincipal1">{{patient.phone || 'Não cadastrado' }}</p>
                                                    </div>
                                                    <div class="flex flex-col gap-2">
                                                        <h1>Endereço *</h1>
                                                        <p class="text-darkTextPrincipal1">{{patient.address || 'Rua exemplo, 123, Russas - CE' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </SheetDescription>
                                    </SheetContent>
                                </Sheet>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

            </div>
        </div>
    </AppLayout>
</template>