<script setup lang="ts">
import AdminLayout from '@/components/layout/AdminLayout.vue';
import Pagination from '@/components/Pagination.vue';
import { OwnerMarketShareType, PaginationType } from '@/types';
defineProps<{ ownersMarketShare: PaginationType<OwnerMarketShareType> }>();
</script>

<template>
    <AdminLayout>
        <h1 class="font-size-3xl text-[var(--color-brand-500)]">Owner Marketshare</h1>
        <h3 v-if="ownersMarketShare.data.length > 0" class="text-[var(--color-brand-500)]">{{ ownersMarketShare.data.length }} lines</h3>
        <div class="my-3" v-if="ownersMarketShare">
            <Pagination
                v-if="ownersMarketShare.last_page > 1"
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
                <table class="w-full text-left text-sm text-zinc-500 rtl:text-right dark:text-zinc-400">
                    <thead class="bg-zinc-50 text-xs text-zinc-700 uppercase dark:bg-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Owner</th>
                            <th scope="col" class="px-6 py-3">Domain count</th>
                            <th scope="col" class="px-6 py-3">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="border-b border-zinc-200 odd:bg-white even:bg-zinc-50 dark:border-zinc-700 odd:dark:bg-zinc-900 even:dark:bg-zinc-800"
                            v-for="d in ownersMarketShare.data"
                            :key="d.id"
                        >
                            <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap text-zinc-900 dark:text-white">
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
                v-if="ownersMarketShare.last_page > 1"
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
        <div v-else class="p-4 text-yellow-500">Loading...</div>
    </AdminLayout>
</template>
