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
            class="focus:ring-brand-medium box-border w-auto rounded border px-3 py-1.5 text-xs font-medium  uppercase "
            :class="{
                'text-gray-200 border-transparent bg-[var(--color-brand-500)]': page.props.locale === lang,
                'border-gray-200 dark:border-gray-800 text-gray-700 dark:text-gray-200 hover:border-[var(--color-brand-200)]': page.props.locale !== lang,
            }"
        >
            {{ lang }}
        </button>
    </div>
</template>
