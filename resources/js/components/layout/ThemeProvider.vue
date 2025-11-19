<template>
    <slot></slot>
</template>

<script setup lang="ts">
import { computed, onMounted, provide, ref, watch } from 'vue';

type Theme = 'light' | 'dark';

const theme = ref<Theme>('light');
const isInitialized = ref(false);
const isDarkMode = computed(() => theme.value === 'dark');

const toggleTheme = () => {
    theme.value = theme.value === 'light' ? 'dark' : 'light';
};

onMounted(() => {
    const savedTheme = localStorage.getItem('theme') as Theme | null;
    const userPreference = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    const initialTheme = savedTheme || userPreference;

    theme.value = initialTheme;
    isInitialized.value = true;
});

watch([theme, isInitialized], ([newTheme, newIsInitialized]) => {
    if (newIsInitialized) {
        localStorage.setItem('theme', newTheme);
        if (newTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
});

provide('theme', {
    isDarkMode,
    toggleTheme,
});
</script>

<script lang="ts">
import { inject } from 'vue';

export function useTheme() {
    const theme = inject('theme');
    if (!theme) {
        throw new Error('useTheme must be used within a ThemeProvider');
    }
    return theme;
}
</script>
