<template>
    <AdminLayout title="Feedback">
        <div class="space-y-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Feedback</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View and manage user feedback.</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg p-4">
                <div class="flex gap-4">
                    <select v-model="status" @change="applyFilters" class="block w-48 rounded-md border-gray-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white shadow-sm">
                        <option value="">All Statuses</option>
                        <option v-for="(label, value) in statuses" :key="value" :value="value">{{ label }}</option>
                    </select>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Feedback</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                        <tr v-for="item in feedback.data" :key="item.id">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ item.title }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ item.description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ item.user?.name || 'Unknown' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="statusClass(item.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize">
                                    {{ item.status?.replace('_', ' ') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <Link :href="route('admin.feedback.show', item.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">View</Link>
                                <button @click="deleteFeedback(item)" class="text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="feedback.data.length === 0">
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No feedback yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :data="feedback"/>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    feedback: Object,
    statuses: Object,
    filters: Object,
});

const status = ref(props.filters?.status || '');

const applyFilters = () => {
    router.get(route('admin.feedback.index'), {
        status: status.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

const statusClass = (status) => {
    const classes = {
        open: 'bg-blue-100 text-blue-800',
        in_progress: 'bg-yellow-100 text-yellow-800',
        on_hold: 'bg-gray-100 text-gray-800',
        resolved: 'bg-green-100 text-green-800',
        reopened: 'bg-red-100 text-red-800',
        cancelled: 'bg-gray-100 text-gray-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const deleteFeedback = (item) => {
    if (confirm('Are you sure you want to delete this feedback?')) {
        router.delete(route('admin.feedback.destroy', item.id));
    }
};
</script>
