<script setup lang="ts">
import { ref } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

interface Props {
    title: string;
    labels: string[] | number[];
    data: number[];
    dataTitle: string;
    color?: string;
    accentColor: string;
}
const props = defineProps<Props>();

const series = ref([
    {
        name: props.dataTitle,
        data: props.data,
    },
]);

const chartOptions = ref({
    chart: {
        fontFamily: 'Outfit, sans-serif',
        toolbar: {
            show: false,
        },
    },
    colors: [props.accentColor],
    plotOptions: {
        bar: {
            horizontal: false,
            borderRadius: 5,
        },
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        show: true,
        width: 4,
        colors: ['transparent'],
    },
    xaxis: {
        categories: props.labels,
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
    },
    legend: {
        show: true,
        fontFamily: 'Outfit',
    },
    grid: {
        yaxis: {
            lines: {
                show: true,
            },
        },
    },
    fill: {
        opacity: 1,
    },
    tooltip: {
        x: {
            show: false,
        },
        y: {
            formatter: function (val: number | string) {
                return val.toString();
            },
        },
    },
});
</script>
<template>
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 sm:px-6 sm:pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ title }}</h3>
        </div>

        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <div id="chartOne" class="-ml-5 min-w-full pl-2">
                <VueApexCharts type="bar" height="auto" :options="chartOptions" :series="series" />
            </div>
        </div>
    </div>
</template>
