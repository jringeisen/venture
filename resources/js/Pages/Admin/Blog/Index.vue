<template>
    <AdminLayout title="Blog Posts">
        <div class="space-y-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Blog Posts</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your blog content.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <Link :href="route('admin.blog-posts.create')" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Add Post
                    </Link>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                        <tr v-for="post in posts.data" :key="post.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ post.title }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ post.user?.name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ post.category?.name || 'Uncategorized' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="post.is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    {{ post.is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <Link :href="route('admin.blog-posts.edit', post.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</Link>
                                <button @click="deletePost(post)" class="text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="posts.data.length === 0">
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No blog posts yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :data="posts"/>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    posts: Object,
});

const deletePost = (post) => {
    if (confirm('Are you sure you want to delete this post?')) {
        router.delete(route('admin.blog-posts.destroy', post.id));
    }
};
</script>
