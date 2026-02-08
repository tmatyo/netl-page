<script setup lang="ts">
const commitHash = import.meta.env.VITE_SOURCE_COMMIT || 'unknown';
</script>

<template>
    <div class="mx-auto mb-10 w-full max-w-60 rounded-2xl bg-gray-50 px-4 py-5 text-center dark:bg-white/[0.03]">
        <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">{{ $t('sidebar_domains_title') }}</h3>
        <p class="text-theme-sm mb-4 text-gray-500 dark:text-gray-400">{{ $t('sidebar_domains_text') }}</p>
        <small v-if="commitHash !== 'unknown'">{{ 'version: ' + commitHash.slice(0, 7) }}</small>
    </div>
</template>
