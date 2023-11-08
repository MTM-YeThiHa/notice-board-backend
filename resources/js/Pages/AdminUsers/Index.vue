<script setup>
import { Head, Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LinkButton from '@/Components/LinkButton.vue';

defineProps(['adminUsers']);

</script>

<template>
    <Head title="Admin Users" />
    <AuthenticatedLayout>
        <div class="max-w-screen-xl mx-auto p-4 sm:p-6 lg:p-8">
            <div class="relative mt-4 overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 table-fixed">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-8 max-w-8 min-w-8">
                                Id
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Created At
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="adminUser in adminUsers" :key="adminUser.id" class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-900">
                                {{ adminUser.id }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ adminUser.name }}
                            </td>
                            <td class="px-6 py-4 text-gray-900">
                                {{ adminUser.email }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-400 truncate">
                                {{ dayjs(adminUser.created_at).format('YYYY-MM-DD hh:mm:ss') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>