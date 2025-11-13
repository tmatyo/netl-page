<script setup lang="ts">
interface Props {
    title: string;
    value: string | number;
    suffix?: string;
    percentage?: number;
    icon?: string;
}
defineProps<Props>();
</script>
<template>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-end justify-between">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ title }}</span>
                <h4 class="text-title-sm mt-2 font-bold text-gray-800 dark:text-white/90">
                    {{ Number(value) }} <span v-if="suffix">{{ ` ${suffix}` }}</span>
                </h4>
            </div>

            <span
                v-if="percentage !== undefined"
                class="flex items-center gap-1 rounded-full py-0.5 pr-2.5 pl-2 text-sm font-medium"
                :class="{
                    'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500': percentage > 0,
                    'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500': percentage === 0,
                    'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500': percentage < 0,
                }"
            >
                {{ percentage !== 0 ? `${Number(percentage)}%` : '-' }}
            </span>
        </div>
    </div>
</template>
