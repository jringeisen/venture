<template>
    <Head title="Courses" />

    <div class="min-h-screen bg-gray-50 dark:bg-primary-dark-gray py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-beach-text dark:text-neutral-200 mb-4">
                    Discover Courses
                </h1>
                <p class="text-gray-600 dark:text-neutral-400 max-w-2xl">
                    Learn with structured, week-by-week courses designed to help you master new subjects at your own pace.
                </p>
            </div>

            <!-- Age Warning Banner -->
            <div v-if="showAgeWarning" class="mb-8 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="shrink-0">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">
                            Set your age for personalized courses
                        </h3>
                        <p class="mt-1 text-sm text-amber-700 dark:text-amber-300">
                            We're showing you all available courses. To see courses designed for your age level, please update your profile.
                        </p>
                        <div class="mt-3">
                            <a
                                :href="route('profile.edit')"
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-amber-800 dark:text-amber-200 bg-amber-100 dark:bg-amber-800/50 rounded-md hover:bg-amber-200 dark:hover:bg-amber-800/70 transition-colors"
                            >
                                Update Profile
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                <div class="bg-white dark:bg-primary-gray rounded-lg p-4 border border-gray-200 dark:border-neutral-700">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <svg class="w-8 h-8 text-beach-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-neutral-400">Available Courses</p>
                            <p class="text-2xl font-bold text-beach-text dark:text-neutral-200">{{ courses.total }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-primary-gray rounded-lg p-4 border border-gray-200 dark:border-neutral-700">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-neutral-400">Enrolled</p>
                            <p class="text-2xl font-bold text-beach-text dark:text-neutral-200">{{ enrolledCourses.length }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="bg-white dark:bg-primary-gray rounded-xl shadow-sm dark:shadow-neutral-900/50 border border-gray-200 dark:border-neutral-700 p-4 sm:p-6 mb-8">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input
                        type="text"
                        v-model="searchQuery"
                        @input="handleSearch"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-neutral-700 rounded-lg focus:ring-2 focus:ring-beach-teal focus:border-transparent dark:bg-primary-gray dark:text-neutral-200 dark:placeholder-neutral-400"
                        placeholder="Search courses..."
                    />
                </div>

                <!-- Active Search Filter -->
                <div v-if="searchQuery" class="mt-4 flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-teal-100 dark:bg-teal-900 text-teal-800 dark:text-teal-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search: "{{ searchQuery }}"
                        <button @click="clearSearch" class="ml-2 hover:text-beach-teal dark:hover:text-teal-300">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </span>
                </div>
            </div>

            <!-- Course Catalog -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-beach-text dark:text-neutral-200">
                        All Courses
                        <span class="text-sm font-normal text-beach-text-light dark:text-neutral-400 ml-2">
                            ({{ courses.total }} courses)
                        </span>
                    </h2>
                </div>

                <!-- Course List -->
                <div v-if="courses.data.length > 0">
                    <div class="space-y-4">
                        <CourseListItem
                            v-for="course in courses.data"
                            :key="course.id"
                            :course="course"
                            :isEnrolled="isUserEnrolled(course.id)"
                            @enroll="handleEnroll"
                        />
                    </div>

                    <!-- Pagination -->
                    <div v-if="courses.last_page > 1" class="mt-8">
                        <div class="bg-white dark:bg-primary-gray rounded-xl shadow-sm dark:shadow-neutral-900/50 border border-gray-200 dark:border-neutral-700">
                            <nav class="flex items-center justify-between px-4 py-3 sm:px-6" aria-label="Pagination">
                                <div class="hidden sm:block">
                                    <p class="text-sm text-beach-text-light dark:text-neutral-400">
                                        Showing <span class="font-medium">{{ courses.from }}</span> to
                                        <span class="font-medium">{{ courses.to }}</span> of
                                        <span class="font-medium">{{ courses.total }}</span> courses
                                    </p>
                                </div>
                                <div class="flex flex-1 justify-between sm:justify-end space-x-3">
                                    <button
                                        @click="goToPage(courses.prev_page_url)"
                                        :disabled="!courses.prev_page_url"
                                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-neutral-700 bg-white dark:bg-primary-gray text-beach-text-light dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        Previous
                                    </button>
                                    <button
                                        @click="goToPage(courses.next_page_url)"
                                        :disabled="!courses.next_page_url"
                                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-neutral-700 bg-white dark:bg-primary-gray text-beach-text-light dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        Next
                                    </button>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="courses.data.length === 0" class="text-center py-16">
                    <div class="bg-white dark:bg-primary-gray rounded-xl shadow-sm dark:shadow-neutral-900/50 border border-gray-200 dark:border-neutral-700 p-8 sm:p-12">
                        <div class="max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-300 dark:text-neutral-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-beach-text dark:text-neutral-200 mb-3">
                                {{ searchQuery ? 'No matching courses found' : 'No courses available yet' }}
                            </h3>
                            <p class="text-gray-600 dark:text-neutral-400 mb-6">
                                {{ searchQuery ? 'Try adjusting your search criteria.' : 'New courses are being added regularly. Check back soon!' }}
                            </p>
                            <div class="space-y-3">
                                <button v-if="searchQuery" @click="clearSearch" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-neutral-700 text-sm font-medium rounded-lg text-beach-text-light dark:text-neutral-400 bg-white dark:bg-primary-gray hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                                    Clear All Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CourseListItem from '@/Components/CourseListItem.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    courses: Object,
    enrolledCourses: Array,
    filters: {
        type: Object,
        default: () => ({})
    },
    showAgeWarning: {
        type: Boolean,
        default: false,
    },
});

// Reactive state - initialize from server-side filters
const searchQuery = ref(props.filters?.search || '');
let searchTimeout = null;

// Helper functions
const isUserEnrolled = (courseId) => {
    return props.enrolledCourses.some(enrollment => enrollment.course_id === courseId);
};

// Server-side search with debouncing
const performSearch = () => {
    const params = {};
    if (searchQuery.value.trim()) {
        params.search = searchQuery.value.trim();
    }

    router.get('/student/courses', params, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Event handlers
const handleSearch = () => {
    // Debounce search to avoid too many requests
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    searchTimeout = setTimeout(() => {
        performSearch();
    }, 300);
};

const clearSearch = () => {
    searchQuery.value = '';
    performSearch();
};

const handleEnroll = (courseId) => {
    router.post(`/student/courses/${courseId}/enroll`);
};

const goToPage = (url) => {
    if (url) {
        router.get(url, {}, {
            preserveState: true,
            preserveScroll: true,
        });
    }
};
</script>
