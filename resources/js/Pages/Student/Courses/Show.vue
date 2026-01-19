<template>
    <Head :title="course.title" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <button
                    @click="router.visit('/student/courses')"
                    class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Courses
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Course Header -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                        <div class="relative h-64 overflow-hidden">
                            <img 
                                :src="course.image_url || '/images/default-course.jpg'" 
                                :alt="course.title"
                                class="w-full h-full object-cover"
                            />
                            <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <div class="flex items-center space-x-3 mb-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 text-white">
                                        General
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        Beginner
                                    </span>
                                </div>
                                <h1 class="text-3xl font-bold text-white mb-2">{{ course.title }}</h1>
                                <p class="text-gray-200 text-lg">{{ course.description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Course Overview -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Course Overview</h2>
                        
                        <!-- Quick Stats -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ course.length_in_weeks }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Weeks</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ statistics.enrolled_count || 0 }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ statistics.completion_rate || 0 }}%</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Completion</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Course Curriculum -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Course Curriculum</h2>
                        
                        <div class="space-y-4">
                            <div 
                                v-for="prompt in course.course_prompts" 
                                :key="prompt.id"
                                class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 text-sm font-medium mr-3">
                                                {{ prompt.week_number }}
                                            </span>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ prompt.formatted_title }}</h3>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-400 mb-3">{{ prompt.description }}</p>
                                        
                                        <!-- Week Learning Objectives -->
                                        <div v-if="prompt.learning_objectives && prompt.learning_objectives.length > 0" class="mb-3">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">This week covers:</h4>
                                            <div class="flex flex-wrap gap-2">
                                                <span 
                                                    v-for="objective in prompt.learning_objectives.slice(0, 3)" 
                                                    :key="objective"
                                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300"
                                                >
                                                    {{ objective }}
                                                </span>
                                                <span v-if="prompt.learning_objectives.length > 3" class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                                    +{{ prompt.learning_objectives.length - 3 }} more
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Week Status -->
                                    <div class="ml-4 shrink-0">
                                        <span v-if="isEnrolled && userProgress && userProgress.current_week > prompt.week_number" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Completed
                                        </span>
                                        <span v-else-if="isEnrolled && userProgress && userProgress.current_week === prompt.week_number" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                            Current
                                        </span>
                                        <span v-else-if="!isEnrolled || !userProgress || userProgress.current_week < prompt.week_number" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                            Locked
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Enrollment Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6 sticky top-6">
                        <div v-if="isEnrolled" class="space-y-4">
                            <div class="text-center">
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 mb-4">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Enrolled
                                </div>
                            </div>

                            <!-- Progress -->
                            <div v-if="userProgress">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Enrollment Status</h3>
                                <div class="space-y-3">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <p v-if="userProgress.completed_at">
                                            Completed on {{ new Date(userProgress.completed_at).toLocaleDateString() }}
                                        </p>
                                        <p v-else>
                                            Started on {{ new Date(userProgress.started_at || userProgress.created_at).toLocaleDateString() }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <button
                                    @click="continueLearning"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a2.5 2.5 0 010 5H9m0-5v5m0-5H7.5a2.5 2.5 0 00-2.5 2.5v1"/>
                                    </svg>
                                    Continue Learning
                                </button>
                                
                                <button
                                    @click="router.visit('/student/courses/enrolled')"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                >
                                    View All Courses
                                </button>
                            </div>
                        </div>

                        <div v-else class="text-center space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ready to Start Learning?</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Join thousands of students mastering new skills.</p>
                            
                            <button
                                @click="enrollInCourse"
                                :disabled="enrolling"
                                class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <svg v-if="enrolling" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ enrolling ? 'Enrolling...' : 'Enroll Now - Free' }}
                            </button>
                            
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Free enrollment • Start immediately • Learn at your own pace
                            </p>
                        </div>
                    </div>

                    <!-- Course Features -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Course Features</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">AI-Powered Learning</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Personalized content adapted to your learning style</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Weekly Trivia</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Test your knowledge with interactive quizzes</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Progress Tracking</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Monitor your learning journey and achievements</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Self-Paced</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Learn at your own speed, whenever you want</p>
                                </div>
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
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    course: Object,
    userProgress: Object,
    statistics: Object,
    isEnrolled: Boolean,
});

const enrolling = ref(false);

const formatSubject = (subject) => {
    if (!subject) return 'General';
    return subject.charAt(0).toUpperCase() + subject.slice(1).toLowerCase();
};

const formatDifficulty = (difficulty) => {
    if (!difficulty) return 'Beginner';
    return difficulty.charAt(0).toUpperCase() + difficulty.slice(1).toLowerCase();
};

const difficultyColorClasses = (difficulty) => {
    const colors = {
        beginner: 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
        intermediate: 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
        advanced: 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'
    };
    return colors[difficulty?.toLowerCase()] || colors.beginner;
};

const formatTime = (minutes) => {
    if (!minutes) return '0 min';
    if (minutes < 60) return `${minutes} min`;
    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;
    return remainingMinutes > 0 ? `${hours}h ${remainingMinutes}m` : `${hours}h`;
};

const enrollInCourse = async () => {
    enrolling.value = true;
    try {
        router.post(`/student/courses/${props.course.id}/enroll`);
    } catch (error) {
        console.error('Enrollment failed:', error);
    } finally {
        enrolling.value = false;
    }
};

const continueLearning = () => {
    const week = props.userProgress?.current_week || 1;
    router.visit(`/student/courses/${props.course.id}/learn/${week}`);
};
</script>