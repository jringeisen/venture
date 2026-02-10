<template>
    <AdminLayout title="Blog Categories">
        <div class="space-y-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-beach-text dark:text-neutral-200">Blog Categories</h2>
                    <p class="mt-1 text-sm text-beach-text-light dark:text-neutral-400">Organize your blog posts with categories.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <Link :href="route('admin.blog-categories.create')" class="inline-flex items-center justify-center rounded-md bg-beach-teal px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-beach-teal-dark">
                        Add Category
                    </Link>
                </div>
            </div>

            <div class="bg-white dark:bg-primary-gray shadow-sm dark:shadow-neutral-900/50 border border-slate-100 dark:border-neutral-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-primary-dark-gray">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-beach-text-light dark:text-neutral-400 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-beach-text-light dark:text-neutral-400 uppercase tracking-wider">Posts</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-primary-gray divide-y divide-gray-200 dark:divide-neutral-700">
                        <tr v-for="category in categories.data" :key="category.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-beach-text dark:text-neutral-200">{{ category.name }}</div>
                                <div class="text-sm text-beach-text-light dark:text-neutral-400">{{ category.slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-beach-text-light dark:text-neutral-400">
                                {{ category.posts_count }} posts
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <Link :href="route('admin.blog-categories.edit', category.id)" class="text-beach-teal hover:text-beach-teal-dark">Edit</Link>
                                <button @click="deleteCategory(category)" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="categories.data.length === 0">
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-beach-text-light dark:text-neutral-400">
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
