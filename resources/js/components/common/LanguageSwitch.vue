<script setup lang="tsx">
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();

const switchLocale = (locale: string) => {
    router.post(`/locale`, { locale }, { preserveState: true, preserveScroll: true, replace: true });
};
</script>
<template>
    <div class="flex flex-row gap-2">
        <button
            v-for="lang in page.props.locales"
            @click="switchLocale(lang)"
            class="focus:ring-brand-medium box-border w-auto rounded border px-3 py-1.5 text-xs font-medium uppercase "
            :class="{
                'text-gray-100 border-transparent bg-[var(--color-brand-text)]': page.props.locale === lang,
                'border-gray-200 dark:brand-border text-gray-500 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800': page.props.locale !== lang,
            }"
        >
            {{ lang }}
        </button>
    </div>
</template>
