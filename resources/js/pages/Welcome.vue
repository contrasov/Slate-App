<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Logo from '@/components/svg/Logo.vue'
</script>

<template>
    <Head title="Bem-vindo(a)">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen justify-between gap-[20vh] flex-col items-center p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:p-8 bg-[url('/public/images/background.svg')] bg-cover">
        <header class="w-full flex flex-row items-center justify-between text-sm bg-white py-2 px-3 rounded-[20px] shadow-xl">

            <Logo class="h-12" />

            <ul class="flex flex-row gap-3 font-bold justify-center lg:pl-14 text-darkTextPrincipal1">
                <li class="hover:underline cursor-pointer">Lorem ipsum</li>
                <li class="hover:underline cursor-pointer">Lorem ipsum</li>
                <li class="hover:underline cursor-pointer">Lorem ipsum</li>
            </ul>

            <nav class="flex items-center justify-end gap-2">
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-md border border-stroke1 px-5 py-1.5 text-sm leading-normal text-darkTextPrincipal1 font-medium hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Entrar
                    </Link>
                    <Link
                        :href="route('user.register')"
                        class="inline-block rounded-md bg-greenPrincipal1 hover:bg-[#00929D] px-5 py-1.5 text-sm leading-normal text-white font-medium hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Criar Conta
                    </Link>
            </nav>
        </header>
        <div class="w-full flex flex-col items-center gap-16 justify-center text-darkTextPrincipal1">
            <div class="flex flex-col items-center gap-4">
                <h1 class="font-bold text-[64px] w-[80%] text-center font-roboto-flex leading-tight" >Lorem ipsum dolor sit ament Aut vero ratione aut dolor</h1>
                <h2 class="font-bold w-[70%] text-center" >Et possimus molestias sit dolor obcaecati quo quibusdam 
                    pariatur eum quam enim ut possimus praesentium qui consequatur eius.
                </h2>
                <button class="inline-block rounded-md bg-greenPrincipal1 px-5 py-1.5 text-sm leading-normal text-white font-medium" >Lorem ipsum</button>
            </div>
            <div class="bg-white w-[1060px] h-[322px] rounded-lg shadow-2xl">
                <!-- imagem exemplo do sistema -->
            </div>
        </div>
        <div class="h-14.5 hidden lg:block"></div>
            
    </div>

</template>
