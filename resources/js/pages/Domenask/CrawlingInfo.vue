<script setup lang="ts">
import IndividualMetric from '@/components/ecommerce/IndividualMetric.vue';
type CrawlInfo = {
    id: number;
    domain_count: number;
    crawling_duration: number;
    download_duration: number;
    avg_speed_in_bytes_per_sec: number;
    file_size: number;
    time_generated: string;
    table_name: string;
};
interface Props {
    latest: CrawlInfo;
    previous: CrawlInfo;
}
const props = defineProps<Props>();
console.log(props);

const getPercentage = (latest: number, previous: number): number => {
    const change: number = ((latest - previous) / previous) * 100;
    return previous !== 0 ? Number(change.toFixed(2)) : previous;
};
</script>
<template>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-6">
        <IndividualMetric
            title="Number of domains"
            :value="latest.domain_count"
            :percentage="getPercentage(latest.domain_count, previous.domain_count)"
        />
        <IndividualMetric
            title="Crawling duration"
            :value="latest.crawling_duration.toFixed(4)"
            suffix="s"
            :percentage="getPercentage(latest.crawling_duration, previous.crawling_duration)"
        />
        <IndividualMetric
            title="Download duration"
            :value="latest.download_duration.toFixed(4)"
            suffix="s"
            :percentage="getPercentage(latest.download_duration, previous.download_duration)"
        />
        <IndividualMetric
            title="Average speed"
            :value="Math.round(latest.avg_speed_in_bytes_per_sec / 1024 / 1024)"
            suffix="MB/s"
            :percentage="getPercentage(latest.avg_speed_in_bytes_per_sec, previous.avg_speed_in_bytes_per_sec)"
        />
        <IndividualMetric
            title="File size"
            :value="(latest.file_size / 1024 / 1024).toFixed(2)"
            suffix="MB"
            :percentage="getPercentage(latest.file_size, previous.file_size)"
        />
    </div>
</template>
