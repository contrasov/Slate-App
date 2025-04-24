<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import Input from '@/components/ui/input/Input.vue';
import { CalendarDays, Clock } from 'lucide-vue-next';

const freeTime = [
    {
        id: 1,
        time: '09:00h',
    },
    {
        id: 2,
        time: '12:00h',
    },
    {
        id: 3,
        time: '14:30h',
    },
    {
        id: 4,
        time: '15:30h',
    }
]

</script>

<template>
    <div class="relative h-[40vh] bg-[url('/images/ufc.jpg')] bg-cover bg-center">
        <main class="pt-[10vh] md:mx-[50vh] h-full">
            <Head title="Agendamento" />
            <div class="flex flex-1 flex-col gap-4 p-4">
                <div class="bg-white p-4 rounded-xl border border-sidebar-border">
                    <span class="flex gap-1 items-center">
                        <h1 class="font-bold">Agendar na {X}</h1>
                    </span>
                    <h2 class="text-sm md:text-bm">Rua Endereço do local do estabelecimento, algum canto ai, 990099-399, Russas-CE</h2>
                </div>
                <div class="bg-white flex md:flex-row flex-col h-fit rounded-xl border border-sidebar-border">
                    <Calendar/>
                    <div class="flex flex-col gap-2 w-full p-4">
                        <h1>Horários disponíveis:</h1>
                        <Button variant="slateDefault" v-for="time in freeTime" :key="time.id">{{ time.time }}</Button>
                    </div>
                </div>
                <div class="bg-white flex flex-col gap-4 p-4 rounded-xl border border-sidebar-border">
                    <div class="flex flex-col font-medium">
                        <div class="flex flex-row gap-1 text-[13px]">
                            <h1>Agendamento para:</h1>
                            <span class="flex items-center">
                                <CalendarDays class="h-4 p-0" />
                                <h1>(Data)</h1>
                            </span>
                            <span class="flex items-center">
                                <Clock class="h-4 p-0" />
                                <h1>(Hora)</h1>
                            </span>
                        </div>
                        <h3 class="text-[13px] py-1 text-gray-500">Insira suas informações de contato para agendar o atendimento.</h3>
                    </div>
                    
                    <div class="grid gap-2">
                        <label class="text-sm" for="agendamento">Nome completo</label>
                        <Input required />
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm" for="Numero">Número para contato (Whatsapp)</label>
                        <Input required type="tel" />
                    </div>

                    <Button variant="slateDefault">Confirmar Agendamento</Button>
                </div>
            </div>
        </main>
    </div>
</template>