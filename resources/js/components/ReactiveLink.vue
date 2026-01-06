<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
defineProps<{ link: string; label: string; disabled: boolean }>();
</script>
<template>
    <Link
        class="dark:bg-dark-background border-sidebar-border/70 bg-background hover:bg-sidebar-border/10 dark:border-sidebar-border hover:dark:bg-sidebar-border/10 cursor-pointer rounded border px-3 py-1 text-sm text-gray-700 dark:text-gray-400 border-gray-700 dark:border-gray-400"
        :class="disabled && 'pointer-events-none cursor-not-allowed opacity-50 disabled:cursor-not-allowed disabled:opacity-50'"
        :href="link"
        :aria-label="label"
        :disabled="disabled"
        @click="disabled ? (e: Event) => e.preventDefault() : null"
    >
        {{ label }}
    </Link>
</template>
