<script setup lang="ts">
import { CalendarHeatmapByDayType, CalendarHeatmapValue, MonthsArrayType } from '@/types';
import { computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import { t as $t } from '../../helpers/i18n';

interface Props {
    title: string;
    data: CalendarHeatmapByDayType[];
}
const props = defineProps<Props>();

const getDaysInMonth = (month: number, year: number): number => {
    return new Date(year, month, 0).getDate();
};

const months = computed<MonthsArrayType[]>(() => [
    { name: $t('january'), index: 1 },
    { name: $t('february'), index: 2 },
    { name: $t('march'), index: 3 },
    { name: $t('april'), index: 4 },
    { name: $t('may'), index: 5 },
    { name: $t('june'), index: 6 },
    { name: $t('july'), index: 7 },
    { name: $t('august'), index: 8 },
    { name: $t('september'), index: 9 },
    { name: $t('october'), index: 10 },
    { name: $t('november'), index: 11 },
    { name: $t('december'), index: 12 },
]);

const today: Date = new Date();
const thisMonth: number = today.getMonth();
const thisYear: number = today.getFullYear();
const nextYearInMonths = computed<MonthsArrayType[]>(() =>
    thisMonth === 0 ? months.value : [...months.value.slice(thisMonth), ...months.value.slice(0, thisMonth)],
);
const dataMap = new Map<string, number[]>();

props.data.forEach((item) => {
    const key = item.expiry_day.slice(0, 7);
    const day = parseInt(item.expiry_day.slice(8)) - 1;
    if (!dataMap.has(key)) dataMap.set(key, []);
    dataMap.get(key)![day] = item.domain_count;
});

const monthlyData = computed<CalendarHeatmapValue[]>(() =>
    nextYearInMonths.value.map((month: MonthsArrayType) => {
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

const chartOptions = computed(() => ({
    dataLabels: {
        //enabled: false,
    },
    //colors: [props.accentColor],
    plotOptions: {
        heatmap: {
            shadeIntensity: 0.5,
            radius: 0,
            useFillColorAsStroke: true,
            distributed: true,
            colorScale: {
                ranges: [
                    {
                        from: 0,
                        to: 500,
                        name: $t('very_low'),
                        color: '#f5a1a1',//'#dadfff',
                    },
                    {
                        from: 501,
                        to: 900,
                        name: $t('low'),
                        color: '#f28282',//'#b5bfff',
                    },
                    {
                        from: 901,
                        to: 1200,
                        name: $t('medium'),
                        color: '#ef6363',//'#909fff',
                    },
                    {
                        from: 1201,
                        to: 1500,
                        name: $t('high'),
                        color: '#bf4f4f',//'#6b7fff',
                    },
                    {
                        from: 1501,
                        to: 2000,
                        name: $t('extreme'),
                        color: '#8f3b3b',//'#465FFF',
                    },
                ],
            },
        },
    },
}));
</script>
<template>
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 sm:px-6 sm:pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ title }}</h3>
        </div>

        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <div id="chartOne" class="my-5 -ml-5 min-w-[650px] pl-5 xl:min-w-full">
                <VueApexCharts type="heatmap" height="550" :options="chartOptions" :series="monthlyData.slice().reverse()" />
            </div>
        </div>
    </div>
</template>
