<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all duration-200 group">
        <!-- Course Image -->
        <div class="relative h-48 overflow-hidden">
            <img 
                :src="course.image_url || '/images/default-course.jpg'" 
                :alt="course.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
            <div class="absolute top-3 left-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                    General
                </span>
            </div>
            <div v-if="recommended" class="absolute top-3 right-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Recommended
                </span>
            </div>
        </div>

        <!-- Course Content -->
        <div class="p-6">
            <div class="flex items-start justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    {{ course.title }}
                </h3>
                <div class="ml-2 flex-shrink-0">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                        Beginner
                    </span>
                </div>
            </div>

            <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4">
                {{ course.description }}
            </p>

            <!-- Course Stats -->
            <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                <div class="flex items-center text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ course.length_in_weeks }} weeks
                </div>
                <div class="flex items-center text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                    </svg>
                    {{ course.enrolled_count || 0 }} enrolled
                </div>
            </div>


            <!-- Action Buttons -->
            <div class="flex items-center justify-between">
                <button
                    @click="$router.visit(`/student/courses/${course.id}`)"
                    class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium transition-colors"
                >
                    View Details
                </button>
                
                <div v-if="isEnrolled" class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Enrolled
                    </span>
                    <button
                        @click="$router.visit(`/student/courses/${course.id}/learn`)"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors"
                    >
                        Continue
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
</template>

<script setup>
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