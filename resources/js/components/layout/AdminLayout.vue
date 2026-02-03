<template>
    <div class="flex min-h-screen flex-col">
        <app-sidebar :noData="noData" />
        <Backdrop />
        <div
            class="flex min-h-screen flex-1 flex-col transition-all duration-300 ease-in-out"
            :class="[isExpanded || isHovered ? 'lg:ml-[290px]' : 'lg:ml-[90px]']"
        >
            <app-header />
            <div class="flex-1">
                <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
                    <slot></slot>
                </div>
            </div>
            <!-- Footer -->
            <footer class="mx-auto max-w-(--breakpoint-2xl) p-4 pt-0 md:p-6">
                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                    {{ $t('heavily_inspired_by') }}
                    <a
                        href="https://tailadmin.com/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="brand-text font-medium transition-colors duration-200"
                    >
                        TailAdmin
                    </a>
                    .
                </p>
            </footer>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useSidebar } from '@/composables/useSidebar';
import AppHeader from './AppHeader.vue';
import AppSidebar from './AppSidebar.vue';
import Backdrop from './Backdrop.vue';
const { isExpanded, isHovered } = useSidebar();
defineProps<{ noData?: boolean }>();
</script>
