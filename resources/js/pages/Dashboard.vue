<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { type SharedData, type User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import DataTableHome from '@/components/DataTableHome.vue';
import Attendance from '@/components/kpis/Attendance.vue';
import Consultations from '@/components/kpis/Consultations.vue';
import FormsCompleted from '@/components/kpis/FormsCompleted.vue';
import FormsPending from '@/components/kpis/FormsPending.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;
const appointments = page.props.appointments as Array<{ id: number, patient: {id: number; name: string}, appointment_time: string, appointment_date: string, patient_id: number, status: string, phone: string }>;


</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex bg-sidebar h-full flex-1 flex-col gap-4 p-4">
            <h1 class="font-bold" >Bem-vindo(a), {{ user.name }} 👋🏼</h1>
            <div class="grid auto-rows-min gap-2 md:grid-cols-4">
                <div class="bg-white p-4 relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Attendance />
                </div>
                <div class="bg-white p-4 relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <Consultations />
                </div>
                <div class="bg-white p-4 relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <FormsCompleted />
                </div>
                <div class="bg-white p-4 relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <FormsPending />
                </div>
            </div>
            <div class="relative min-h-[100vh] bg-white flex-1 rounded-xl border border-sidebar-border dark:border-sidebar-border md:min-h-min">
                <DataTableHome :appointments="appointments"/>
            </div>
        </div>
    </AppLayout>
</template>
