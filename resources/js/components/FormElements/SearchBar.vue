<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{
    searchTerm: string;
}>();

const emit = defineEmits<{
    (e: 'search', query: string): void;
}>();

const searchTerm = ref<string>(props.searchTerm || '');

const onSearch = (event: Event) => {
    event.preventDefault();
    if(searchTerm.value.trim() === '' || searchTerm.value.length < 3) {
        return;
    }
    emit('search', searchTerm.value);
};
</script>
<template>
    <form @submit="onSearch" class="my-4">
        <label for="search" class="sr-only mb-2 text-sm font-medium text-gray-900 dark:text-white">Search</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 dark:text-gray-500">
                <svg
                    class="text-body h-4 w-4"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input
                v-model="searchTerm"
                type="search"
                id="search"
                class="border-default-medium text-heading rounded border-gray-300 dark:border-gray-800 focus:ring-brand focus:border-brand placeholder:text-body block w-full border p-3 ps-9 text-sm shadow-xs text-gray-800 dark:text-gray-300"
                placeholder="Search"
                minlength="3"
                maxlength="256"
                required
            />
            <button
                type="submit"
                class="hover:bg-brand-strong focus:ring-brand-medium absolute end-1.5 bottom-1.5 box-border rounded border border-transparent bg-[var(--color-brand-500)] px-3 py-1.5 text-xs leading-5 font-medium text-white shadow-xs focus:ring-4 focus:outline-none"
            >
                Search
            </button>
        </div>
    </form>
</template>
