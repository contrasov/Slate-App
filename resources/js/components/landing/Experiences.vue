<script setup lang="ts">
const highlights = [
    {
        text: 'Organize os horários de atendimento e visualize os agendamentos em uma agenda central.',
        name: 'Agenda de consultas',
        position: 'Configure sua disponibilidade',
        img: 'images/Avatar-1.svg'
    },
    {
        text: 'Compartilhe um link público para que pacientes solicitem horários disponíveis.',
        name: 'Link para agendamento',
        position: 'Facilite o primeiro contato',
        img: 'images/Avatar-2.svg'
    },
    {
        text: 'Mantenha os dados de contato dos pacientes e complemente o acompanhamento com fichas clínicas.',
        name: 'Cadastro de pacientes',
        position: 'Fichas como recurso complementar',
        img: 'images/Avatar-3.svg'
    }
]

</script>

<template>
    <section class="flex flex-col items-center gap-14">
        <h1 class="text-3xl font-bold w-96 text-center">Recursos para organizar sua rotina no <span
                class="text-greenPrincipal1">Slate</span></h1>

        <div class="flex flex-row gap-4">
            <div class="w-[400px] flex flex-col gap-4 text-center p-8 bg-white border border-sidebar-border rounded-md"
                v-for="(review, index) in highlights" :key="index">
                <p>{{ review.text }}</p>
                <div class="flex flex-col items-center">
                    <img :src="review.img" alt="Médica Psicóloga">
                    <p class="text-darkTextPrincipal1 font-bold">{{ review.name }}</p>
                    <p>{{ review.position }}</p>
                </div>
            </div>
        </div>

    </section>
</template>
