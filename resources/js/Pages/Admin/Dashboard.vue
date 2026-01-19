<template>
    <AdminLayout title="Dashboard">
        <div class="space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="stat in statCards" :key="stat.name" class="bg-white dark:bg-neutral-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <component :is="stat.icon" class="h-6 w-6 text-gray-400" aria-hidden="true"/>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ stat.name }}</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ stat.value }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-neutral-700 px-5 py-3">
                        <div class="text-sm">
                            <Link :href="stat.href" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                View all
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Feedback -->
            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-neutral-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent Feedback</h3>
                </div>
                <ul role="list" class="divide-y divide-gray-200 dark:divide-neutral-700">
                    <li v-for="item in recentFeedback" :key="item.id" class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ item.title }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ item.user?.name || 'Unknown' }}</p>
                            </div>
                            <div class="ml-4">
                                <span :class="statusClass(item.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize">
                                    {{ item.status?.replace('_', ' ') }}
                                </span>
                            </div>
                        </div>
                    </li>
                    <li v-if="recentFeedback.length === 0" class="px-4 py-4 sm:px-6 text-sm text-gray-500 dark:text-gray-400">
                        No feedback yet.
                    </li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
    UsersIcon,
    AcademicCapIcon,
    DocumentTextIcon,
    ChatBubbleLeftRightIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    stats: Object,
    recentFeedback: Array,
});

const statCards = computed(() => [
    {
        name: 'Total Users',
        value: props.stats.users,
        icon: UsersIcon,
        href: route('admin.users.index'),
    },
    {
        name: 'Courses',
        value: props.stats.courses,
        icon: AcademicCapIcon,
        href: route('admin.courses.index'),
    },
    {
        name: 'Blog Posts',
        value: props.stats.blogPosts,
        icon: DocumentTextIcon,
        href: route('admin.blog-posts.index'),
    },
    {
        name: 'Feedback',
        value: props.stats.feedback,
        icon: ChatBubbleLeftRightIcon,
        href: route('admin.feedback.index'),
    },
]);

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
</script>
