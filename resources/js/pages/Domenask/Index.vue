<script setup lang="ts">
import CalendarHeatMap from '@/components/ecommerce/CalendarHeatMap.vue';
import LineChart from '@/components/ecommerce/LineChart.vue';
import MonthlyTarget from '@/components/ecommerce/MonthlyTarget.vue';
import PieChart from '@/components/ecommerce/PieChart.vue';
import RecentOrders from '@/components/ecommerce/RecentOrders.vue';
import StatisticsChart from '@/components/ecommerce/StatisticsChart.vue';
import AdminLayout from '@/components/layout/AdminLayout.vue';
import useApi from '@/composables/useApi';
import {
    CrawlingDurationType,
    DownloadSpeedType,
    NameServerMarketShareType,
    NumberOfDomainType,
    OwnerMarketShareType,
    RegistrarMarketShareType,
} from '@/types';
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
                        type="bar"
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
                        type="bar"
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
                        type="bar"
                    />
                </div>
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-1 xl:gap-6">
                    <CalendarHeatMap title="Number of expiring domains per day" :data="data?.calendar_heatmap_by_day.slice(0, 365)" />
                </div>
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-3 xl:gap-6">
                <div class="grid grid-rows-1 gap-4 xl:grid-rows-2 xl:gap-6">
                    <PieChart
                        title="Domain owner market share"
                        :data="data?.owner_market_share.slice(0, 19).map((item: OwnerMarketShareType) => item.domain_count)"
                        dataTitle="Number of domains"
                        :labels="data?.owner_market_share.slice(0, 19).map((item: OwnerMarketShareType) => item.owner)"
                    />
                    <LineChart
                        title="Domain owner market share"
                        :data="data?.owner_market_share.slice(0, 19).map((item: OwnerMarketShareType) => item.domain_count)"
                        dataTitle="Number of domains"
                        :labels="data?.owner_market_share.slice(0, 19).map((item: OwnerMarketShareType) => item.owner)"
                        type="bar"
                    />
                </div>
                <div class="grid grid-rows-1 gap-4 xl:grid-rows-2 xl:gap-6">
                    <PieChart
                        title="Domain registrar market share"
                        :data="data?.registrar_market_share.slice(0, 19).map((item: RegistrarMarketShareType) => item.domain_count)"
                        dataTitle="Number of domains"
                        :labels="data?.registrar_market_share.slice(0, 19).map((item: RegistrarMarketShareType) => item.registrar)"
                    />
                    <LineChart
                        title="Domain registrar market share"
                        :data="data?.registrar_market_share.slice(0, 19).map((item: RegistrarMarketShareType) => item.domain_count)"
                        dataTitle="Number of domains"
                        :labels="data?.registrar_market_share.slice(0, 19).map((item: RegistrarMarketShareType) => item.registrar)"
                        type="bar"
                    />
                </div>
                <div class="grid grid-rows-1 gap-4 xl:grid-rows-2 xl:gap-6">
                    <PieChart
                        title="Name server market share"
                        :data="data?.name_server_market_share.slice(0, 19).map((item: NameServerMarketShareType) => item.count)"
                        dataTitle="Number of domains"
                        :labels="data?.name_server_market_share.slice(0, 19).map((item: NameServerMarketShareType) => item.ns)"
                    />
                    <LineChart
                        title="Name server market share"
                        :data="data?.name_server_market_share.slice(0, 19).map((item: NameServerMarketShareType) => item.count)"
                        dataTitle="Number of domains"
                        :labels="data?.name_server_market_share.slice(0, 19).map((item: NameServerMarketShareType) => item.ns)"
                        type="bar"
                    />
                </div>
                </div>
            </div>
            <div class="col-span-12 xl:col-span-12">
                <!-- <MonthlyTarget /> -->
            </div>

            <div class="col-span-12">
                <!-- <StatisticsChart /> -->
            </div>

            <div class="col-span-12 xl:col-span-12">
                <!-- <RecentOrders /> -->
            </div>
        </div>
    </AdminLayout>
</template>
