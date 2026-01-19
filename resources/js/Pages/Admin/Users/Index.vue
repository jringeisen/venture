<template>
    <AdminLayout title="Users">
        <div class="space-y-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Users</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage all registered users.</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <TextInput
                            v-model="search"
                            type="text"
                            placeholder="Search users..."
                            class="w-full"
                            @input="debouncedSearch"
                        />
                    </div>
                    <div>
                        <select v-model="type" @change="applyFilters" class="block w-full rounded-md border-gray-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white shadow-sm">
                            <option value="">All Users</option>
                            <option value="parents">Parents Only</option>
                            <option value="students">Students Only</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Students</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Courses</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                        <tr v-for="user in users.data" :key="user.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="user.parent_id ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    {{ user.parent_id ? 'Student' : 'Parent' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ user.students_count || 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ user.enrolled_courses_count || 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <Link :href="route('admin.users.show', user.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">View</Link>
                                <Link :href="route('admin.users.edit', user.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</Link>
                            </td>
                        </tr>
                        <tr v-if="users.data.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No users found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :data="users"/>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const type = ref(props.filters?.type || '');

let searchTimeout = null;

const debouncedSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 300);
};

const applyFilters = () => {
    router.get(route('admin.users.index'), {
        search: search.value || undefined,
        type: type.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};
</script>
