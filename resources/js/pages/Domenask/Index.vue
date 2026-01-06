<script setup lang="ts">
import CalendarHeatMap from '@/components/ecommerce/CalendarHeatMap.vue';
import LineChart from '@/components/ecommerce/LineChart.vue';
import PieChart from '@/components/ecommerce/PieChart.vue';
import AdminLayout from '@/components/layout/AdminLayout.vue';
import {
    CalendarHeatmapByDayType,
    CrawlInfo,
    DomainStatisticsType,
    MetricType,
    NameServerMarketShareType,
    OwnerMarketShareType,
    RegistrarMarketShareType,
} from '@/types';
import CrawlingInfo from './CrawlingInfo.vue';

const props = defineProps<{
    latestCrawling: CrawlInfo;
    previousCrawling: CrawlInfo;
    domainStatistics: DomainStatisticsType;
    numberOfDomainsMetric: MetricType[];
    crawlingDurationMetric: MetricType[];
    downloadSpeedMetric: MetricType[];
    calendarHeatmapByDay: CalendarHeatmapByDayType[];
    ownersMarketShare: OwnerMarketShareType[];
    registrarsMarketShare: RegistrarMarketShareType[];
    nameserverMarketShare: NameServerMarketShareType[];
}>();
</script>
<template>
    <AdminLayout>
        <div class="grid grid-cols-12 gap-4 md:gap-6" v-if="props">
            <div class="col-span-12 space-y-6 xl:col-span-12">
                <h1 class="font-size-3xl text-[var(--color-brand-500)]">Domain statistics overview</h1>
                <CrawlingInfo
                    :latest="latestCrawling"
                    :previous="previousCrawling"
                    :avgSpeed="domainStatistics.average_download_speed_bytes_per_second"
                    :avgDuration="domainStatistics.crawling_average_duration_seconds"
                    :avgDomainLength="domainStatistics.avg_domain_name_length"
                    :longestDomainLength="domainStatistics.longest_domain_name_length"
                />
                <h1 class="font-size-2xl col-span-12 text-[var(--color-brand-500)]">Domain statistics over time</h1>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 xl:gap-6">
                    <LineChart
                        title="Domain count over time"
                        :data="numberOfDomainsMetric.map((item: MetricType) => item.value)"
                        dataTitle="Number of domains"
                        :labels="numberOfDomainsMetric.map((item: MetricType) => item.date)"
                        type="bar"
                    />
                    <LineChart
                        title="Crawling duration over time (seconds)"
                        :data="crawlingDurationMetric.map((item: MetricType) => parseInt(item.value.toFixed(4)))"
                        dataTitle="Crawling duration (s)"
                        :labels="crawlingDurationMetric.map((item: MetricType) => item.date)"
                        type="bar"
                    />
                    <LineChart
                        title="Download speed over time (MB/s)"
                        :data="downloadSpeedMetric.map((item: MetricType) => Math.round(item.value / 1024 / 1024))"
                        dataTitle="Download speed (MB/s)"
                        :labels="downloadSpeedMetric.map((item: MetricType) => item.date)"
                        type="bar"
                    />
                </div>
                <h1 class="font-size-2xl col-span-12 text-[var(--color-brand-500)]">Expiring domains heatmap</h1>
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-1 xl:gap-6">
                    <CalendarHeatMap title="Number of expiring domains (per day) in the next year" :data="calendarHeatmapByDay" />
                </div>
                <h1 class="font-size-2xl col-span-12 text-[var(--color-brand-500)]">Marketshare statistics</h1>
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-3 xl:gap-6">
                    <div class="grid grid-rows-1 gap-4 xl:grid-rows-2 xl:gap-6">
                        <PieChart
                            title="Domain owner market share"
                            :data="ownersMarketShare.slice(0, 19).map((item: OwnerMarketShareType) => item.domain_count)"
                            dataTitle="Number of domains"
                            :labels="ownersMarketShare.slice(0, 19).map((item: OwnerMarketShareType) => item.owner)"
                        />
                        <LineChart
                            title="Domain owner market share"
                            :data="ownersMarketShare.slice(0, 19).map((item: OwnerMarketShareType) => item.domain_count)"
                            dataTitle="Number of domains"
                            :labels="ownersMarketShare.slice(0, 19).map((item: OwnerMarketShareType) => item.owner)"
                            type="bar"
                        />
                    </div>
                    <div class="grid grid-rows-1 gap-4 xl:grid-rows-2 xl:gap-6">
                        <PieChart
                            title="Domain registrar market share"
                            :data="registrarsMarketShare.slice(0, 19).map((item: RegistrarMarketShareType) => item.domain_count)"
                            dataTitle="Number of domains"
                            :labels="registrarsMarketShare.slice(0, 19).map((item: RegistrarMarketShareType) => item.registrar)"
                        />
                        <LineChart
                            title="Domain registrar market share"
                            :data="registrarsMarketShare.slice(0, 19).map((item: RegistrarMarketShareType) => item.domain_count)"
                            dataTitle="Number of domains"
                            :labels="registrarsMarketShare.slice(0, 19).map((item: RegistrarMarketShareType) => item.registrar)"
                            type="bar"
                        />
                    </div>
                    <div class="grid grid-rows-1 gap-4 xl:grid-rows-2 xl:gap-6">
                        <PieChart
                            title="Name server market share"
                            :data="nameserverMarketShare.slice(0, 19).map((item: NameServerMarketShareType) => item.count)"
                            dataTitle="Number of domains"
                            :labels="nameserverMarketShare.slice(0, 19).map((item: NameServerMarketShareType) => item.ns)"
                        />
                        <LineChart
                            title="Name server market share"
                            :data="nameserverMarketShare.slice(0, 19).map((item: NameServerMarketShareType) => item.count)"
                            dataTitle="Number of domains"
                            :labels="nameserverMarketShare.slice(0, 19).map((item: NameServerMarketShareType) => item.ns)"
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
