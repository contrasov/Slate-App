<script setup lang="ts">
import { Calendar, Info } from 'lucide-vue-next';
import { ref } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

const chartOptions = ref({
    chart: {
        type: 'area',
        toolbar: {
            show: false
        },
        sparkline: {
            enabled: true
        }
    },
    stroke: {
        curve: 'smooth',
        width: 2,
        colors: ['#1DD496'],
        lineCap: 'round'
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 0.3,
            opacityFrom: 0.4,
            opacityTo: 0.1,
            stops: [0, 90, 100]
        },
        colors: ['#1DD496']
    },
    grid: {
        show: false,
        padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
        }
    },
    xaxis: {
        labels: {
            show: false
        },
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false
        },
        type: 'numeric',
        tickAmount: 5
    },
    yaxis: {
        show: false,
        min: 0,
        max: 40,
        tickAmount: 5,
        forceNiceScale: true
    },
    tooltip: {
        enabled: false
    },
    markers: {
        size: 0
    },
    smooth: true,
    animations: {
        enabled: true,
        speed: 300,
        animateGradually: {
            enabled: false
        }
    },
    dataLabels: {
        enabled: false
    }
});

const series = ref([{
    data: [[1, 4], [2, 15], [3, 8], [4, 34]]
}]);

const props = defineProps<{
    amountAppointments: number;
}>();

</script>

<template>
    <div class="flex min-w-0 flex-col gap-2">
        <div class="flex items-center gap-1">
            <span class="flex flex-row items-center gap-1">
                <Calendar class="text-[#1DD496] h-4 w-4"/>
                <p class="text-sm font-medium text-muted-foreground">Consultas agendadas</p>
            </span>
            <Info class="w-3 h-3 text-gray-400" />
        </div>

        <div class="flex items-center gap-2">
            <p class="text-3xl font-semibold tracking-tight text-darkTextPrincipal1">{{props.amountAppointments}}</p>
        </div>

        <div class="mt-auto h-12 w-full overflow-hidden">
            <VueApexCharts
                type="area"
                :options="chartOptions"
                :series="series"
                height="48px"
                width="100%"
            />
        </div>
    </div>
</template>
