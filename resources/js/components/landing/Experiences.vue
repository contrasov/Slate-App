<script setup lang="ts">
const reviews = [
    {
        text: '“O Slate facilitou muito a rotina do meu consultório. O agendamento é simples e meus pacientes adoram receber os lembretes.”',
        name: 'Dra. Mariana Souza',
        position: 'Psicóloga',
        img: 'images/Avatar-1.svg'
    },
    {
        text: '“Agora faço toda a anamnese direto do tablet. Economizei papel, tempo e ainda tenho tudo organizado em um só lugar.”',
        name: 'Dr. Lucas Ferreira',
        position: 'Nutricionista',
        img: 'images/Avatar-2.svg'
    },
    {
        text: '“Simples, direto e funcional. O Slate se encaixou perfeitamente na minha rotina de atendimentos online."',
        name: 'Dra. Beatriz Alves',
        position: 'Psicopedagoga',
        img: 'images/Avatar-3.svg'
    }
]

</script>

<template>
    <section class="flex flex-col items-center gap-14">
        <h1 class="text-3xl font-bold w-96 text-center">Experiências de quem confia no <span
                class="text-greenPrincipal1">Slate</span></h1>

        <div class="flex flex-row gap-4">
            <div class="w-[400px] flex flex-col gap-4 text-center p-8 bg-white border border-sidebar-border rounded-md"
                v-for="(review, index) in reviews" :key="index">
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