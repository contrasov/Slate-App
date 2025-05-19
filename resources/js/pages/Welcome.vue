<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Logo from '@/components/svg/Logo.vue'
import Benefits from '@/components/landing/Benefits.vue';
import Experiences from '@/components/landing/Experiences.vue';
import Doubts from '@/components/landing/Doubts.vue';

</script>

<template>

    <Head title="Bem-vindo(a)">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex w-full justify-between gap-[20vh] flex-col items-center pt-4 text-darkTextPrincipal1 dark:bg-[#0a0a0a] bg-[url('/public/images/background.svg')] bg-cover">

        <!-- acho que ta errado na semantica -->
        <div class="w-full px-8">
            <header
                class="fixed top-2 left-0 right-0 w-[97%] mx-auto flex flex-row items-center justify-between text-sm bg-white py-2 px-4 rounded-[20px] shadow-xl">

                <Logo class="h-12" />

                <ul class="flex flex-row gap-3 font-bold justify-center lg:pl-14 text-darkTextPrincipal1">
                    <li class="hover:underline cursor-pointer">Benefícios</li>
                    <!-- <li class="hover:underline cursor-pointer">Planos</li> -->
                    <li class="hover:underline cursor-pointer">Experiências</li>
                    <li class="hover:underline cursor-pointer">Dúvidas</li>
                    <li class="hover:underline cursor-pointer">Suporte</li>
                </ul>

                <nav class="flex items-center justify-end gap-2">
                    <Link :href="route('login')"
                        class="inline-block rounded-md border border-stroke1 px-5 py-1.5 text-sm leading-normal text-darkTextPrincipal1 font-medium hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
                    Entrar
                    </Link>
                    <Link :href="route('user.register')"
                        class="inline-block rounded-md bg-greenPrincipal1 hover:bg-[#00929D] px-5 py-1.5 text-sm leading-normal text-white font-medium hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                    Criar Conta
                    </Link>
                </nav>
            </header>
        </div>

        <div class="border w-full flex flex-col items-center gap-24 justify-center text-darkTextPrincipal1">

            <div class="flex flex-col items-center gap-4">
                <h1 class="font-bold text-[64px] w-[80%] text-center font-roboto-flex leading-tight">Anamnese sem
                    complicação. <span class="font-bold text-greenPrincipal1">Agende, colete e evolua. </span></h1>
                <h2 class="font-medium w-[60%] text-center">
                    O Slate centraliza agendamento e ficha de anamnese
                    em uma plataforma intuitiva e pronta para crescer com você.
                </h2>

                <Link :href="route('user.register')"
                    class="inline-block rounded-md bg-greenPrincipal1 hover:bg-[#00929D] px-5 py-1.5 text-sm leading-normal text-white font-medium hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                Crie sua conta grátis
                </Link>
            </div>

            <div>
                <img src="images/Dashboard.svg" alt="Dashboard do sistema Slate">
            </div>

            <Benefits />
            <Experiences />
            <Doubts />

            <footer class="bg-white w-full p-5 flex flex-col gap-8">
                <Logo class="h-8" />

                <div class="flex flex-row justify-between px-6">
                    <div>
                        <h3 class="font-bold mb-3">Suporte e Contato</h3>
                        <ul class="flex flex-col gap-2">
                            <li class="hover:underline cursor-pointer">Contato</li>
                            <li class="hover:underline cursor-pointer">Suporte Técnico</li>
                            <li class="hover:underline cursor-pointer">Feedback</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold mb-3">Social</h3>
                        <ul class="flex flex-col gap-2">
                            <li class="hover:underline cursor-pointer">Twitter / X</li>
                            <li class="hover:underline cursor-pointer">Linkedin</li>
                            <li class="hover:underline cursor-pointer">Instagram</li>
                        </ul>
                    </div>

                </div>

                <div class="flex flex-row justify-between px-6 text-gray-500 text-xs">
                    <p>©2025 DARP · Todos os direitos reservados.</p>

                    <ul class="text-darkTextPrincipal1 flex flex-row gap-2">
                        <li class="hover:underline cursor-pointer">Termos de uso</li>
                        <li class="hover:underline cursor-pointer">Política de Privacidade</li>
                        <li class="hover:underline cursor-pointer">Segurança</li>
                    </ul>
                </div>
            </footer>
        </div>

    </div>

</template>
