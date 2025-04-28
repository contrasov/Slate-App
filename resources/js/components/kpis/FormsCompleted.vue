<script setup lang="ts">
import { ClipboardPenLine, Info } from 'lucide-vue-next';
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
    data: [[1, 4], [2, 30], [3, 2], [4, 10]]
}]);
</script>

<template>
    <div class="flex flex-col">
        <div class="flex items-center gap-1">
            <span class="flex flex-row items-center gap-1">
                <ClipboardPenLine class="text-[#10B981] h-4 w-4"/>
                <h1 class="font-medium text-[15px]">Fichas preenchidas</h1>    
            </span>
            <Info class="w-3 h-3 text-gray-400" />
        </div>

        <div class="flex items-center gap-2">
            <h1 class="text-[40px] font-bold">260</h1>
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