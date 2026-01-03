<script setup lang="ts">
import NothingToSeeHere from '@/components/common/NothingToSeeHere.vue';
import SearchBar from '@/components/FormElements/SearchBar.vue';
import AdminLayout from '@/components/layout/AdminLayout.vue';
import Pagination from '@/components/Pagination.vue';
import { RegistrarMarketSharePropsType } from '@/types';
import { router } from '@inertiajs/vue3';
defineProps<RegistrarMarketSharePropsType>();
const search = (searchText: string) => {
    router.get(
        window.location.pathname,
        { search: searchText },
        {
            preserveState: true,
            replace: true,
        },
    );
};
</script>

<template>
    <AdminLayout>
        <h1 class="font-size-3xl text-[var(--color-brand-500)]">
            Registrar Marketshare <small class="text-gray-700">({{ registrarsMarketShare.total }})</small>
        </h1>
        <SearchBar :searchTerm="searchQuery ?? ''" @search="(searchQuery: string) => search(searchQuery)" />
        <div class="my-3" v-if="registrarsMarketShare.data.length > 0">
            <Pagination
                :data="{ current_page: registrarsMarketShare.current_page, last_page: registrarsMarketShare.last_page }"
                :links="{
                    first_page_url: registrarsMarketShare.first_page_url,
                    prev_page_url: registrarsMarketShare.prev_page_url,
                    next_page_url: registrarsMarketShare.next_page_url,
                    last_page_url: registrarsMarketShare.last_page_url,
                }"
                class="mt-4"
            />
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left text-sm text-zinc-500 rtl:text-right dark:text-zinc-400">
                    <thead class="bg-zinc-50 text-xs text-zinc-700 uppercase dark:bg-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Registrar</th>
                            <th scope="col" class="px-6 py-3">Domain count</th>
                            <th scope="col" class="px-6 py-3">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="border-b border-zinc-200 odd:bg-white even:bg-zinc-50 dark:border-zinc-700 odd:dark:bg-zinc-900 even:dark:bg-zinc-800"
                            v-for="d in registrarsMarketShare.data"
                            :key="d.id"
                        >
                            <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap text-zinc-900 dark:text-white">
                                {{ d.id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ d.registrar }}
                            </td>
                            <td class="px-6 py-4">
                                {{ d.domain_count }}
                            </td>
                            <td class="px-6 py-4">
                                {{ d.percentage }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Pagination
                :data="{ current_page: registrarsMarketShare.current_page, last_page: registrarsMarketShare.last_page }"
                :links="{
                    first_page_url: registrarsMarketShare.first_page_url,
                    prev_page_url: registrarsMarketShare.prev_page_url,
                    next_page_url: registrarsMarketShare.next_page_url,
                    last_page_url: registrarsMarketShare.last_page_url,
                }"
                class="mt-4"
            />
        </div>
        <NothingToSeeHere v-else />
    </AdminLayout>
</template>
