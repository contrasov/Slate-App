<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ChevronDown, ArrowLeft, ClipboardPlus } from 'lucide-vue-next';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { router } from '@inertiajs/vue3';
import TabHistory from '@/components/TabHistory.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    patient: {
        name: string;
        phone: string;
        email: string;
        birth_date: string;
        gender: string;
        address: string;
        city: string;
        state: string;
        zip_code: string;
        country: string;
    };
    appointments: {
        id: number;
        appointment_time: string;
        appointment_date: string;
        status: string;
    };
}>();

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

const page = usePage();
const from = page.url.includes('from=schedule') ? 'schedule' : 'dashboard';

const goBack = () => {
  if (from === 'dashboard') {
    router.visit(route('dashboard'));
  } else if (from === 'schedule') {
    router.visit(route('schedule'));
  } else {
    router.visit('/'); // fallback
  }
}

</script>

<template>
    <AppLayout>
        <Head title="Atendimento" />
        <div class="flex bg-sidebar h-full flex-1 flex-col gap-4 p-4">
            <span class="flex flex-row gap-3 items-center">
                <ArrowLeft class="w-4 h-4 cursor-pointer text-gray-400 hover:text-greenPrincipal1" @click="goBack()" />
                <h1 class="text-[15px] text-darkTextPrincipal1 font-bold">Atendimento do paciente</h1>
                <p class="flex flex-row items-center gap-2" :class="statusStyle[appointments.status]">
                          <span class="w-2 h-2 rounded-full"
                          :class="{
                            'bg-[#4A4AFF]': appointments.status === 'A Confirmar',
                            'bg-green-500': appointments.status === 'Confirmado',
                            'bg-red-500': appointments.status === 'Cancelado'
                          }"
                        ></span>
                       {{ appointments.status }}</p>
            </span>
            <div class="bg-white p-4 rounded-md border border-sidebar-border flex flex-row justify-between">
                <div class="grid grid-cols-4">
                    <div class="flex flex-col gap-1 min-w-[50vh]">
                        <label for="name" class="text-sm text-gray-400">Nome Completo</label>
                        <h1 class="text-[15px] text-darkTextPrincipal1 font-bold">{{ patient.name || 'N/A' }}</h1>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        <label for="name" class="text-sm text-gray-400">Número de Telefone</label>
                        <h1 class="text-[15px] text-darkTextPrincipal1 font-bold">{{ formatPhone(patient.phone) || 'N/A' }}</h1>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="name" class="text-sm text-gray-400">E-mail</label>
                        <h1 class="text-[15px] text-darkTextPrincipal1 font-bold">{{ patient.email || 'N/A' }}</h1>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="name" class="text-sm text-gray-400">Data de Nascimento</label>
                        <h1 class="text-[15px] text-darkTextPrincipal1 font-bold">{{ patient.birth_date || 'N/A' }}</h1>
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        <label for="name" class="text-sm text-gray-400">Gênero</label>
                        <h1 class="text-[15px] text-darkTextPrincipal1 font-bold">{{ patient.gender || 'N/A' }}</h1>
                    </div>
                    
                    <div class="flex flex-col gap-1 min-w-[62vh]">
                        <label for="name" class="text-sm text-gray-400">Endereço</label>
                        <h1 class="text-[15px] text-darkTextPrincipal1 font-bold">{{ patient.address || 'N/A' }}</h1>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <Button variant="slateDefault" >
                        <ClipboardPlus class="w-4 h-4" />
                        Gerar Ficha
                    </Button>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button variant="filter" class="flex flex-row items-center justify-center gap-2">
                                Alterar Status
                                <ChevronDown class="w-4 h-4" />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="w-44">
                            <DropdownMenuItem>Confirmado</DropdownMenuItem>
                            <DropdownMenuItem>Cancelado</DropdownMenuItem>
                            <DropdownMenuItem>Não Compareceu</DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <Button variant="filter" >Encerrar Antedimento</Button>
                </div>

            </div>
            <div class="bg-white p-4 h-[84vh] rounded-md border border-sidebar-border">
                <TabHistory />
            </div>
        </div>
    </AppLayout>
</template>