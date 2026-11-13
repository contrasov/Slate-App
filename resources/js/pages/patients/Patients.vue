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
        <main class="min-h-full flex-1 bg-[#f5f7f6] p-4 sm:p-6 xl:p-8">
            <div class="mx-auto flex w-full max-w-[1440px] flex-col gap-6">
                <header class="space-y-1">
                    <p class="text-sm font-medium text-greenPrincipal1">Atendimento</p>
                    <h1 class="text-2xl font-semibold tracking-tight text-darkTextPrincipal1 sm:text-3xl">Pacientes</h1>
                    <p class="text-sm text-muted-foreground">Consulte e gerencie as informações dos seus pacientes.</p>
                </header>
                <section class="overflow-hidden rounded-2xl border border-sidebar-border/70 bg-white p-4 shadow-sm sm:p-6">
                    <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <Input type="text" placeholder="Pesquisar paciente" class="sm:max-w-sm" />
                        <Button variant="outline" class="h-10 self-start sm:self-auto">Buscar</Button>
                </div>
                    <div class="overflow-x-auto">
                <Table class="min-w-[800px]">
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
                        <TableRow v-if="patients.length === 0">
                            <TableCell colspan="6" class="h-28 text-center text-sm text-muted-foreground">Nenhum paciente encontrado.</TableCell>
                        </TableRow>
                        <TableRow v-for="patient in patients" :key="patient.id" class="transition-colors hover:bg-muted/30">
                            <TableCell>#{{patient.id}}</TableCell>
                            <TableCell>{{patient.name}}</TableCell>
                            <TableCell>{{formatPhone(patient.phone)}}</TableCell>
                            <TableCell>{{patient.email || 'Não cadastrado'}}</TableCell>
                            <TableCell>{{patient.birth_date || 'Não cadastrado'}}</TableCell>
                            <TableCell>

                                <!-- sheet -->
                                <Sheet>
                                    <SheetTrigger as-child>
                                        <Button size="sm" variant="outline">Ver mais</Button>
                                    </SheetTrigger>
                                    <SheetContent class="w-full overflow-y-auto sm:max-w-5xl">
                                        <SheetHeader>
                                            <SheetTitle>
                                                Informações do paciente
                                            </SheetTitle>
                                        </SheetHeader>
                                        <SheetDescription class="mt-4 flex flex-col gap-5">
                                            <div class="flex flex-col items-start justify-between gap-3 rounded-xl border bg-muted/20 p-4 sm:flex-row sm:items-center">
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

                                            <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,0.8fr)]">
                                                <div class="min-w-0">
                                                    <TabHistory />
                                                </div>
                                                <div class="flex min-w-0 flex-col gap-8 border-t border-sidebar-border pt-5 lg:border-l lg:border-t-0 lg:py-1 lg:pl-5">
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
                </section>
            </div>
        </main>
    </AppLayout>
</template>
