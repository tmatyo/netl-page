<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    title: string;
    labels: string[];
    data: number[];
    dataTitle: string;
    color?: string;
    accentColor: string;
}
const props = defineProps<Props>();

const series = ref(props.data);

const chartOptions = ref({
    labels: props.labels,
    stroke: { width: 0 },
    theme: {
        monochrome: {
            enabled: true,
            color: props.accentColor,
        },
    },
    legend: {
        show: true,
        position: 'bottom',
    },
    datalabels: {
        style: {
            colors: ['#fff'],
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
            <div id="chartOne" class="-ml-5 min-w-[650px] pl-2 xl:min-w-full">
                <apexchart type="pie" height="auto" :options="chartOptions" :series="series" />
            </div>
        </div>
    </div>
</template>
