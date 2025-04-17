<script setup lang="ts">
import { Bell } from 'lucide-vue-next';
import type { User } from '@/types';

interface Props {
    user: User;
}
defineProps<Props>();
</script>

<template>
    <div class="flex justify-between items-center">
        <h1 class="font-bold" >Bem-vindo(a), {{ user.name }} 👋🏼</h1>
        <div class="border p-[5px] rounded-md cursor-pointer">
            <Bell class="h-5"/>
        </div>
    </div>
</template>