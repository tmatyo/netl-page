import '../css/app.css';
import '../css/main.css';
// Import Swiper styles
import 'flatpickr/dist/flatpickr.css';
import 'jsvectormap/dist/jsvectormap.css';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { createGtag } from 'vue-gtag';
import VueApexCharts from 'vue3-apexcharts';
import SidebarProvider from './components/layout/SidebarProvider.vue';
import ThemeProvider from './components/layout/ThemeProvider.vue';
import { t } from './helpers/i18n';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const appGtag = import.meta.env.VITE_GTAG || '';
const gtag = createGtag({
    tagId: appGtag,
});

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({
            render: () =>
                h(
                    ThemeProvider,
                    {},
                    {
                        default: () =>
                            h(
                                SidebarProvider,
                                {},
                                {
                                    default: () => h(App, props),
                                },
                            ),
                    },
                ),
        });
        app.use(plugin);
        app.use({ install: (app) => (app.config.globalProperties.$t = t) });
        app.use(VueApexCharts);
        app.use(gtag);
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
