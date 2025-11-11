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
                            @click="$router.visit(`/student/courses/${course.id}`)"
                            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ course.title }}</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Week {{ weekNumber }}: {{ currentWeek.formatted_title }}</p>
                        </div>
                    </div>

                    <!-- Right: Course Status -->
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ userProgress.completed_at ? 'Course Completed' : 'In Progress' }}
                        </span>
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
                            <div
                                v-for="prompt in course.course_prompts"
                                :key="prompt.id"
                                :class="[
                                    'w-full text-left p-3 rounded-lg',
                                    prompt.week_number === weekNumber 
                                        ? 'bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100 border border-blue-200 dark:border-blue-800' 
                                        : 'text-gray-900 dark:text-white'
                                ]"
                            >
                                <div class="flex items-center">
                                    <span :class="[
                                        'inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-medium mr-3',
                                        prompt.week_number === weekNumber 
                                            ? 'bg-blue-600 text-white'
                                            : 'bg-gray-100 dark:bg-gray-700 text-gray-600'
                                    ]">
                                        {{ prompt.week_number }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium truncate">{{ prompt.formatted_title }}</p>
                                    </div>
                                </div>
                            </div>
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
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ currentWeek.formatted_title }}</h2>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ currentWeek.description }}</p>
                                    
                                    <!-- Learning Objectives -->
                                    <div v-if="currentWeek.learning_objectives && currentWeek.learning_objectives.length > 0">
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
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Course Content</h3>
                                    <button
                                        v-if="!contentLoaded"
                                        @click="loadContent"
                                        :disabled="contentLoading"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <svg v-if="contentLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ contentLoading ? 'Loading Content...' : 'Start Learning' }}
                                    </button>
                                </div>

                                <!-- Content Output -->
                                <div 
                                    v-if="contentLoaded"
                                    class="prose dark:prose-invert max-w-none bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700"
                                >
                                    <div class="whitespace-pre-wrap">{{ contentText }}</div>
                                </div>
                            </div>


                            <!-- Course Completion -->
                            <div v-if="contentLoaded && !userProgress.completed_at" class="space-y-4">
                                <!-- Trivia Section -->
                                <div v-if="currentWeek.trivia_questions && currentWeek.trivia_questions.length > 0" class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
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
                                            <h5 class="font-medium text-gray-900 dark:text-white mb-3">{{ currentTriviaQuestion.question }}</h5>
                                            <div class="space-y-2">
                                                <button
                                                    v-for="(option, index) in currentTriviaQuestion.options"
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

                                <!-- Complete Course Button -->
                                <div class="text-center">
                                    <button
                                        @click="completeCourse"
                                        :disabled="completing || (currentWeek.trivia_questions?.length > 0 && !triviaCompleted)"
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <svg v-if="completing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ completing ? 'Completing...' : 'Complete Course' }}
                                    </button>
                                    <p v-if="currentWeek.trivia_questions?.length > 0 && !triviaCompleted" class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        Complete the quiz to finish the course
                                    </p>
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
import { ref, computed, onMounted, onUnmounted } from 'vue';
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

// Content state
const contentLoaded = ref(false);
const contentLoading = ref(false);
const contentText = ref('');

// Timer state
const timerActive = ref(false);
const studyTime = ref(0);
let timerInterval = null;

// Trivia state
const triviaStarted = ref(false);
const triviaCompleted = ref(false);
const currentTriviaIndex = ref(0);
const triviaAnswers = ref([]);
const selectedAnswer = ref(null);
const triviaScore = ref(0);

// Completion state
const completing = ref(false);

const currentTriviaQuestion = computed(() => {
    if (!props.currentWeek.trivia_questions || currentTriviaIndex.value >= props.currentWeek.trivia_questions.length) {
        return null;
    }
    return props.currentWeek.trivia_questions[currentTriviaIndex.value];
});

const formatTime = (seconds) => {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
};

const loadContent = async () => {
    contentLoading.value = true;
    contentText.value = '';
    
    try {
        const response = await fetch(`/student/courses/${props.course.id}/week/${props.weekNumber}/content`);
        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        
        let buffer = '';
        
        while (true) {
            const { done, value } = await reader.read();
            if (done) break;
            
            buffer += decoder.decode(value, { stream: true });
            const lines = buffer.split('\n');
            buffer = lines.pop(); // Keep incomplete line in buffer
            
            for (const line of lines) {
                if (line.startsWith('data: ')) {
                    try {
                        const data = JSON.parse(line.slice(6));
                        if (data.delta && data.delta.content) {
                            contentText.value += data.delta.content;
                        }
                        if (data.finish_reason === 'stop') {
                            contentLoaded.value = true;
                            startTimer();
                            break;
                        }
                    } catch (e) {
                        console.error('Error parsing SSE data:', e);
                    }
                }
            }
        }
    } catch (error) {
        console.error('Error loading content:', error);
        contentText.value = 'Error loading content. Please try again.';
    } finally {
        contentLoading.value = false;
    }
};

const startTimer = () => {
    timerActive.value = true;
    timerInterval = setInterval(() => {
        studyTime.value++;
    }, 1000);
};

const toggleTimer = () => {
    if (timerActive.value) {
        clearInterval(timerInterval);
        timerActive.value = false;
    } else {
        startTimer();
    }
};

const updateProgress = async () => {
    if (studyTime.value > 0) {
        try {
            await fetch(`/student/courses/${props.course.id}/progress`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    time_spent: Math.floor(studyTime.value / 60) // Convert to minutes
                })
            });
        } catch (error) {
            console.error('Error updating progress:', error);
        }
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
        
        // Check if answer is correct
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

const completeCourse = async () => {
    completing.value = true;
    
    try {
        const payload = {};
        
        if (triviaCompleted.value && triviaScore.value > 0) {
            payload.trivia_score = Math.round((triviaScore.value / props.currentWeek.trivia_questions.length) * 100);
        }
        
        const response = await fetch(`/student/courses/${props.course.id}/week/${props.weekNumber}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(payload)
        });
        
        const result = await response.json();
        
        if (result.success) {
            router.visit(`/student/courses/${props.course.id}`, {
                onSuccess: () => {
                    // Show completion celebration
                }
            });
        }
    } catch (error) {
        console.error('Error completing course:', error);
    } finally {
        completing.value = false;
    }
};

// Simplified since week navigation is disabled

// Simplified lifecycle since progress tracking is disabled
onMounted(() => {
    // Component initialization
});

onUnmounted(() => {
    // Cleanup
});
</script>