<script setup lang="ts">
import LineChart from '@/components/ecommerce/LineChart.vue';
import MonthlyTarget from '@/components/ecommerce/MonthlyTarget.vue';
import RecentOrders from '@/components/ecommerce/RecentOrders.vue';
import StatisticsChart from '@/components/ecommerce/StatisticsChart.vue';
import AdminLayout from '@/components/layout/AdminLayout.vue';
import useApi from '@/composables/useApi';
import { CrawlingDurationType, DownloadSpeedType, NumberOfDomainType } from '@/types';
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
            <div class="col-span-12 space-y-6 xl:col-span-12">
                <CrawlingInfo
                    :latest="data?.loads_info.latest_load"
                    :previous="data?.loads_info.previous_load"
                    :avgSpeed="data?.loads_info.average_download_speed_bytes_per_second"
                    :avgDuration="data?.loads_info.crawling_average_duration_seconds"
                    :avgDomainLength="data?.avg_domain_name_length"
                    :longestDomainLength="data?.longest_domain_name_length"
                />
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 xl:gap-6">
                    <LineChart
                        title="Domain count over time"
                        :data="data?.loads_info.number_of_domains_over_time.map((item: NumberOfDomainType) => item.domain_count)"
                        dataTitle="Number of domains"
                        :labels="data?.loads_info.number_of_domains_over_time.map((item: NumberOfDomainType) => item.date_created)"
                    />
                    <LineChart
                        title="Crawling duration over time (seconds)"
                        :data="
                            data?.loads_info.crawling_duration_over_time.map((item: CrawlingDurationType) =>
                                item.crawling_duration_seconds.toFixed(4),
                            )
                        "
                        dataTitle="Crawling duration (s)"
                        :labels="data?.loads_info.crawling_duration_over_time.map((item: CrawlingDurationType) => item.date_created)"
                    />
                    <LineChart
                        title="Download speed over time (MB/s)"
                        :data="
                            data?.loads_info.download_speed_over_time.map((item: DownloadSpeedType) =>
                                Math.round(item.download_speed_bytes_per_second / 1024 / 1024),
                            )
                        "
                        dataTitle="Download speed (MB/s)"
                        :labels="data?.loads_info.download_speed_over_time.map((item: DownloadSpeedType) => item.date_created)"
                    />
                </div>
            </div>
            <div class="col-span-12 xl:col-span-12">
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
