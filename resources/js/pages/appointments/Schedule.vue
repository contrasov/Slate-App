<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
/* import { toast } from 'vue-sonner'; */
import { useToast } from '@/components/ui/toast'
import { type SharedData, type User } from '@/types';

const page = usePage<SharedData>();
const user = page.props.auth.user as User;


const { toast } = useToast();

const copylink = () => {
    const link = route('schedule.public', { token: user.schedule_token });
    navigator.clipboard.writeText(link);
}
</script>

<template>
    <AppLayout>
        <Head title="Agenda" />
        <div class="flex bg-sidebar h-full flex-1 flex-col gap-4 p-4">
            <div class="flex justify-between items-center">
                <h1 class="font-bold" >Agenda de Consultas</h1>
                <div>
                    <Button
                        variant="scheduleLink" @click="() => {
                        toast({
                            title: 'Link copiado',
                            variant: 'defaultSlate'
                        })
                        copylink();
                        }"
                    >
                        Copiar link
                    </Button>
                </div>
            </div>
            <div class="relative min-h-[100vh] bg-white flex-1 rounded-xl border border-sidebar-border dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>