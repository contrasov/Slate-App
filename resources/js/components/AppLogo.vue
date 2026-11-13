<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div class="flex aspect-square size-8 shrink-0 items-center justify-center">
        <AppLogoIcon class="size-6 group-data-[collapsible=icon]:size-5" />
    </div>
    <div class="ml-2 grid min-w-0 flex-1 text-left group-data-[collapsible=icon]:hidden">
        <span class="truncate text-base font-semibold leading-none tracking-tight text-darkTextPrincipal1">Slate App</span>
    </div>
</template>
