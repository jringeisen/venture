<template>
    <Head :title="`${course.title} - Week ${weekNumber}`" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Left: Course Info -->
                    <div class="flex items-center space-x-4">
                        <button
                            @click="router.visit(`/student/courses/${course.id}`)"
                            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ course.title }}</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Week {{ weekNumber }} of {{ totalWeeks }}: {{ currentWeek?.formatted_title || currentWeek?.title }}</p>
                        </div>
                    </div>

                    <!-- Right: Progress -->
                    <div class="flex items-center space-x-4">
                        <div class="hidden sm:flex items-center space-x-2">
                            <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div
                                    class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                    :style="{ width: `${progressPercent}%` }"
                                ></div>
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ progressPercent }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar: Course Navigation -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Course Weeks</h3>
                        <div class="space-y-2">
                            <button
                                v-for="prompt in course.course_prompts"
                                :key="prompt.id"
                                @click="navigateToWeek(prompt.week_number)"
                                :disabled="!canAccessWeek(prompt.week_number)"
                                :class="[
                                    'w-full text-left p-3 rounded-lg transition-colors',
                                    prompt.week_number === weekNumber
                                        ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100 border border-blue-200 dark:border-blue-800'
                                        : canAccessWeek(prompt.week_number)
                                            ? 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-white cursor-pointer'
                                            : 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                ]"
                            >
                                <div class="flex items-center">
                                    <span :class="[
                                        'inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-medium mr-3',
                                        prompt.week_number === weekNumber
                                            ? 'bg-blue-600 text-white'
                                            : prompt.week_number < userProgress.current_week
                                                ? 'bg-green-500 text-white'
                                                : canAccessWeek(prompt.week_number)
                                                    ? 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300'
                                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-400'
                                    ]">
                                        <svg v-if="prompt.week_number < userProgress.current_week" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span v-else>{{ prompt.week_number }}</span>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium truncate">{{ prompt.formatted_title || prompt.title }}</p>
                                    </div>
                                    <svg v-if="!canAccessWeek(prompt.week_number)" class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <!-- Week Header -->
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ currentWeek?.formatted_title || currentWeek?.title }}</h2>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ currentWeek?.description }}</p>

                                    <!-- Learning Objectives -->
                                    <div v-if="currentWeek?.learning_objectives && currentWeek.learning_objectives.length > 0">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">This Week's Objectives:</h3>
                                        <div class="flex flex-wrap gap-2">
                                            <span
                                                v-for="objective in currentWeek.learning_objectives"
                                                :key="objective"
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300"
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
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Lesson Content</h3>

                                <!-- Content Output -->
                                <div
                                    v-if="sanitizedContent"
                                    class="prose dark:prose-invert max-w-none bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700"
                                    v-html="sanitizedContent"
                                ></div>

                                <!-- No Content Message -->
                                <div
                                    v-else
                                    class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700 text-center"
                                >
                                    <p class="text-gray-500 dark:text-gray-400">Content for this week is coming soon.</p>
                                </div>
                            </div>


                            <!-- Week Completion Section -->
                            <div v-if="sanitizedContent && !isWeekCompleted" class="space-y-4">
                                <!-- Trivia Section -->
                                <div v-if="currentWeek?.trivia_questions && currentWeek.trivia_questions.length > 0" class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Test Your Knowledge</h3>

                                    <div v-if="!triviaStarted" class="text-center">
                                        <p class="text-gray-600 dark:text-gray-400 mb-4">Ready to test what you've learned this week?</p>
                                        <button
                                            @click="startTrivia"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 transition-colors"
                                        >
                                            Start Quiz ({{ currentWeek.trivia_questions.length }} questions)
                                        </button>
                                    </div>

                                    <div v-else-if="!triviaCompleted" class="space-y-4">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="font-medium text-gray-900 dark:text-white">Question {{ currentTriviaIndex + 1 }} of {{ currentWeek.trivia_questions.length }}</h4>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                Score: {{ triviaScore }} / {{ currentTriviaIndex }}
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                            <h5 class="font-medium text-gray-900 dark:text-white mb-3">{{ currentTriviaQuestion?.question }}</h5>
                                            <div class="space-y-2">
                                                <button
                                                    v-for="(option, index) in currentTriviaQuestion?.options"
                                                    :key="index"
                                                    @click="selectAnswer(index)"
                                                    :class="[
                                                        'w-full text-left p-3 rounded-lg border transition-colors',
                                                        selectedAnswer === index
                                                            ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 text-blue-900 dark:text-blue-100'
                                                            : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-900 dark:text-white'
                                                    ]"
                                                >
                                                    {{ option }}
                                                </button>
                                            </div>

                                            <div class="mt-4 flex justify-end">
                                                <button
                                                    @click="nextTriviaQuestion"
                                                    :disabled="selectedAnswer === null"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                                >
                                                    {{ currentTriviaIndex === currentWeek.trivia_questions.length - 1 ? 'Finish Quiz' : 'Next Question' }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-else class="text-center space-y-4">
                                        <div class="text-4xl">{{ triviaScore >= currentWeek.trivia_questions.length * 0.7 ? '🎉' : '📚' }}</div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Quiz Complete!</h4>
                                            <p class="text-gray-600 dark:text-gray-400">You scored {{ triviaScore }} out of {{ currentWeek.trivia_questions.length }} ({{ Math.round((triviaScore / currentWeek.trivia_questions.length) * 100) }}%)</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Complete Week Button -->
                                <div class="text-center">
                                    <button
                                        @click="completeWeek"
                                        :disabled="completing || (currentWeek?.trivia_questions?.length > 0 && !triviaCompleted)"
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <svg v-if="completing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ completing ? 'Completing...' : isLastWeek ? 'Complete Course' : 'Complete Week & Continue' }}
                                    </button>
                                    <p v-if="currentWeek?.trivia_questions?.length > 0 && !triviaCompleted" class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        Complete the quiz to continue
                                    </p>
                                </div>
                            </div>

                            <!-- Already Completed Message -->
                            <div v-else-if="isWeekCompleted && sanitizedContent" class="text-center py-8">
                                <div class="text-4xl mb-4">✅</div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Week Completed!</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">You've already completed this week's lesson.</p>
                                <button
                                    v-if="weekNumber < totalWeeks"
                                    @click="navigateToWeek(weekNumber + 1)"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors"
                                >
                                    Continue to Week {{ weekNumber + 1 }}
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
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
import { ref, computed } from 'vue';
import DOMPurify from 'dompurify';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    course: Object,
    currentWeek: Object,
    userProgress: Object,
    weekNumber: Number,
    canAdvance: Boolean,
    isLastWeek: Boolean,
});

// Trivia state
const triviaStarted = ref(false);
const triviaCompleted = ref(false);
const currentTriviaIndex = ref(0);
const triviaAnswers = ref([]);
const selectedAnswer = ref(null);
const triviaScore = ref(0);

// Completion state
const completing = ref(false);

// Computed
const totalWeeks = computed(() => props.course?.length_in_weeks || props.course?.course_prompts?.length || 1);

const progressPercent = computed(() => {
    const currentWeek = props.userProgress?.current_week || 1;
    // Use current_week / totalWeeks for accurate progress (week 1 of 5 = 20%, week 5 of 5 = 100%)
    return Math.min(100, Math.round((currentWeek / totalWeeks.value) * 100));
});

// Sanitize HTML content to prevent XSS attacks
const sanitizedContent = computed(() => {
    if (!props.currentWeek?.content) {
        return '';
    }
    return DOMPurify.sanitize(props.currentWeek.content, {
        ALLOWED_TAGS: ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'br', 'ul', 'ol', 'li', 'strong', 'em', 'b', 'i', 'u', 's', 'strike', 'a', 'img', 'blockquote', 'pre', 'code', 'hr', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'span', 'div'],
        ALLOWED_ATTR: ['href', 'src', 'alt', 'title', 'class', 'target', 'rel'],
    });
});

const isWeekCompleted = computed(() => {
    return props.weekNumber < (props.userProgress?.current_week || 1);
});

const isLastWeek = computed(() => {
    return props.weekNumber >= totalWeeks.value;
});

const currentTriviaQuestion = computed(() => {
    if (!props.currentWeek?.trivia_questions || currentTriviaIndex.value >= props.currentWeek.trivia_questions.length) {
        return null;
    }
    return props.currentWeek.trivia_questions[currentTriviaIndex.value];
});

// Methods
const canAccessWeek = (week) => {
    return week <= (props.userProgress?.current_week || 1);
};

const navigateToWeek = (week) => {
    if (canAccessWeek(week)) {
        router.visit(`/student/courses/${props.course.id}/learn/${week}`);
    }
};

const startTrivia = () => {
    triviaStarted.value = true;
    currentTriviaIndex.value = 0;
    triviaAnswers.value = [];
    selectedAnswer.value = null;
    triviaScore.value = 0;
};

const selectAnswer = (answerIndex) => {
    selectedAnswer.value = answerIndex;
};

const nextTriviaQuestion = () => {
    if (selectedAnswer.value !== null) {
        triviaAnswers.value[currentTriviaIndex.value] = selectedAnswer.value;

        if (selectedAnswer.value === currentTriviaQuestion.value.correct_answer) {
            triviaScore.value++;
        }

        if (currentTriviaIndex.value === props.currentWeek.trivia_questions.length - 1) {
            triviaCompleted.value = true;
        } else {
            currentTriviaIndex.value++;
            selectedAnswer.value = null;
        }
    }
};

const completeWeek = () => {
    completing.value = true;

    const payload = {};

    if (triviaCompleted.value && triviaScore.value > 0 && props.currentWeek?.trivia_questions?.length > 0) {
        payload.trivia_score = Math.round((triviaScore.value / props.currentWeek.trivia_questions.length) * 100);
    }

    router.post(`/student/courses/${props.course.id}/week/${props.weekNumber}/complete`, payload, {
        onError: (errors) => {
            console.error('Error completing week:', errors);
            completing.value = false;
        },
    });
};
</script>
