<script setup lang="ts">
import { getCurrentInstance, onMounted, ref } from 'vue';
import { VueCookies } from 'vue-cookies';
import { consentDeniedAll, consentGrantedAll } from 'vue-gtag';

const cookieName = 'cookie_consent';
const defaultExpires = '30d';
const show = ref(false);
const instance = getCurrentInstance();

if (!instance) {
    throw new Error('Failed to get current instance');
}

const $cookies: VueCookies = instance.appContext.config.globalProperties.$cookies;

const accept = () => {
    $cookies.set(cookieName, 'granted', defaultExpires);
    consentGrantedAll();
    show.value = false;
};

const decline = () => {
    $cookies.set(cookieName, 'denied', defaultExpires);
    consentDeniedAll();
    show.value = false;
};

onMounted(() => {
    const consent = $cookies.get(cookieName);
    if (consent === 'granted') {
        consentGrantedAll();
    } else if (consent === 'denied') {
        consentDeniedAll();
    } else {
        show.value = true;
    }
});
</script>
<template>
    <aside
        v-if="show"
        class="bg-white border border-gray-200 dark:brand-border z-[999999] dark:brand-bg fixed right-0 bottom-0 left-0 mx-auto px-6 py-4 sm:right-16 sm:bottom-6 sm:left-16 sm:max-w-5xl sm:rounded-md"
    >
        <div class="flex items-center justify-between space-x-6 text-gray-800 dark:text-gray-200">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $t('cookie_consent_message') }}
                </p>
            </div>
            <div class="ml-auto flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3">
                <button
                    @click="decline"
                    class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium whitespace-nowrap text-gray-900 hover:text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:z-10 focus:ring-4 focus:ring-gray-200 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    type="button"
                >
                    {{ $t('cookie_consent_decline') }}
                </button>
                <button
                    @click="accept"
                    class="rounded-lg bg-[var(--color-brand-text)] px-5 py-2.5 text-sm font-medium whitespace-nowrap text-white transition duration-150 ease-in-out focus:z-10 focus:ring-4 focus:ring-blue-300 focus:outline-none"
                    type="button"
                >
                    {{ $t('cookie_consent_button') }}
                </button>
            </div>
        </div>
    </aside>
</template>
