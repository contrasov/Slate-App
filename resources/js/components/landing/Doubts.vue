<script setup lang="ts">
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger
} from '@/components/ui/accordion'

const defaultValue = 'item-1'

const accordionItems = [
    {
        value: 'item-1',
        title: 'O que posso organizar no Slate?',
        content: 'O Slate reúne agenda, horários de atendimento, agendamentos públicos e cadastro de pacientes. A disponibilidade pode variar conforme as funções já liberadas na sua conta.'
    },
    { 
        value: 'item-2', 
        title: 'Meus dados e dos meus pacientes estão seguros?', 
        content: 'O Slate está em desenvolvimento. Consulte os termos e a política de privacidade do produto para entender como os dados são tratados antes de registrar informações sensíveis.'
    },
    { 
        value: 'item-3', 
        title: 'Consigo usar o Slate em atendimentos online?', 
        content: 'Você pode usar a agenda para organizar atendimentos presenciais ou online. O Slate não realiza a chamada de vídeo.'
    },
    { 
        value: 'item-4', 
        title: 'Posso personalizar as perguntas da ficha de anamnese?', 
        content: 'As fichas clínicas são um complemento ao fluxo de agendamento. A personalização das perguntas é uma possibilidade para versões futuras.'
    },
    { 
        value: 'item-5', 
        title: 'É possível exportar ou imprimir os dados da anamnese?', 
        content: 'A exportação e impressão de fichas não estão descritas como recursos disponíveis nesta versão.'
    }
]

</script>

<template>
    <section id="duvidas" class="flex flex-col items-center gap-14 w-[81%]">
        <h1 class="text-3xl font-bold text-center">Dúvidas? A gente responde.</h1>

        <Accordion type="single" class="w-full" collapsible :default-value="defaultValue">
            <AccordionItem v-for="item in accordionItems" :key="item.value" :value="item.value">
                <AccordionTrigger>
                <span class="flex flex-row gap-2 font-bold">
                    <span class="w-[3px] bg-greenPrincipal1 rounded-lg"> </span>
                    {{ item.title }}
                </span>
                </AccordionTrigger>
                <AccordionContent class="text-gray-500">
                    {{ item.content }}
                </AccordionContent>
            </AccordionItem>
        </Accordion>

    </section>
</template>
