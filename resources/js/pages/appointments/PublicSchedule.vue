<template>
    <div class="min-h-screen bg-gray-100">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-4">Agendamentos de {{ user.name }}</h1>
                    
                    <div v-if="appointments.length > 0" class="space-y-4">
                        <div v-for="appointment in appointments" :key="appointment.id" 
                             class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">{{ appointment.description }}</p>
                                    <p class="text-gray-600">
                                        {{ formatDate(appointment.date) }} às {{ appointment.time }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-else class="text-center py-8 text-gray-500">
                        Nenhum agendamento público disponível.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';

const props = defineProps({
    user: Object,
    appointments: Array
});

const formatDate = (date) => {
    return format(new Date(date), "dd 'de' MMMM 'de' yyyy", { locale: ptBR });
};
</script> 