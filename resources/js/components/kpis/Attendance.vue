<script setup lang="ts">
import { UserRound, Info } from 'lucide-vue-next';
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
        colors: ['#10B981'],
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
        colors: ['#10B981']
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
        tickAmount: 6,
        decimalsInFloat: 0
    },
    yaxis: {
        show: false,
        min: 0,
        max: 1500,
        tickAmount: 5
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
    data: [[1, 20], [2, 900], [3, 200], [4, 1200], [5, 1439]]
}]);
</script>

<template>
    <div class="flex flex-col">
        <div class="flex items-center gap-1">
            <span class="flex flex-row items-center gap-1">
                <UserRound class="text-[#10B981] h-4 w-4"/>
                <h1 class="font-medium text-[15px]">Total de atendimentos</h1>    
            </span>
            <Info class="w-3 h-3 text-gray-400" />
        </div>

        <div class="flex items-center gap-2">
            <h1 class="text-[40px] font-bold">1,439</h1>
            <span class="bg-green-100 text-green-600 px-1 py-1 rounded-md text-sm font-medium">+231</span>
        </div>

        <div class="h-16">
            <VueApexCharts
                type="area"
                :options="chartOptions"
                :series="series"
                height="20px"
                width="220px"
            />
        </div>
    </div>
</template>