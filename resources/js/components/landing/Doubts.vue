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
        title: 'O Slate é gratuito?',
        content: 'Sim, o Slate oferece um plano gratuito com todas as funcionalidades essenciais. Novas funções, como personalização de ficha, poderão ter planos específicos no futuro.'
    },
    { 
        value: 'item-2', 
        title: 'Meus dados e dos meus pacientes estão seguros?', 
        content: 'Com certeza. Utilizamos criptografia e boas práticas de segurança para garantir que todos os dados fiquem protegidos e acessíveis apenas por você.'
    },
    { 
        value: 'item-3', 
        title: 'Consigo usar o Slate em atendimentos online?', 
        content: 'Sim! O sistema foi pensado para funcionar bem em qualquer formato de atendimento, seja presencial ou remoto.' 
    },
    { 
        value: 'item-4', 
        title: 'Posso personalizar as perguntas da ficha de anamnese?', 
        content: 'Em breve! A versão 2 do Slate trará a funcionalidade de personalização completa da ficha, adaptando-a à sua área de atuação.' 
    },
    { 
        value: 'item-5', 
        title: 'É possível exportar ou imprimir os dados da anamnese?', 
        content: 'Sim. Você pode visualizar, exportar em PDF ou imprimir as fichas de anamnese sempre que precisar, com poucos cliques.' 
    }
]

</script>

<template>
    <section class="flex flex-col items-center gap-14 w-[81%]">
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