<template>
    <Head :title="`${course.title} - Week ${weekNumber}, Day ${dayNumber}`" />

    <div class="min-h-screen bg-gray-50 dark:bg-primary-dark-gray">
        <!-- Header -->
        <div class="bg-white dark:bg-primary-gray border-b border-gray-200 dark:border-neutral-700 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Left: Course Info with Breadcrumbs -->
                    <div class="flex items-center space-x-4">
                        <button
                            @click="router.visit(`/student/courses/${course.id}`)"
                            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-neutral-400 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <div>
                            <!-- Breadcrumbs -->
                            <nav class="flex items-center space-x-1 text-sm text-beach-text-light dark:text-neutral-400 mb-0.5">
                                <button @click="router.visit('/student/courses')" class="hover:text-gray-700 dark:hover:text-neutral-200 transition-colors">Courses</button>
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                <button @click="router.visit(`/student/courses/${course.id}`)" class="hover:text-gray-700 dark:hover:text-neutral-200 transition-colors truncate max-w-[150px]">{{ course.title }}</button>
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                <span class="text-beach-text-light dark:text-neutral-200">Week {{ weekNumber }}, Day {{ dayNumber }}</span>
                            </nav>
                            <h1 class="text-lg font-semibold text-beach-text dark:text-neutral-200">
                                {{ currentDay?.title || currentWeek?.formatted_title || currentWeek?.title }}
                            </h1>
                        </div>
                    </div>

                    <!-- Right: Progress & Time -->
                    <div class="flex items-center space-x-4">
                        <!-- Estimated reading time -->
                        <div v-if="estimatedReadingTime" class="hidden md:flex items-center space-x-2 text-sm text-beach-text-light dark:text-neutral-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span>~{{ estimatedReadingTime }} min read</span>
                        </div>
                        <!-- Time tracking indicator -->
                        <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-600 dark:text-neutral-400">
                            <svg class="w-4 h-4" :class="{ 'text-green-500': isUserActive && isPageVisible, 'text-gray-400': !isUserActive || !isPageVisible }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ formattedElapsedTime }}</span>
                        </div>
                        <!-- Progress bar -->
                        <div class="hidden sm:flex items-center space-x-2">
                            <div class="w-32 bg-gray-200 dark:bg-neutral-700 rounded-full h-2">
                                <div
                                    class="bg-beach-teal h-2 rounded-full transition-all duration-300"
                                    :style="{ width: `${progressPercent}%` }"
                                ></div>
                            </div>
                            <span class="text-sm text-gray-600 dark:text-neutral-400">{{ progressPercent }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar: Course Navigation -->
                <div class="lg:col-span-1">
                    <CourseNavigationSidebar
                        :course-prompts="course.course_prompts"
                        :week-number="weekNumber"
                        :day-number="dayNumber"
                        :user-progress="userProgress"
                        :course-id="course.id"
                    />
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-primary-gray rounded-xl shadow-sm dark:shadow-neutral-900/50 border border-gray-200 dark:border-neutral-700 overflow-hidden">
                        <!-- Day Header -->
                        <div class="p-6 border-b border-gray-200 dark:border-neutral-700">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold text-beach-text dark:text-neutral-200 mb-2">
                                        {{ currentDay?.title || currentWeek?.formatted_title || currentWeek?.title }}
                                    </h2>
                                    <p class="text-gray-600 dark:text-neutral-400 mb-4">
                                        {{ currentDay?.description || currentWeek?.description }}
                                    </p>

                                    <!-- Learning Objectives -->
                                    <div v-if="activeObjectives && activeObjectives.length > 0">
                                        <h3 class="text-sm font-semibold text-beach-text dark:text-neutral-200 mb-2">Today's Objectives:</h3>
                                        <div class="flex flex-wrap gap-2">
                                            <span
                                                v-for="objective in activeObjectives"
                                                :key="objective"
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-teal-50 dark:bg-teal-900/30 text-beach-teal-dark dark:text-teal-300"
                                            >
                                                {{ objective }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="p-6">
                            <!-- Content Display -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-beach-text dark:text-neutral-200 mb-4">Lesson Content</h3>

                                <!-- Content Output -->
                                <div
                                    v-if="sanitizedContent"
                                    class="prose dark:prose-invert max-w-none bg-gray-50 dark:bg-primary-dark-gray rounded-lg p-6 border border-gray-200 dark:border-neutral-700"
                                    v-html="sanitizedContent"
                                ></div>

                                <!-- No Content Message -->
                                <div
                                    v-else
                                    class="bg-gray-50 dark:bg-primary-dark-gray rounded-lg p-6 border border-gray-200 dark:border-neutral-700 text-center"
                                >
                                    <p class="text-beach-text-light dark:text-neutral-400">Content for this lesson is coming soon.</p>
                                </div>
                            </div>


                            <!-- Day Completion Section -->
                            <div v-if="sanitizedContent && !isDayCompleted(weekNumber, dayNumber)" class="space-y-4">
                                <!-- Trivia Section -->
                                <TriviaQuiz
                                    ref="triviaQuizRef"
                                    :questions="activeTrivia"
                                    @completed="onTriviaCompleted"
                                />

                                <!-- Complete Day Button -->
                                <div class="text-center">
                                    <button
                                        @click="completeDay"
                                        :disabled="completing || (activeTrivia?.length > 0 && !triviaIsCompleted)"
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <svg v-if="completing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ completing ? 'Completing...' : isLastDay ? 'Complete Course' : isLastDayOfWeek ? 'Complete Week' : 'Complete Day & Continue' }}
                                    </button>
                                    <p v-if="activeTrivia?.length > 0 && !triviaIsCompleted" class="text-sm text-beach-text-light dark:text-neutral-400 mt-2">
                                        Complete the quiz to continue
                                    </p>
                                </div>
                            </div>

                            <!-- Already Completed Message -->
                            <div v-else-if="isDayCompleted(weekNumber, dayNumber) && sanitizedContent" class="text-center py-8">
                                <div class="text-4xl mb-4">✅</div>
                                <h3 class="text-lg font-semibold text-beach-text dark:text-neutral-200 mb-2">Lesson Completed!</h3>
                                <p class="text-gray-600 dark:text-neutral-400 mb-4">You've already completed this lesson.</p>
                            </div>

                            <!-- Bottom Navigation -->
                            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-neutral-700">
                                <div class="flex items-center justify-between">
                                    <button
                                        v-if="hasPreviousDay"
                                        @click="navigateToPreviousDay"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-beach-text-light dark:text-neutral-400 bg-white dark:bg-primary-gray border border-gray-300 dark:border-neutral-700 rounded-lg hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors"
                                    >
                                        <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        Previous Lesson
                                    </button>
                                    <div v-else></div>

                                    <button
                                        v-if="hasNextDay && canAccessNextDay"
                                        @click="navigateToNextDay"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-beach-teal border border-transparent rounded-lg hover:bg-beach-teal-dark transition-colors"
                                    >
                                        Next Lesson
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                    <button
                                        v-else-if="!hasNextDay && isDayCompleted(weekNumber, dayNumber)"
                                        @click="router.visit(`/student/courses/${course.id}`)"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 transition-colors"
                                    >
                                        <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Course Complete - View Summary
                                    </button>
                                    <div v-else></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Milestone Celebration Modal -->
    <MilestoneCelebration
        :show="showMilestoneModal"
        :emoji="milestoneEmoji"
        :title="milestoneTitle"
        :message="milestoneMessage"
        @close="closeMilestoneModal"
    />
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import DOMPurify from 'dompurify';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CourseNavigationSidebar from '@/Components/CourseNavigationSidebar.vue';
import TriviaQuiz from '@/Components/TriviaQuiz.vue';
import MilestoneCelebration from '@/Components/MilestoneCelebration.vue';
import { useTimeTracking } from '@/Composables/useTimeTracking.js';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    course: Object,
    currentWeek: Object,
    currentDay: Object,
    userProgress: Object,
    weekNumber: Number,
    dayNumber: Number,
    canAdvance: Boolean,
    isLastWeek: Boolean,
    isLastDay: Boolean,
    isLastDayOfWeek: Boolean,
    totalDays: Number,
});

// Time tracking composable
const {
    isPageVisible,
    isUserActive,
    formattedElapsedTime,
    startLearningSession,
    endLearningSession,
} = useTimeTracking(
    computed(() => props.course.id),
    computed(() => props.weekNumber),
    computed(() => props.dayNumber),
);

// Trivia ref
const triviaQuizRef = ref(null);
const triviaIsCompleted = ref(false);
const triviaScorePercent = ref(0);

// Completion state
const completing = ref(false);

// Milestone celebration state
const showMilestoneModal = ref(false);
const milestoneEmoji = ref('');
const milestoneTitle = ref('');
const milestoneMessage = ref('');

// Watch for week/day changes and reset state
watch([() => props.weekNumber, () => props.dayNumber], ([newWeek, newDay], [oldWeek, oldDay]) => {
    if (newWeek !== oldWeek || newDay !== oldDay) {
        // Reset trivia state
        triviaIsCompleted.value = false;
        triviaScorePercent.value = 0;

        // Reset completion state
        completing.value = false;

        // End old session and start new one
        endLearningSession().then(() => {
            startLearningSession();
        });
    }
});

// Computed
const totalWeeks = computed(() => props.course?.course_prompts?.length || props.course?.length_in_weeks || 1);

const progressPercent = computed(() => {
    const currentWeek = props.userProgress?.current_week || 1;
    const currentDay = props.userProgress?.current_day || 1;

    let daysCompleted = 0;
    let totalDaysCount = 0;

    for (const prompt of props.course?.course_prompts || []) {
        const daysInWeek = prompt.days_count || prompt.days?.length || 1;
        totalDaysCount += daysInWeek;

        if (prompt.week_number < currentWeek) {
            daysCompleted += daysInWeek;
        } else if (prompt.week_number === currentWeek) {
            daysCompleted += Math.max(0, currentDay - 1);
        }
    }

    if (totalDaysCount === 0) return 0;
    return Math.min(100, Math.round((daysCompleted / totalDaysCount) * 100));
});

const sanitizedContent = computed(() => {
    const content = props.currentDay?.content || props.currentWeek?.content;
    if (!content) return '';
    return DOMPurify.sanitize(content, {
        ALLOWED_TAGS: ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'br', 'ul', 'ol', 'li', 'strong', 'em', 'b', 'i', 'u', 's', 'strike', 'a', 'img', 'blockquote', 'pre', 'code', 'hr', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'span', 'div'],
        ALLOWED_ATTR: ['href', 'src', 'alt', 'title', 'class', 'target', 'rel'],
    });
});

const activeObjectives = computed(() => {
    return props.currentDay?.learning_objectives || props.currentWeek?.learning_objectives || [];
});

const activeTrivia = computed(() => {
    return props.currentDay?.trivia_questions || props.currentWeek?.trivia_questions || [];
});

const estimatedReadingTime = computed(() => {
    const duration = props.currentDay?.estimated_duration_minutes || props.currentWeek?.estimated_duration_minutes;
    if (duration) return duration;

    const content = props.currentDay?.content || props.currentWeek?.content;
    if (content) {
        const wordCount = content.replace(/<[^>]*>/g, '').split(/\s+/).length;
        return Math.max(1, Math.ceil(wordCount / 200));
    }
    return null;
});

// Navigation helpers
const canAccessDay = (week, day) => {
    if (week < props.userProgress?.current_week) return true;
    if (week === props.userProgress?.current_week) {
        return day <= (props.userProgress?.current_day || 1);
    }
    return false;
};

const isDayCompleted = (week, day) => {
    if (week < props.userProgress?.current_week) return true;
    if (week === props.userProgress?.current_week) {
        return day < props.userProgress?.current_day;
    }
    return false;
};

const hasPreviousDay = computed(() => {
    if (props.dayNumber > 1) return true;
    if (props.weekNumber > 1) return true;
    return false;
});

const hasNextDay = computed(() => {
    const currentWeekPrompt = props.course.course_prompts?.find(p => p.week_number === props.weekNumber);
    const totalDaysInWeek = currentWeekPrompt?.days_count || currentWeekPrompt?.days?.length || 1;

    if (props.dayNumber < totalDaysInWeek) return true;
    if (props.weekNumber < totalWeeks.value) return true;
    return false;
});

const canAccessNextDay = computed(() => {
    const currentWeekPrompt = props.course.course_prompts?.find(p => p.week_number === props.weekNumber);
    const totalDaysInWeek = currentWeekPrompt?.days_count || currentWeekPrompt?.days?.length || 1;

    if (props.dayNumber < totalDaysInWeek) {
        return canAccessDay(props.weekNumber, props.dayNumber + 1);
    }
    if (props.weekNumber < totalWeeks.value) {
        return canAccessDay(props.weekNumber + 1, 1);
    }
    return false;
});

// Methods
const navigateToPreviousDay = () => {
    if (props.dayNumber > 1) {
        router.visit(`/student/courses/${props.course.id}/learn/${props.weekNumber}/${props.dayNumber - 1}`);
    } else if (props.weekNumber > 1) {
        const prevWeekPrompt = props.course.course_prompts?.find(p => p.week_number === props.weekNumber - 1);
        const lastDayOfPrevWeek = prevWeekPrompt?.days_count || prevWeekPrompt?.days?.length || 1;
        router.visit(`/student/courses/${props.course.id}/learn/${props.weekNumber - 1}/${lastDayOfPrevWeek}`);
    }
};

const navigateToNextDay = () => {
    const currentWeekPrompt = props.course.course_prompts?.find(p => p.week_number === props.weekNumber);
    const totalDaysInWeek = currentWeekPrompt?.days_count || currentWeekPrompt?.days?.length || 1;

    if (props.dayNumber < totalDaysInWeek) {
        router.visit(`/student/courses/${props.course.id}/learn/${props.weekNumber}/${props.dayNumber + 1}`);
    } else if (props.weekNumber < totalWeeks.value) {
        router.visit(`/student/courses/${props.course.id}/learn/${props.weekNumber + 1}/1`);
    }
};

// Trivia event handler
const onTriviaCompleted = ({ score, total, scorePercent }) => {
    triviaIsCompleted.value = true;
    triviaScorePercent.value = scorePercent;
};

// Milestone celebration functions
const showMilestoneCelebration = (emoji, title, message) => {
    milestoneEmoji.value = emoji;
    milestoneTitle.value = title;
    milestoneMessage.value = message;
    showMilestoneModal.value = true;
};

const closeMilestoneModal = () => {
    showMilestoneModal.value = false;
};

const completeDay = () => {
    completing.value = true;

    const payload = {};

    if (triviaIsCompleted.value && triviaScorePercent.value > 0) {
        payload.trivia_score = triviaScorePercent.value;
    }

    router.post(`/student/courses/${props.course.id}/week/${props.weekNumber}/day/${props.dayNumber}/complete`, payload, {
        onSuccess: () => {
            // Milestone check handled by server redirect
        },
        onError: () => {
            completing.value = false;
        },
    });
};
</script>
