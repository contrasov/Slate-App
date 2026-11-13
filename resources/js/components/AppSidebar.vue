<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarTrigger } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { CalendarDays, LayoutGrid, Library, UsersRound } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const overviewNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
];

const careNavItems: NavItem[] = [
    {
        title: 'Fichas',
        href: '/forms',
        icon: Library,
    },
    {
        title: 'Pacientes',
        href: '/patients',
        icon: UsersRound,
    }, 
    {
        title: 'Agenda',
        href: '/schedule',
        icon: CalendarDays,
    }, 
];

</script>

<template>
    <Sidebar collapsible="icon" variant="sidebar" class="border-r-0 bg-transparent">
        <SidebarHeader class="flex-row items-center px-4 pb-6 pt-5 group-data-[collapsible=icon]:flex-col group-data-[collapsible=icon]:gap-2 group-data-[collapsible=icon]:px-2">
            <SidebarMenu class="min-w-0 flex-1 group-data-[collapsible=icon]:w-full group-data-[collapsible=icon]:flex-none">
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="h-12 rounded-xl px-2 hover:bg-transparent">
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
            <SidebarTrigger class="size-8 shrink-0 rounded-md border border-sidebar-border text-muted-foreground hover:bg-muted" />
        </SidebarHeader>

        <!-- os itens da sidebar -->
        <SidebarContent class="gap-5 px-2">
            <NavMain title="Visão geral" :items="overviewNavItems" />
            <NavMain title="Atendimento" :items="careNavItems" />
        </SidebarContent>

        <SidebarFooter class="border-t border-sidebar-border p-3">
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
