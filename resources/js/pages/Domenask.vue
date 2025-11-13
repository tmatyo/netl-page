<template>
    <ThemeProvider>
        <SidebarProvider>
            <Index />
        </SidebarProvider>
    </ThemeProvider>
</template>

<script setup lang="ts">
import SidebarProvider from '@/components/layout/SidebarProvider.vue';
import ThemeProvider from '@/components/layout/ThemeProvider.vue';
import Index from './Domenask/Index.vue';
</script>
