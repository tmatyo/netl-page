<script setup lang="ts">
import AdminLayout from '@/components/layout/AdminLayout.vue';
import Pagination from '@/components/Pagination.vue';
import { DomainListPropsType } from '@/types';
defineProps<DomainListPropsType>();
</script>

<template>
    <AdminLayout>
        <h1 class="font-size-3xl text-[var(--color-brand-500)]">Domain List <small class="text-gray-700">({{ domains.total }})</small></h1>
        <div class="my-3" v-if="domains">
            <Pagination
                v-if="domains.last_page > 1"
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
                <table class="w-full text-left text-sm text-zinc-500 rtl:text-right dark:text-zinc-400">
                    <thead class="bg-zinc-50 text-xs text-zinc-700 uppercase dark:bg-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Domain</th>
                            <th scope="col" class="px-6 py-3">Registrar</th>
                            <th scope="col" class="px-6 py-3">Owner</th>
                            <th scope="col" class="px-6 py-3">Name servers</th>
                            <th scope="col" class="px-6 py-3">Expiration date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="border-b border-zinc-200 odd:bg-white even:bg-zinc-50 dark:border-zinc-700 odd:dark:bg-zinc-900 even:dark:bg-zinc-800"
                            v-for="d in domains.data"
                            :key="d.id"
                        >
                            <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap text-zinc-900 dark:text-white">
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
                v-if="domains.last_page > 1"
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
        <div v-else class="p-4 text-yellow-500">Loading...</div>
    </AdminLayout>
</template>
