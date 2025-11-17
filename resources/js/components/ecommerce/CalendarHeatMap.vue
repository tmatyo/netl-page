<script setup lang="ts">
import { CalendarHeatmapByDayType, CalendarHeatmapValue, MonthsArrayType } from '@/types';
import { ref } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

interface Props {
    title: string;
    data: CalendarHeatmapByDayType[];
}
const props = defineProps<Props>();

const getDaysInMonth = (month: number, year: number): number => {
    return new Date(year, month, 0).getDate();
};

const months: MonthsArrayType[] = [
    { name: 'January', index: 1 },
    { name: 'February', index: 2 },
    { name: 'March', index: 3 },
    { name: 'April', index: 4 },
    { name: 'May', index: 5 },
    { name: 'June', index: 6 },
    { name: 'July', index: 7 },
    { name: 'August', index: 8 },
    { name: 'September', index: 9 },
    { name: 'October', index: 10 },
    { name: 'November', index: 11 },
    { name: 'December', index: 12 },
];

const today: Date = new Date();
const thisMonth: number = today.getMonth();
const thisYear: number = today.getFullYear();
const nextYearInMonths: MonthsArrayType[] = thisMonth === 0 ? months : [...months.slice(thisMonth), ...months.slice(0, thisMonth)];
const dataMap = new Map<string, number[]>();

props.data.forEach((item) => {
    const key = item.expiry_day.slice(0, 7);
    const day = parseInt(item.expiry_day.slice(8)) - 1;
    if (!dataMap.has(key)) dataMap.set(key, []);
    dataMap.get(key)![day] = item.domain_count;
});

const monthlyData = ref<CalendarHeatmapValue[]>(
    nextYearInMonths.map((month: MonthsArrayType) => {
        const year: number = month.index <= thisMonth ? thisYear + 1 : thisYear;
        const key: string = `${year}-${String(month.index).padStart(2, '0')}`;
        const daysInMonth: number = getDaysInMonth(month.index, year);
        const monthArray: number[] = Array(daysInMonth).fill(0);

        if (dataMap.has(key)) {
            const fromProps = dataMap.get(key);
            fromProps?.forEach((value, idx) => {
                if (value !== undefined) monthArray[idx] = value;
            });
        }

        return {
            name: month.name,
            data: monthArray,
        };
    }),
);

const chartOptions = ref({
    dataLabels: {
        enabled: false,
    },
    colors: ['#008FFB'],
    plotOptions: {
        heatmap: {
            distributed: true,
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
                <VueApexCharts type="heatmap" height="550" :options="chartOptions" :series="monthlyData.slice().reverse()" />
            </div>
        </div>
    </div>
</template>
