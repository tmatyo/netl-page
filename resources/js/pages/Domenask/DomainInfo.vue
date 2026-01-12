<script setup lang="ts">
import IndividualMetric from '@/components/ecommerce/IndividualMetric.vue';
import { CrawlInfo } from '@/types';

interface Props {
    latest: CrawlInfo;
    previous: CrawlInfo;
    avgDomainLength: number;
    longestDomainLength: number;
}
defineProps<Props>();

const getPercentage = (latest: number, previous: number): number => {
    const change: number = ((latest - previous) / previous) * 100;
    return previous !== 0 ? Number(change.toFixed(2)) : previous;
};

const userLocale: string = navigator.languages?.[0] || navigator.language || 'sk-SK';
</script>
<template>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 md:gap-6 xl:grid-cols-2">
        <IndividualMetric
            :title="$t('domain_count')"
            :value="latest.domain_count"
            :percentage="getPercentage(latest.domain_count, previous.domain_count)"
        />
        <IndividualMetric :title="$t('valid_as_of')" :value="new Date(latest.time_generated).toLocaleString(userLocale)" />
        <IndividualMetric :title="$t('avg_domain_length')" :value="avgDomainLength" :suffix="$t('char')" />
        <IndividualMetric :title="$t('longest_domain_length')" :value="longestDomainLength" :suffix="$t('char')" />
    </div>
</template>
