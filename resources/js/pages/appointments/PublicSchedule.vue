<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';

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
    <main class="mx-[50vh] h-screen">
        <Head title="Agendamento" />
            <div class="flex flex-1 flex-col gap-4 p-4">
                <h1 class="font-bold" >Agendar Consulta na {X}</h1>
                <div class="flex relative h-fit flex-1 rounded-xl border border-sidebar-border">
                    <Calendar/>
                    <div class="flex flex-col gap-2 w-full p-4">
                        <h1>Horários disponíveis:</h1>
                        <Button variant="slateDefault" v-for="time in freeTime" :key="time.id" >{{ time.time }}</Button>
                    </div>
                </div>
            </div>
    </main>
</template>