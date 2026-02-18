<script setup lang="ts">
defineProps<{
    data: string[];
}>();
</script>
<template>
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <span v-for="domain in data" class="text-sm inline-block text-gray-300 m-1 p-1">{{ domain }}</span>  
    </div>
</template>
