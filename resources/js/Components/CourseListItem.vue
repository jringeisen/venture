<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all duration-200 group">
        <div class="p-6">
            <div class="flex items-start space-x-4">
                <!-- Course Image -->
                <div class="shrink-0">
                    <img
                        v-if="course.image_url"
                        :src="course.image_url"
                        :alt="course.title"
                        class="w-20 h-20 rounded-lg object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                    <div
                        v-else
                        class="w-20 h-20 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center group-hover:scale-105 transition-transform duration-300"
                    >
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>

                <!-- Course Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ course.title }}
                            </h3>
                            <div v-if="course.subject_category || course.difficulty_level || hasAgeGroup" class="flex items-center flex-wrap gap-2 mt-1">
                                <span v-if="course.subject_category" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                    {{ formatSubject(course.subject_category) }}
                                </span>
                                <span v-if="course.difficulty_level" :class="difficultyColorClasses(course.difficulty_level)" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium">
                                    {{ formatDifficulty(course.difficulty_level) }}
                                </span>
                                <span v-if="hasAgeGroup" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                    {{ ageGroupLabel }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 mb-3">
                        {{ course.description }}
                    </p>

                    <!-- Course Stats -->
                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ course.length_in_weeks || course.weeks_count || 0 }} weeks
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </svg>
                            {{ course.enrolled_count || 0 }} enrolled
                        </div>
                    </div>


                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between">
                        <button
                            @click="viewDetails"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium transition-colors"
                        >
                            View Details →
                        </button>
                        
                        <div v-if="isEnrolled" class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Enrolled
                            </span>
                            <button
                                @click="continueLearning"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors"
                            >
                                Continue Learning
                            </button>
                        </div>
                        
                        <button
                            v-else
                            @click="handleEnroll"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        >
                            Enroll Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    course: {
        type: Object,
        required: true
    },
    isEnrolled: {
        type: Boolean,
        default: false
    },
    recommended: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['enroll']);

const hasAgeGroup = computed(() => {
    return props.course.min_age !== null && props.course.max_age !== null;
});

const ageGroupLabel = computed(() => {
    if (!hasAgeGroup.value) return '';
    return `Ages ${props.course.min_age}-${props.course.max_age}`;
});

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

const handleEnroll = () => {
    emit('enroll', props.course.id);
};

const viewDetails = () => {
    router.visit(`/student/courses/${props.course.id}`);
};

const continueLearning = () => {
    router.visit(`/student/courses/${props.course.id}/learn`);
};
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>