<template>
    <Head title="Courses" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Discover Courses
                </h1>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl">
                    Learn with structured, week-by-week courses designed to help you master new subjects at your own pace.
                </p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Available Courses</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ courses.total }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Enrolled</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ enrolledCourses.length }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-8">
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
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                        placeholder="Search courses..."
                    />
                </div>

                <!-- Active Search Filter -->
                <div v-if="searchQuery" class="mt-4 flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search: "{{ searchQuery }}"
                        <button @click="clearSearch" class="ml-2 hover:text-blue-600 dark:hover:text-blue-300">
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
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        All Courses
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">
                            ({{ filteredCourses.length }} courses)
                        </span>
                    </h2>
                </div>

                <!-- Course List -->
                <div v-if="filteredCourses.length > 0">
                    <div class="space-y-4">
                        <CourseListItem
                            v-for="course in paginatedCourses"
                            :key="course.id"
                            :course="course"
                            :isEnrolled="isUserEnrolled(course.id)"
                            @enroll="handleEnroll"
                        />
                    </div>

                    <!-- Pagination -->
                    <div v-if="totalPages > 1" class="mt-8">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <nav class="flex items-center justify-between px-4 py-3 sm:px-6" aria-label="Pagination">
                                <div class="hidden sm:block">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        Showing <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span> to 
                                        <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredCourses.length) }}</span> of 
                                        <span class="font-medium">{{ filteredCourses.length }}</span> courses
                                    </p>
                                </div>
                                <div class="flex flex-1 justify-between sm:justify-end space-x-3">
                                    <button
                                        @click="previousPage"
                                        :disabled="currentPage === 1"
                                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        Previous
                                    </button>
                                    <button
                                        @click="nextPage"
                                        :disabled="currentPage === totalPages"
                                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        Next
                                    </button>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-16">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 sm:p-12">
                        <div class="max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                                {{ searchQuery ? 'No matching courses found' : 'No courses available yet' }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                {{ searchQuery ? 'Try adjusting your search criteria.' : 'New courses are being added regularly. Check back soon!' }}
                            </p>
                            <div class="space-y-3">
                                <button v-if="searchQuery" @click="clearSearch" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
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
import { ref, computed, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CourseListItem from '@/Components/CourseListItem.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    courses: Object,
    enrolledCourses: Array,
});

// Reactive state
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 12;

// Computed properties
const filteredCourses = computed(() => {
    let filtered = [...props.courses.data];

    // Apply search filter
    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(course =>
            course.title.toLowerCase().includes(query) ||
            course.description?.toLowerCase().includes(query)
        );
    }

    return filtered;
});

const totalPages = computed(() => {
    return Math.ceil(filteredCourses.value.length / itemsPerPage);
});

const paginatedCourses = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredCourses.value.slice(start, end);
});

// Helper functions
const isUserEnrolled = (courseId) => {
    return props.enrolledCourses.some(enrollment => enrollment.course_id === courseId);
};

// Event handlers
const handleSearch = () => {
    currentPage.value = 1;
};

const clearSearch = () => {
    searchQuery.value = '';
    currentPage.value = 1;
};

const handleEnroll = (courseId) => {
    router.post(`/student/courses/${courseId}/enroll`);
};

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

// Watch for search changes to reset page
watch(searchQuery, () => {
    currentPage.value = 1;
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>