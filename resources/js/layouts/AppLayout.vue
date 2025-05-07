<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
/* import { Toaster } from '@/components/ui/sonner' */
import Toaster from '@/components/ui/toast/Toaster.vue'


interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- <Toaster position="bottom-right" /> -->
        <Toaster />
        <slot />
    </AppLayout>
</template>
