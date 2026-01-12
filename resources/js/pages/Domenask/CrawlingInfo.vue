<script setup lang="ts">
import IndividualMetric from '@/components/ecommerce/IndividualMetric.vue';
import { CrawlInfo } from '@/types';

interface Props {
    latest: CrawlInfo;
    previous: CrawlInfo;
    avgSpeed: number;
    avgDuration: number;
}
defineProps<Props>();

const getPercentage = (latest: number, previous: number): number => {
    const change: number = ((latest - previous) / previous) * 100;
    return previous !== 0 ? Number(change.toFixed(2)) : previous;
};

</script>
<template>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 md:gap-6 xl:grid-cols-4">
        <IndividualMetric
            :title="$t('file_size')"
            :value="(latest.file_size / 1024 / 1024).toFixed(2)"
            suffix="MB"
            :percentage="getPercentage(latest.file_size, previous.file_size)"
        />
        <IndividualMetric
            :title="$t('crawling_duration')"
            :value="latest.crawling_duration.toFixed(4)"
            suffix="s"
            :percentage="getPercentage(latest.crawling_duration, previous.crawling_duration)"
        />
        <IndividualMetric :title="$t('avg_crawling_duration')" :value="avgDuration.toFixed(4)" suffix="s" />
        <IndividualMetric
            :title="$t('download_duration')"
            :value="latest.download_duration.toFixed(4)"
            suffix="s"
            :percentage="getPercentage(latest.download_duration, previous.download_duration)"
        />
        <IndividualMetric
            :title="$t('download_speed')"
            :value="Math.round(latest.avg_speed_in_bytes_per_sec / 1024 / 1024)"
            suffix="MB/s"
            :percentage="getPercentage(latest.avg_speed_in_bytes_per_sec, previous.avg_speed_in_bytes_per_sec)"
        />
        <IndividualMetric :title="$t('avg_download_speed')" :value="Math.round(avgSpeed / 1024 / 1024)" suffix="MB/s" />
    </div>
</template>
