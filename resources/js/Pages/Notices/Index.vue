<script setup>
import { Head, Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LinkButton from '@/Components/LinkButton.vue';

defineProps(['notices']);

</script>

<template>
    <Head title="Notices" />
    <AuthenticatedLayout>
        <div class="max-w-screen-xl mx-auto p-4 sm:p-6 lg:p-8">
            <div class="flex items-center justify-end">
                <LinkButton :href="route('notices.create')">Create New Notice</LinkButton>
            </div>
            <div class="relative mt-4 overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 table-fixed">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-80 min-w-80">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3 w-52 max-w-48 min-w-48">
                                Distribution
                            </th>
                            <th scope="col" class="px-6 py-3 w-24 max-w-24 min-w-24">
                                Public
                            </th>
                            <th scope="col" class="px-6 py-3 w-40 max-w-40 min-w-40">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="notice in notices" :key="notice.id" class="bg-white border-b">
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ notice.title }}
                            </td>
                            <td
                                :class="['px-6 py-4 font-semibold', (dayjs().isBefore(notice.distribution_start_date) || dayjs().isAfter(notice.distribution_end_date)) ? 'text-red-500' : 'text-green-500']">
                                {{ dayjs(notice.distribution_start_date).format('D MMM YYYY') }} - {{
                                    dayjs(notice.distribution_end_date).format('D MMM YYYY') }}
                            </td>
                            <td class="px-6 py-4 ">
                                <Link :href="route('notices.updatePublic', notice.id)" method="post">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" :checked="notice.public_flag" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                                </Link>
                            </td>
                            <td class="flex space-x-4 px-6 py-4">
                                <Link :href="route('notices.edit', notice.id)"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</Link>
                                <Link :href="route('notices.destroy', notice.id)" method="delete"
                                    class="font-medium text-red-600 hover:underline">Delete</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>