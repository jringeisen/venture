<template>
    <div v-if="questions && questions.length > 0" class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-beach-text dark:text-white mb-4">Test Your Knowledge</h3>

        <div v-if="!triviaStarted" class="text-center">
            <p class="text-gray-600 dark:text-gray-400 mb-4">Ready to test what you've learned today?</p>
            <button
                @click="startTrivia"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 transition-colors"
            >
                Start Quiz ({{ questions.length }} questions)
            </button>
        </div>

        <div v-else-if="!triviaCompleted" class="space-y-4">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-medium text-beach-text dark:text-white">Question {{ currentTriviaIndex + 1 }} of {{ questions.length }}</h4>
                <div class="flex items-center space-x-3">
                    <div class="flex space-x-1">
                        <span
                            v-for="(_, idx) in questions"
                            :key="idx"
                            :class="[
                                'w-2 h-2 rounded-full',
                                idx < currentTriviaIndex
                                    ? (triviaAnswers[idx] === questions[idx].correct_answer ? 'bg-green-500' : 'bg-red-500')
                                    : idx === currentTriviaIndex
                                        ? 'bg-beach-teal'
                                        : 'bg-gray-300 dark:bg-gray-600'
                            ]"
                        ></span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                <h5 class="font-medium text-beach-text dark:text-white mb-3">{{ currentTriviaQuestion?.question }}</h5>
                <div class="space-y-2">
                    <button
                        v-for="(option, index) in currentTriviaQuestion?.options"
                        :key="index"
                        @click="selectAnswer(index)"
                        :disabled="answerRevealed"
                        :class="[
                            'w-full text-left p-3 rounded-lg border transition-all',
                            answerRevealed && index === currentTriviaQuestion?.correct_answer
                                ? 'border-green-500 bg-green-50 dark:bg-green-900/20 text-green-900 dark:text-green-100 ring-2 ring-green-500'
                                : answerRevealed && selectedAnswer === index && index !== currentTriviaQuestion?.correct_answer
                                    ? 'border-red-500 bg-red-50 dark:bg-red-900/20 text-red-900 dark:text-red-100'
                                    : selectedAnswer === index && !answerRevealed
                                        ? 'border-beach-teal bg-teal-50 dark:bg-teal-900/20 text-teal-900 dark:text-teal-100'
                                        : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-beach-text dark:text-white',
                            answerRevealed ? 'cursor-default' : ''
                        ]"
                    >
                        <div class="flex items-center justify-between">
                            <span>{{ option }}</span>
                            <svg v-if="answerRevealed && index === currentTriviaQuestion?.correct_answer" class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <svg v-else-if="answerRevealed && selectedAnswer === index && index !== currentTriviaQuestion?.correct_answer" class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </button>
                </div>

                <!-- Answer Feedback -->
                <div v-if="answerRevealed" class="mt-4 p-3 rounded-lg" :class="selectedAnswer === currentTriviaQuestion?.correct_answer ? 'bg-green-100 dark:bg-green-900/30' : 'bg-amber-100 dark:bg-amber-900/30'">
                    <p class="text-sm font-medium" :class="selectedAnswer === currentTriviaQuestion?.correct_answer ? 'text-green-800 dark:text-green-200' : 'text-amber-800 dark:text-amber-200'">
                        {{ selectedAnswer === currentTriviaQuestion?.correct_answer ? 'Correct!' : 'Not quite right' }}
                    </p>
                    <p v-if="selectedAnswer !== currentTriviaQuestion?.correct_answer" class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                        The correct answer is: <strong>{{ currentTriviaQuestion?.options[currentTriviaQuestion?.correct_answer] }}</strong>
                    </p>
                </div>

                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-beach-text-light dark:text-gray-400">
                        {{ triviaScore }} correct so far
                    </div>
                    <button
                        v-if="!answerRevealed"
                        @click="revealAnswer"
                        :disabled="selectedAnswer === null"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-beach-teal hover:bg-beach-teal-dark disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        Check Answer
                    </button>
                    <button
                        v-else
                        @click="nextTriviaQuestion"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-beach-teal hover:bg-beach-teal-dark transition-colors"
                    >
                        {{ currentTriviaIndex === questions.length - 1 ? 'See Results' : 'Next Question' }}
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div v-else class="text-center space-y-4">
            <div class="text-5xl mb-2">{{ triviaScore >= questions.length * 0.7 ? '&#127881;' : triviaScore >= questions.length * 0.5 ? '&#128077;' : '&#128218;' }}</div>
            <div>
                <h4 class="text-xl font-semibold text-beach-text dark:text-white">Quiz Complete!</h4>
                <p class="text-gray-600 dark:text-gray-400 mt-1">You scored {{ triviaScore }} out of {{ questions.length }}</p>

                <!-- Score visual -->
                <div class="mt-4 flex justify-center">
                    <div class="relative w-24 h-24">
                        <svg class="w-24 h-24 transform -rotate-90">
                            <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="none" class="text-gray-200 dark:text-gray-700"/>
                            <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="none"
                                :class="triviaScore >= questions.length * 0.7 ? 'text-green-500' : triviaScore >= questions.length * 0.5 ? 'text-yellow-500' : 'text-red-500'"
                                :stroke-dasharray="251.2"
                                :stroke-dashoffset="251.2 - (251.2 * triviaScore / questions.length)"
                                stroke-linecap="round"
                            />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-2xl font-bold text-beach-text dark:text-white">{{ scorePercent }}%</span>
                        </div>
                    </div>
                </div>

                <p v-if="triviaScore >= questions.length * 0.7" class="text-green-600 dark:text-green-400 mt-3 font-medium">
                    Great job! You've mastered this lesson.
                </p>
                <p v-else class="text-amber-600 dark:text-amber-400 mt-3">
                    Keep practicing! Review the content and try again.
                </p>
            </div>

            <!-- Retry button -->
            <button
                v-if="triviaScore < questions.length * 0.7"
                @click="retryTrivia"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-beach-text-light dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Retry Quiz
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    questions: {
        type: Array,
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['completed']);

// Trivia state
const triviaStarted = ref(false);
const triviaCompleted = ref(false);
const currentTriviaIndex = ref(0);
const triviaAnswers = ref([]);
const selectedAnswer = ref(null);
const triviaScore = ref(0);
const answerRevealed = ref(false);

// Computed
const currentTriviaQuestion = computed(() => {
    if (!props.questions || currentTriviaIndex.value >= props.questions.length) {
        return null;
    }
    return props.questions[currentTriviaIndex.value];
});

const scorePercent = computed(() => {
    if (!props.questions || props.questions.length === 0) {
        return 0;
    }
    return Math.round((triviaScore.value / props.questions.length) * 100);
});

// Emit completed event when quiz finishes
watch(triviaCompleted, (isCompleted) => {
    if (isCompleted) {
        emit('completed', {
            score: triviaScore.value,
            total: props.questions.length,
            scorePercent: scorePercent.value,
        });
    }
});

// Reset state when questions change
watch(() => props.questions, () => {
    triviaStarted.value = false;
    triviaCompleted.value = false;
    currentTriviaIndex.value = 0;
    triviaAnswers.value = [];
    selectedAnswer.value = null;
    triviaScore.value = 0;
    answerRevealed.value = false;
});

// Methods
const startTrivia = () => {
    triviaStarted.value = true;
    currentTriviaIndex.value = 0;
    triviaAnswers.value = [];
    selectedAnswer.value = null;
    triviaScore.value = 0;
    answerRevealed.value = false;
};

const selectAnswer = (answerIndex) => {
    if (!answerRevealed.value) {
        selectedAnswer.value = answerIndex;
    }
};

const revealAnswer = () => {
    if (selectedAnswer.value !== null) {
        answerRevealed.value = true;
        triviaAnswers.value[currentTriviaIndex.value] = selectedAnswer.value;

        if (selectedAnswer.value === currentTriviaQuestion.value.correct_answer) {
            triviaScore.value++;
        }
    }
};

const nextTriviaQuestion = () => {
    if (currentTriviaIndex.value === props.questions.length - 1) {
        triviaCompleted.value = true;
    } else {
        currentTriviaIndex.value++;
        selectedAnswer.value = null;
        answerRevealed.value = false;
    }
};

const retryTrivia = () => {
    triviaStarted.value = true;
    triviaCompleted.value = false;
    currentTriviaIndex.value = 0;
    triviaAnswers.value = [];
    selectedAnswer.value = null;
    triviaScore.value = 0;
    answerRevealed.value = false;
};

defineExpose({
    triviaCompleted,
    triviaScore,
});
</script>
