<script lang="ts" setup>
import ReactiveLink from './ReactiveLink.vue';
defineProps({
    data: {
        type: Object,
        required: true,
    },
    links: {
        type: Object,
        required: true,
    },
});
</script>
<template>
    <div class="flex items-center justify-center gap-2 py-4">
        <ReactiveLink
            :disabled="!links.first_page_url || parseInt(links.first_page_url?.split('?page=')[1]) === data.current_page || links.last_page_url === links.first_page_url"
            :link="links.first_page_url ?? '#'"
            :label="$t('first')"
        />
        <ReactiveLink
            :disabled="!links.prev_page_url"
            :link="links.prev_page_url ?? '#'"
            :label="$t('previous')"
        />
        <span class="text-sm text-gray-700 dark:text-gray-400">{{ data.current_page }} of {{ data.last_page }}</span>
        <ReactiveLink
            :disabled="!links.next_page_url"
            :link="links.next_page_url ?? '#'"
            :label="$t('next')"
        />
        <ReactiveLink
            :disabled="!links.last_page_url || parseInt(links.last_page_url?.split('?page=')[1]) === data.current_page || links.last_page_url === links.first_page_url"
            :link="links.last_page_url ?? '#'"
            :label="$t('last')"
        />
    </div>
</template>
