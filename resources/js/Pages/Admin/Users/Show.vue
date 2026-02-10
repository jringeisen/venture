<template>
    <AdminLayout title="User Details">
        <div class="space-y-6">
            <div class="mb-6">
                <Link :href="route('admin.users.index')" class="text-sm text-beach-text-light hover:text-beach-text">
                    &larr; Back to Users
                </Link>
            </div>

            <!-- User Info -->
            <div class="bg-white dark:bg-primary-gray shadow-sm dark:shadow-neutral-900/50 border border-slate-100 dark:border-neutral-700 rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-beach-text dark:text-neutral-200">User Information</h3>
                        <Link :href="route('admin.users.edit', user.id)" class="text-beach-teal hover:text-beach-teal-dark text-sm">
                            Edit
                        </Link>
                    </div>

                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-beach-text-light dark:text-neutral-400">Name</dt>
                            <dd class="mt-1 text-sm text-beach-text dark:text-neutral-200">{{ user.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-beach-text-light dark:text-neutral-400">Email</dt>
                            <dd class="mt-1 text-sm text-beach-text dark:text-neutral-200">{{ user.email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-beach-text-light dark:text-neutral-400">Type</dt>
                            <dd class="mt-1">
                                <span :class="user.parent_id ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    {{ user.parent_id ? 'Student' : 'Parent' }}
                                </span>
                            </dd>
                        </div>
                        <div v-if="user.parent">
                            <dt class="text-sm font-medium text-beach-text-light dark:text-neutral-400">Parent</dt>
                            <dd class="mt-1 text-sm text-beach-text dark:text-neutral-200">
                                <Link :href="route('admin.users.show', user.parent.id)" class="text-beach-teal hover:text-beach-teal-dark">
                                    {{ user.parent.name }}
                                </Link>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Students (if parent) -->
            <div v-if="user.students?.length" class="bg-white dark:bg-primary-gray shadow-sm dark:shadow-neutral-900/50 border border-slate-100 dark:border-neutral-700 rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-beach-text dark:text-neutral-200 mb-4">Students</h3>
                    <ul class="divide-y divide-gray-200 dark:divide-neutral-700">
                        <li v-for="student in user.students" :key="student.id" class="py-3">
                            <Link :href="route('admin.users.show', student.id)" class="flex items-center justify-between hover:bg-gray-50 dark:hover:bg-neutral-800 -mx-4 px-4 py-2 rounded">
                                <div>
                                    <p class="text-sm font-medium text-beach-text dark:text-neutral-200">{{ student.name }}</p>
                                    <p class="text-sm text-beach-text-light dark:text-neutral-400">{{ student.email }}</p>
                                </div>
                                <ChevronRightIcon class="h-5 w-5 text-gray-400 dark:text-neutral-400"/>
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Enrolled Courses -->
            <div v-if="user.enrolled_courses?.length" class="bg-white dark:bg-primary-gray shadow-sm dark:shadow-neutral-900/50 border border-slate-100 dark:border-neutral-700 rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-beach-text dark:text-neutral-200 mb-4">Enrolled Courses</h3>
                    <ul class="divide-y divide-gray-200 dark:divide-neutral-700">
                        <li v-for="course in user.enrolled_courses" :key="course.id" class="py-3">
                            <Link :href="route('admin.courses.edit', course.id)" class="flex items-center justify-between hover:bg-gray-50 dark:hover:bg-neutral-800 -mx-4 px-4 py-2 rounded">
                                <div>
                                    <p class="text-sm font-medium text-beach-text dark:text-neutral-200">{{ course.title }}</p>
                                </div>
                                <ChevronRightIcon class="h-5 w-5 text-gray-400 dark:text-neutral-400"/>
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ChevronRightIcon } from '@heroicons/vue/24/outline';

defineProps({
    user: Object,
});
</script>
