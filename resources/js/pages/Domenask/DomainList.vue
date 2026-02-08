<script setup lang="ts">
import NothingToSeeHere from '@/components/common/NothingToSeeHere.vue';
import SearchBar from '@/components/FormElements/SearchBar.vue';
import AdminLayout from '@/components/layout/AdminLayout.vue';
import Pagination from '@/components/Pagination.vue';
import { DomainListPropsType } from '@/types';
import { router } from '@inertiajs/vue3';

const props = defineProps<DomainListPropsType>();

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
        <div class="my-3" v-if="domains.data.length > 0">
        <h1 class="font-size-3xl brand-text">
            {{ $t('domain_list') }} <small class="text-gray-700">({{ domains.total }})</small>
        </h1>
        <SearchBar :searchTerm="searchQuery ?? ''" @search="(searchQuery: string) => search(searchQuery)" />
            <Pagination
                :data="{ current_page: domains.current_page, last_page: domains.last_page }"
                :links="{
                    first_page_url: domains.first_page_url,
                    prev_page_url: domains.prev_page_url,
                    next_page_url: domains.next_page_url,
                    last_page_url: domains.last_page_url,
                }"
                class="mt-4"
            />
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-brand-600 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">{{ $t('domain') }}</th>
                            <th scope="col" class="px-6 py-3">{{ $t('registrar') }}</th>
                            <th scope="col" class="px-6 py-3">{{ $t('owner') }}</th>
                            <th scope="col" class="px-6 py-3">{{ $t('name_servers') }}</th>
                            <th scope="col" class="px-6 py-3">{{ $t('expiration_date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="border-b border-gray-200 odd:bg-white even:bg-gray-50 dark:border-brand-600 odd:dark:bg-brand-800 even:dark:bg-brand-700"
                            v-for="d in domains.data"
                            :key="d.id"
                        >
                            <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap text-gray-900 dark:text-white">
                                {{ d.id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ d.domain }}
                            </td>
                            <td class="px-6 py-4">
                                {{ d.id_reg }}
                            </td>
                            <td class="px-6 py-4">
                                {{ d.id_owner }}
                            </td>
                            <td class="px-6 py-4">
                                {{ d.ns1 }}<br />
                                {{ d.ns2 }}<br />
                                {{ d.ns3 }}<br />
                                {{ d.ns4 }}
                            </td>
                            <td class="px-6 py-4">
                                {{ d.expiry_date }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Pagination
                :data="{ current_page: domains.current_page, last_page: domains.last_page }"
                :links="{
                    first_page_url: domains.first_page_url,
                    prev_page_url: domains.prev_page_url,
                    next_page_url: domains.next_page_url,
                    last_page_url: domains.last_page_url,
                }"
                class="mt-4"
            />
        </div>
        <NothingToSeeHere v-else />
    </AdminLayout>
</template>
