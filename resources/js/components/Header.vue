<script setup lang="ts">
import { Bell, Plus } from 'lucide-vue-next';
import type { User } from '@/types';
import Button from './ui/button/Button.vue';

interface Props {
    user: User;
}
defineProps<Props>();
</script>

<template>
    <div class="flex justify-between items-center">
        <h1 class="font-bold" >Bem-vindo(a), {{ user.name }} 👋🏼</h1>
        <div class="flex gap-2">
            <Button variant="slateDefault" size="sm">
                <Plus/>
                Nova Consulta
            </Button>
            <div class="flex border border-sidebar-border bg-white p-[5px] rounded-md cursor-pointer items-center">
                <Bell class="h-5"/>
            </div>
        </div>
    </div>
</template>