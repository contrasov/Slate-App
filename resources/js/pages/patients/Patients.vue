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
import { Trash2, Ellipsis } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import {
  Tabs,
  TabsContent,
  TabsList,
  TabsTrigger,
} from '@/components/ui/tabs'
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import TabHistory from '@/components/TabHistory.vue';
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
                <div class="flex items-center gap-2 mb-4 justify-between">
                    <Input type="text" placeholder="Pesquisar ficha" />
                    <Button variant="outline" class="h-10">Buscar</Button>
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

                                <!-- sheet -->
                                <Sheet class="bg-sidebar">
                                    <SheetTrigger as-child>
                                        <Button size="sm" variant="outline">Ver mais</Button>
                                    </SheetTrigger>
                                    <SheetContent>
                                        <SheetHeader>
                                            <SheetTitle>
                                                Informações do paciente
                                            </SheetTitle>
                                        </SheetHeader>
                                        <SheetDescription class="mt-2 flex flex-col gap-2">
                                            <div class="flex flex-row justify-between items-center rounded-md py-3">
                                                <h1 class="text-xl font-bold text-darkTextPrincipal1">{{patient.name}}</h1>
                                                <div class="flex flex-row gap-4 items-center">
                                                    <DropdownMenu>
                                                        <DropdownMenuTrigger as-child>
                                                            <Ellipsis class="text-gray-400 cursor-pointer hover:text-greenPrincipal1"/>
                                                        </DropdownMenuTrigger>
                                                        <DropdownMenuContent class="flex flex-col gap-2 p-2">
                                                            <DropdownMenuItem class="cursor-pointer hover:bg-gray-100 py-1 px-2 rounded-md">Editar</DropdownMenuItem>
                                                            <DropdownMenuItem @click="deletePatient(patient.id)" class="cursor-pointer hover:bg-gray-100 py-1 px-2 rounded-md">Excluir</DropdownMenuItem>
                                                        </DropdownMenuContent>
                                                    </DropdownMenu>
                                                    <Button variant="slateDefault">Enviar Whatsapp</Button>
                                                </div>
                                            </div>

                                            <div class="flex flex-row items-start justify-between">
                                                <div class="w-[490px]">
                                                    <TabHistory />
                                                </div>
                                                <div class="flex flex-col gap-8 px-4 border-l border-sidebar-border w-[420px]">
                                                    <div class="flex flex-col gap-2">
                                                        <h2 class="font-bold text-darkTextPrincipal1">Informações Pessoais</h2>
                                                        <div class="flex flex-col gap-2">
                                                            <div class="flex flex-row gap-12 justify-between">
                                                                <h3>E-mail</h3>
                                                                <p class="text-darkTextPrincipal1">{{patient.email || 'Não cadastrado'}}</p>
                                                            </div>
    
                                                            <div class="flex flex-row gap-12 justify-between">
                                                                <h3>Telefone</h3>
                                                                <p class="text-darkTextPrincipal1">{{formatPhone(patient.phone) || 'Não cadastrado'}}</p>
                                                            </div>
    
                                                            <div class="flex flex-row gap-12 justify-between">
                                                                <h3>Gênero</h3>
                                                                <p class="text-darkTextPrincipal1">{{patient.gender || 'Não cadastrado'}}</p>
                                                            </div>
    
                                                            <div class="flex flex-row gap-12 justify-between">
                                                                <h3>Data de nascimento</h3>
                                                                <p class="text-darkTextPrincipal1">{{patient.birth_date || 'Não cadastrado'}}</p>
                                                            </div>
    
                                                            <div class="flex flex-row gap-12 justify-between">
                                                                <h3>Endereço</h3>
                                                                <p class="text-darkTextPrincipal1">{{patient.address || 'Não cadastrado'}}</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-col gap-3">
                                                        <h2 class="font-bold text-darkTextPrincipal1">Notas</h2>
                                                        <textarea class="w-full h-[100px] border border-sidebar-border rounded-md p-2" placeholder="Digite uma nota para o paciente"></textarea>
                                                        <Button variant="outline">Salvar nota</Button>
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