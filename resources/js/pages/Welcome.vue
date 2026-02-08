<script setup lang="ts">
import CookieConsent from '@/components/common/CookieConsent.vue';
import Logo from '@/components/layout/Logo.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome">asdf </Head>
    <div class="dark:brand-bg flex min-h-screen flex-col items-center p-6 text-[#1b1b18] lg:justify-center lg:p-8">
        <div class="flex w-full items-center justify-center gap-5 opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
            <main class="flex w-[50%] flex-col overflow-hidden">
                <div class="flex flex-row gap-5 mb-6 items-center">
                    <Logo />
                    <div class="flex flex-col">
                        <h1 class="brand-text text-bold !text-4xl">{{ $t('welcome') }}</h1>
                        <p class="text-gray-500 dark:text-gray-200">{{ $t('welcome_text') }}</p>
                    </div>
                </div>
                <div
                    class="dark:border-brand-300 dark:bg-brand-900 flex-1 rounded-xl border-1 bg-white p-6 text-[13px] leading-[20px] dark:text-gray-400"
                >
                    <div class="flex flex-col gap-4">
                        <Link :href="'/domainsk/'">
                            <div
                                class="brand-border dark:brand-bg mb-10 w-full max-w-100 rounded-xl border-1 bg-gray-50 px-4 py-5 dark:hover:border-[var(--color-brand-text)]"
                            >
                                <h2 class="brand-text mb-2 font-semibold uppercase">{{ $t('sidebar_domains_title') }}</h2>
                                <p class="text-theme-sm mb-4 text-gray-500 dark:text-gray-400">{{ $t('sidebar_domains_text') }}</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <CookieConsent />
</template>
