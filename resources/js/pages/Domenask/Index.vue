<script setup lang="ts">
import BarChart from '@/components/ecommerce/BarChart.vue';
import CalendarHeatMap from '@/components/ecommerce/CalendarHeatMap.vue';
import LineChart from '@/components/ecommerce/LineChart.vue';
import PieChart from '@/components/ecommerce/PieChart.vue';
import AdminLayout from '@/components/layout/AdminLayout.vue';
import WordCloud from '@/components/WordCloud.vue';
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
import DomainInfo from './DomainInfo.vue';

const props = defineProps<{
    latestCrawling: CrawlInfo;
    previousCrawling: CrawlInfo;
    domainStatistics: DomainStatisticsType | null;
    numberOfDomainsMetric: MetricType[];
    crawlingDurationMetric: MetricType[];
    downloadSpeedMetric: MetricType[];
    calendarHeatmapByDay: CalendarHeatmapByDayType[];
    ownersMarketShare: OwnerMarketShareType[];
    registrarsMarketShare: RegistrarMarketShareType[];
    nameserverMarketShare: NameServerMarketShareType[];
    expiredDomains: string[];
    newDomains: string[];
}>();
const crawlingInfoKeyword: string = 'agent=007';
const queryParam: string = window.location.search;
const accentColor: string = window.getComputedStyle(document.body).getPropertyValue('--color-brand-text');
</script>
<template>
    <AdminLayout :noData="!domainStatistics">
        <div>
            <h1 class="font-size-3xl brand-text" v-if="domainStatistics || numberOfDomainsMetric.length">{{ $t('domain_statistics') }}</h1>
            <div class="mb-6 grid grid-cols-2 space-y-6 xl:grid-cols-4 xl:space-y-0 xl:space-x-6">
                <DomainInfo
                    v-if="domainStatistics"
                    class="col-span-2"
                    :latest="latestCrawling"
                    :previous="previousCrawling"
                    :avgDomainLength="domainStatistics.avg_domain_name_length"
                    :longestDomainLength="domainStatistics.longest_domain_name_length"
                />
                <LineChart
                    v-if="numberOfDomainsMetric.length"
                    class="col-span-2"
                    :title="$t('domain_count_over_time')"
                    :data="numberOfDomainsMetric.map((item: MetricType) => item.value)"
                    :dataTitle="$t('domain_count')"
                    :labels="numberOfDomainsMetric.map((item: MetricType) => item.date)"
                    :accentColor="accentColor"
                />
            </div>

            <h1 v-if="domainStatistics && queryParam.includes(crawlingInfoKeyword)" class="font-size-2xl brand-text col-span-12">
                {{ $t('crawling_info') }}
            </h1>
            <div v-if="queryParam.includes(crawlingInfoKeyword)" class="grid grid-cols-1 space-y-6">
                <CrawlingInfo
                    v-if="domainStatistics"
                    class="col-span-3"
                    :latest="latestCrawling"
                    :previous="previousCrawling"
                    :avgSpeed="domainStatistics.average_download_speed_bytes_per_second"
                    :avgDuration="domainStatistics.crawling_average_duration_seconds"
                />
                <div class="mb-6 grid grid-cols-1 space-y-6 xl:grid-cols-2 xl:space-y-0 xl:space-x-6">
                    <LineChart
                        v-if="crawlingDurationMetric.length"
                        :title="$t('crawling_duration_over_time')"
                        :data="crawlingDurationMetric.map((item: MetricType) => parseInt(item.value.toFixed(4)))"
                        :dataTitle="$t('crawling_duration_sec')"
                        :labels="crawlingDurationMetric.map((item: MetricType) => item.date)"
                        :accentColor="accentColor"
                    />
                    <LineChart
                        v-if="downloadSpeedMetric.length"
                        :title="$t('download_speed_over_time')"
                        :data="downloadSpeedMetric.map((item: MetricType) => Math.round(item.value / 1024 / 1024))"
                        :dataTitle="$t('download_speed_mbps')"
                        :labels="downloadSpeedMetric.map((item: MetricType) => item.date)"
                        :accentColor="accentColor"
                    />
                </div>
            </div>

            <h1
                class="font-size-2xl brand-text col-span-12"
                v-if="ownersMarketShare.length || registrarsMarketShare.length || nameserverMarketShare.length"
            >
                {{ $t('marketshare_data') }}
            </h1>
            <div class="mb-6 grid grid-cols-1 gap-4 xl:grid-cols-3 xl:gap-6">
                <div class="grid grid-rows-1 gap-4 xl:grid-rows-2 xl:gap-6" v-if="ownersMarketShare.length">
                    <PieChart
                        :title="$t('owner_marketshare')"
                        :data="ownersMarketShare.slice(0, 19).map((item: OwnerMarketShareType) => item.domain_count)"
                        :dataTitle="$t('domain_count')"
                        :labels="ownersMarketShare.slice(0, 19).map((item: OwnerMarketShareType) => item.owner)"
                        :accentColor="accentColor"
                    />
                    <BarChart
                        :title="$t('owner_marketshare')"
                        :data="ownersMarketShare.slice(0, 19).map((item: OwnerMarketShareType) => item.domain_count)"
                        :dataTitle="$t('domain_count')"
                        :labels="ownersMarketShare.slice(0, 19).map((item: OwnerMarketShareType) => item.owner)"
                        :accentColor="accentColor"
                    />
                </div>
                <div class="grid grid-rows-1 gap-4 xl:grid-rows-2 xl:gap-6" v-if="registrarsMarketShare.length">
                    <PieChart
                        :title="$t('registrar_marketshare')"
                        :data="registrarsMarketShare.slice(0, 19).map((item: RegistrarMarketShareType) => item.domain_count)"
                        :dataTitle="$t('domain_count')"
                        :labels="registrarsMarketShare.slice(0, 19).map((item: RegistrarMarketShareType) => item.registrar)"
                        :accentColor="accentColor"
                    />
                    <BarChart
                        :title="$t('registrar_marketshare')"
                        :data="registrarsMarketShare.slice(0, 19).map((item: RegistrarMarketShareType) => item.domain_count)"
                        :dataTitle="$t('domain_count')"
                        :labels="registrarsMarketShare.slice(0, 19).map((item: RegistrarMarketShareType) => item.registrar)"
                        :accentColor="accentColor"
                    />
                </div>
                <div class="grid grid-rows-1 gap-4 xl:grid-rows-2 xl:gap-6" v-if="nameserverMarketShare.length">
                    <PieChart
                        :title="$t('ns_marketshare')"
                        :data="nameserverMarketShare.slice(0, 19).map((item: NameServerMarketShareType) => item.count)"
                        :dataTitle="$t('domain_count')"
                        :labels="nameserverMarketShare.slice(0, 19).map((item: NameServerMarketShareType) => item.ns)"
                        :accentColor="accentColor"
                    />
                    <BarChart
                        :title="$t('ns_marketshare')"
                        :data="nameserverMarketShare.slice(0, 19).map((item: NameServerMarketShareType) => item.count)"
                        :dataTitle="$t('domain_count')"
                        :labels="nameserverMarketShare.slice(0, 19).map((item: NameServerMarketShareType) => item.ns)"
                        :accentColor="accentColor"
                    />
                </div>
            </div>

            <h1 class="font-size-2xl brand-text col-span-12" v-if="calendarHeatmapByDay.length">{{ $t('expiring_domains_heatmap') }}</h1>
            <div class="mb-6 grid grid-cols-1 gap-4 xl:grid-cols-1 xl:gap-6" v-if="calendarHeatmapByDay.length">
                <CalendarHeatMap :title="$t('expiring_domains_count')" :data="calendarHeatmapByDay" :months="$t('months')" :levels="$t('levels')" />
            </div>

            <h1 class="font-size-2xl brand-text col-span-12" v-if="expiredDomains.length">{{ $t('expired_domains') }}</h1>
            <div class="mb-6 grid grid-cols-1 gap-4 xl:grid-cols-1 xl:gap-6" v-if="expiredDomains.length">
                <WordCloud :data="expiredDomains" />
            </div>

            <h1 class="font-size-2xl brand-text col-span-12" v-if="newDomains.length">{{ $t('new_domains') }}</h1>
            <div class="mb-6 grid grid-cols-1 gap-4 xl:grid-cols-1 xl:gap-6" v-if="newDomains.length">
                <WordCloud :data="newDomains" />
            </div>

            <div v-if="!domainStatistics">
                <h3 class="text-center text-gray-500">{{ $t('no_data_yet') }}</h3>
            </div>
        </div>
    </AdminLayout>
</template>
