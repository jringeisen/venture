<template>
    <AdminLayout title="Blog Categories">
        <div class="space-y-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Blog Categories</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Organize your blog posts with categories.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <Link :href="route('admin.blog-categories.create')" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Add Category
                    </Link>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Posts</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                        <tr v-for="category in categories.data" :key="category.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ category.name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ category.slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ category.posts_count }} posts
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <Link :href="route('admin.blog-categories.edit', category.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</Link>
                                <button @click="deleteCategory(category)" class="text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="categories.data.length === 0">
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No categories yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :data="categories"/>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    categories: Object,
});

const deleteCategory = (category) => {
    if (confirm('Are you sure you want to delete this category?')) {
        router.delete(route('admin.blog-categories.destroy', category.id));
    }
};
</script>
