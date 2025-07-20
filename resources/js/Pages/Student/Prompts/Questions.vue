<template>
    <div>
        <!-- Loading State -->
        <div v-if="loading || processing" role="status" class="space-y-3" aria-live="polite">
            <div class="animate-pulse space-y-3">
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-5/6"></div>
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-4/6"></div>
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-3/4"></div>
            </div>
            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-4">
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Finding related questions...</span>
            </div>
        </div>
        
        <!-- Questions List -->
        <div v-else-if="questions.length > 0" class="space-y-2">
            <div 
                v-for="(item, index) in questions"
                :key="index"
                class="group relative"
            >
                <button
                    type="button"
                    class="w-full text-left p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    :class="{
                        'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20': item.selected,
                        'shadow-sm hover:shadow-md': !item.selected
                    }"
                    @click.prevent="questionClicked(item)"
                    :aria-label="`Ask: ${item.question}`"
                >
                    <div class="flex items-start justify-between">
                        <span 
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors duration-200"
                            :class="{
                                'text-blue-700 dark:text-blue-300': item.selected
                            }"
                        >
                            {{ item.question }}
                        </span>
                        <svg 
                            class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors duration-200 flex-shrink-0 ml-2 mt-0.5"
                            :class="{
                                'text-blue-500': item.selected
                            }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    
                    <!-- Selection Indicator -->
                    <div v-if="item.selected" class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 rounded-l-lg"></div>
                </button>
            </div>
            
            <!-- Helpful Tip -->
            <div class="mt-6 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keep exploring!</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Click any question above to dive deeper into the topic.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Empty State -->
        <div v-else class="text-center py-8">
            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">No related questions yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ask a question to see related topics and suggestions.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
    question: {
        type: String,
        required: true,
    },
    processing: {
        type: Boolean,
        required: true,
    },
})

const emit = defineEmits(['questionClicked']);

const questions = ref(JSON.parse(localStorage.getItem('questions')) || []);
const loading = ref(false);
const error = ref('');
let retryCount = 0;
const maxRetries = 3;

// Watch for question changes to fetch new related questions
watch(() => props.question, (newQuestion, oldQuestion) => {
    if (newQuestion && newQuestion !== oldQuestion) {
        fetchQuestions();
    }
});

onMounted(() => {
    if (questions.value.length === 0 && props.question) {
        fetchQuestions();
    }
});

const fetchQuestions = async () => {
    if (!props.question) return;
    
    loading.value = true;
    error.value = '';
    
    try {
        const response = await axios.post('/student/prompts/questions', {
            question: props.question
        });
        
        questions.value = response.data.questions || [];
        localStorage.setItem('questions', JSON.stringify(questions.value));
        retryCount = 0;
    } catch (err) {
        console.error('Error fetching questions:', err);
        error.value = 'Failed to load related questions';
        
        if (retryCount < maxRetries) {
            retryCount++;
            setTimeout(() => {
                fetchQuestions();
            }, 2000 * retryCount);
        }
    } finally {
        loading.value = false;
    }
};

const questionClicked = (item) => {
    const formattedQuestions = questions.value.map((q) => {
        return {
            'question': q.question,
            'selected': q.question === item.question
        }
    });

    localStorage.setItem('questions', JSON.stringify(formattedQuestions));
    questions.value = formattedQuestions;

    emit('questionClicked', {question: item.question});
}

const retry = () => {
    retryCount = 0;
    fetchQuestions();
};
</script>
