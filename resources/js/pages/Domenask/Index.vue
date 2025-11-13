<script setup lang="ts">
import MonthlySale from '@/components/ecommerce/MonthlySale.vue';
import MonthlyTarget from '@/components/ecommerce/MonthlyTarget.vue';
import RecentOrders from '@/components/ecommerce/RecentOrders.vue';
import StatisticsChart from '@/components/ecommerce/StatisticsChart.vue';
import AdminLayout from '@/components/layout/AdminLayout.vue';
import useApi from '@/composables/useApi';
import { onMounted } from 'vue';
import CrawlingInfo from './CrawlingInfo.vue';

const { data, get, loading, error } = useApi();

onMounted(async () => {
    await get('./test_data.json');
    console.log(data.value);
});
</script>
<template>
    <AdminLayout>
        <div class="grid grid-cols-12 gap-4 md:gap-6" v-if="data">
            <div class="col-span-12 space-y-6 xl:col-span-7">
                <CrawlingInfo :latest="data?.loads_info.latest_load" :previous="data?.loads_info.previous_load" />
                <MonthlySale title="Monthly Saleeees" :data="data" />
            </div>
            <div class="col-span-12 xl:col-span-5">
                <MonthlyTarget />
            </div>

            <div class="col-span-12">
                <StatisticsChart />
            </div>

            <div class="col-span-12 xl:col-span-12">
                <RecentOrders />
            </div>
        </div>
    </AdminLayout>
</template>
