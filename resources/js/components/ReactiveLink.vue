<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
defineProps<{ link: string; label: string; disabled: boolean }>();
</script>
<template>
    <Link
        class="hover:bg-gray-200 dark:hover:bg-gray-800 cursor-pointer rounded border px-3 py-1 text-sm border-gray-200 dark:brand-border text-gray-500 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-200"
        :class="disabled && 'pointer-events-none cursor-not-allowed opacity-20 disabled:cursor-not-allowed disabled:opacity-20'"
        :href="link"
        :aria-label="label"
        :disabled="disabled"
        @click="disabled ? (e: Event) => e.preventDefault() : null"
    >
        {{ label }}
    </Link>
</template>
