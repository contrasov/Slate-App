<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { type SharedData, type User } from '@/types';
import DataTableHome from '@/components/DataTableHome.vue';
import Attendance from '@/components/kpis/Attendance.vue';
import Consultations from '@/components/kpis/Consultations.vue';
import FormsCompleted from '@/components/kpis/FormsCompleted.vue';
import FormsPending from '@/components/kpis/FormsPending.vue';
import { ArrowUpRight, CalendarDays } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const appointments = page.props.appointments as Array<{ 
    id: number, 
    patient: {
        id: number; 
        name: string
    }, 
    appointment_time: string, 
    appointment_date: string, 
    patient_id: number, 
    status: string, 
    phone: string 
}>;

const amountAppointments = page.props.amountAppointments as number;
const completedAppointments = page.props.completedAppointments as number;

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <main class="min-h-full flex-1 bg-[#f5f7f6] p-4 sm:p-6 xl:p-8">
            <div class="mx-auto flex w-full max-w-[1440px] flex-col gap-6">
                <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-greenPrincipal1">Painel</p>
                        <h1 class="text-2xl font-semibold tracking-tight text-darkTextPrincipal1 sm:text-3xl">Visão geral</h1>
                        <p class="text-sm text-muted-foreground">Bem-vindo(a), {{ user.name }}. Acompanhe sua rotina de atendimentos.</p>
                    </div>
                    <Link :href="route('schedule')" class="inline-flex h-10 items-center justify-center gap-2 self-start rounded-lg bg-greenPrincipal1 px-4 text-sm font-medium text-darkTextPrincipal1 shadow-sm transition-colors hover:bg-greenPrincipal1/85 sm:self-auto">
                        <CalendarDays class="size-4" />
                        Abrir agenda
                        <ArrowUpRight class="size-4" />
                    </Link>
                </header>

                <section class="overflow-hidden rounded-2xl border border-sidebar-border/70 bg-white shadow-sm" aria-label="Indicadores">
                    <div class="grid divide-y divide-sidebar-border/70 lg:grid-cols-4 lg:divide-y-0">
                        <article class="relative min-h-40 overflow-hidden p-4 sm:p-5 lg:after:absolute lg:after:right-0 lg:after:top-1/4 lg:after:h-1/2 lg:after:w-px lg:after:bg-sidebar-border/70 lg:after:content-['']">
                            <Attendance :completed-appointments="completedAppointments" />
                        </article>
                        <article class="relative min-h-40 overflow-hidden p-4 sm:p-5 lg:after:absolute lg:after:right-0 lg:after:top-1/4 lg:after:h-1/2 lg:after:w-px lg:after:bg-sidebar-border/70 lg:after:content-['']">
                            <Consultations :amountAppointments="amountAppointments" />
                        </article>
                        <article class="relative min-h-40 overflow-hidden p-4 sm:p-5 lg:after:absolute lg:after:right-0 lg:after:top-1/4 lg:after:h-1/2 lg:after:w-px lg:after:bg-sidebar-border/70 lg:after:content-['']">
                            <FormsCompleted />
                        </article>
                        <article class="min-h-40 overflow-hidden p-4 sm:p-5">
                            <FormsPending />
                        </article>
                    </div>
                </section>

                <section class="min-h-0 overflow-hidden rounded-2xl border border-sidebar-border/70 bg-white shadow-sm" aria-label="Próximas consultas">
                    <DataTableHome :appointments="appointments" />
                </section>
            </div>
        </main>
    </AppLayout>
</template>
