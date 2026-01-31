<script setup lang="ts">
import SearchBar from '@/components/FormElements/SearchBar.vue';
import AdminLayout from '@/components/layout/AdminLayout.vue';
import Pagination from '@/components/Pagination.vue';
import NothingToSeeHere from '@/components/common/NothingToSeeHere.vue';
import { OwnerMarketSharePropsType } from '@/types';
import { router } from '@inertiajs/vue3';
defineProps<OwnerMarketSharePropsType>();

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
        <h1 class="font-size-3xl brand-text">
            Owner Marketshare <small class="text-gray-700">({{ ownersMarketShare.total }})</small>
        </h1>
        <SearchBar :searchTerm="searchQuery ?? ''" @search="(searchQuery: string) => search(searchQuery)" />
        <div class="my-3" v-if="ownersMarketShare.data.length > 0">
            <Pagination
                :data="{ current_page: ownersMarketShare.current_page, last_page: ownersMarketShare.last_page }"
                :links="{
                    first_page_url: ownersMarketShare.first_page_url,
                    prev_page_url: ownersMarketShare.prev_page_url,
                    next_page_url: ownersMarketShare.next_page_url,
                    last_page_url: ownersMarketShare.last_page_url,
                }"
                class="mt-4"
            />
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Owner</th>
                            <th scope="col" class="px-6 py-3">Domain count</th>
                            <th scope="col" class="px-6 py-3">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="border-b border-gray-200 odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-gray-900 even:dark:bg-gray-800"
                            v-for="d in ownersMarketShare.data"
                            :key="d.id"
                        >
                            <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap text-gray-900 dark:text-white">
                                {{ d.id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ d.owner }}
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
                :data="{ current_page: ownersMarketShare.current_page, last_page: ownersMarketShare.last_page }"
                :links="{
                    first_page_url: ownersMarketShare.first_page_url,
                    prev_page_url: ownersMarketShare.prev_page_url,
                    next_page_url: ownersMarketShare.next_page_url,
                    last_page_url: ownersMarketShare.last_page_url,
                }"
                class="mt-4"
            />
        </div>
        <NothingToSeeHere v-else />
    </AdminLayout>
</template>
