<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import LinkButton from '@/Components/LinkButton.vue';

defineProps(['users']);

</script>

<template>
    <Head title="Users" />
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
                            <th scope="col" class="px-6 py-3 w-48 max-w-48 min-w-48">
                                Device Token
                            </th>
                            <th scope="col" class="px-6 py-3 w-32 max-w-32 min-w-32">
                                Suspended
                            </th>
                            <th scope="col" class="px-6 py-3 w-40 max-w-40 min-w-40">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id" class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-900">
                                {{ user.id }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ user.name }}
                            </td>
                            <td class="px-6 py-4 text-gray-900">
                                {{ user.email }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-400 truncate">
                                {{ user.device_token }}
                            </td>
                            <td class="px-6 py-4 ">
                                <Link :href="route('users.updateSuspend', user.id)" method="post">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" :checked="user.suspend_flag" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                                </Link>
                            </td>
                            <td class="flex space-x-4 px-6 py-4">
                                <Link :href="route('users.destroy', user.id)" method="delete"
                                    class="font-medium text-red-600 hover:underline">Delete</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>