<template>
    <Head title="Prompts" />

    <div class="max-w-7xl mx-auto pb-20 px-4 sm:px-8">
        <!-- Enhanced Search Section -->
        <div class="mt-6">
            <form @submit.prevent="submit" class="relative">
                <div class="relative">
                    <TextInput
                        ref="searchInput"
                        class="py-3 px-6 pr-16 text-lg sm:text-xl w-full font-medium rounded-full shadow-lg border-2 border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all duration-200"
                        :placeholder="currentPlaceholder"
                        v-model="form.question"
                        @input="handleInputChange"
                        @focus="showSuggestions = true"
                        @blur="hideSuggestions"
                        @keydown="handleKeydown"
                        :disabled="form.processing"
                        aria-label="Ask a question or tell us what you want to learn about"
                        autocomplete="off"
                    />

                    <button 
                        type="submit" 
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="form.processing || !form.question.trim()"
                        aria-label="Submit question"
                    >
                        <svg 
                            v-if="!form.processing" 
                            xmlns="http://www.w3.org/2000/svg" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            :strokeWidth="1.5" 
                            stroke="currentColor" 
                            class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 dark:text-neutral-400"
                        >
                            <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <svg 
                            v-else
                            class="animate-spin w-5 h-5 sm:w-6 sm:h-6 text-gray-600 dark:text-neutral-400" 
                            xmlns="http://www.w3.org/2000/svg" 
                            fill="none" 
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Search Suggestions Dropdown -->
                <div 
                    v-if="showSuggestions && (searchSuggestions.length > 0 || recentQuestions.length > 0)" 
                    class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 max-h-80 overflow-y-auto"
                >
                    <!-- Recent Questions -->
                    <div v-if="recentQuestions.length > 0 && !form.question.trim()" class="p-2">
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            Recent Questions
                        </div>
                        <button
                            v-for="(question, index) in recentQuestions.slice(0, 5)"
                            :key="`recent-${index}`"
                            type="button"
                            class="w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-sm transition-colors duration-150"
                            @mousedown.prevent="selectSuggestion(question)"
                        >
                            <span class="text-gray-700 dark:text-gray-300">{{ question }}</span>
                        </button>
                    </div>

                    <!-- Search Suggestions -->
                    <div v-if="searchSuggestions.length > 0" class="p-2">
                        <div v-if="recentQuestions.length > 0 && !form.question.trim()" class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            Suggestions
                        </div>
                        <button
                            v-for="(suggestion, index) in searchSuggestions.slice(0, 6)"
                            :key="`suggestion-${index}`"
                            type="button"
                            class="w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-sm transition-colors duration-150"
                            :class="{ 'bg-blue-50 dark:bg-blue-900': selectedSuggestionIndex === index }"
                            @mousedown.prevent="selectSuggestion(suggestion)"
                        >
                            <span class="text-gray-700 dark:text-gray-300">{{ suggestion }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Error Display -->
        <div v-if="errors.question" class="mt-3">
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-red-700 dark:text-red-400 font-medium">{{ errors.question }}</span>
                </div>
            </div>
        </div>

        <!-- Enhanced Loading State -->
        <div v-if="form.processing" role="status" class="mt-8 sm:mt-12" aria-live="polite">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 sm:p-8">
                <div class="flex flex-col items-center text-center">
                    <div class="relative">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 border-4 border-blue-200 dark:border-blue-800 rounded-full animate-pulse"></div>
                        <div class="absolute inset-0 w-12 h-12 sm:w-16 sm:h-16 border-4 border-transparent border-t-blue-600 rounded-full animate-spin"></div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ loadingMessage }}</h3>
                        <div class="flex items-center justify-center space-x-1">
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flagged Content Warning -->
        <div v-if="result && flagged" class="mt-6">
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 sm:p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800 dark:text-red-400 mb-2">Content Review Notice</h3>
                        <p class="text-red-700 dark:text-red-300 whitespace-pre-wrap">{{ result.message }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section with Responsive Layout -->
        <div v-if="!form.processing && result && flagged !== true" class="mt-8 sm:mt-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
                <!-- Main Content Area -->
                <div class="lg:col-span-8">
                    <!-- Subject Display -->
                    <div class="mb-6">
                        <Subject
                            :question="form.question"
                            :processing="form.processing"
                        />
                    </div>
                    
                    <!-- Content Display -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Response</h3>
                            <button
                                v-if="result && !form.processing"
                                @click="copyContent"
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-200"
                                :class="{ 'text-green-600 dark:text-green-400': copySuccess }"
                            >
                                <svg v-if="!copySuccess" class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <svg v-else class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ copySuccess ? 'Copied!' : 'Copy' }}
                            </button>
                        </div>
                        <Content
                            :question="form.question"
                            :processing="form.processing"
                        />
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Related Questions</h3>
                        <Questions
                            :question="form.question"
                            :processing="form.processing"
                            @questionClicked="(event) => handleQuestionClicked(event)"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { useForm, Head } from '@inertiajs/vue3'
    import { ref, watch, computed, onMounted, nextTick } from 'vue'
    import TextInput from "@/Components/TextInput.vue";
    import Subject from "@/Pages/Student/Prompts/Subject.vue";
    import Content from "@/Pages/Student/Prompts/Content.vue";
    import Questions from "@/Pages/Student/Prompts/Questions.vue";

    defineOptions({
        layout: AuthenticatedLayout
    });

    const props = defineProps({
        errors: {
            type: Object,
            required: false,
        },
        result: {
            type: Object,
            required: false,
        },
    })

    const form = useForm({
        question: '',
    })

    const searchInput = ref(null);
    const flagged = ref(false);
    const showSuggestions = ref(false);
    const selectedSuggestionIndex = ref(-1);
    const copySuccess = ref(false);
    const placeholderIndex = ref(0);
    
    // Sample suggestions and recent questions
    const sampleQuestions = [
        "How does photosynthesis work?",
        "What is the theory of relativity?",
        "Explain the water cycle",
        "What causes earthquakes?",
        "How do computers process information?",
        "What is DNA and how does it work?"
    ];
    
    const searchSuggestions = ref([]);
    const recentQuestions = ref(JSON.parse(localStorage.getItem('recentQuestions') || '[]'));
    
    // Dynamic placeholder text
    const currentPlaceholder = computed(() => {
        if (form.question.trim()) return "";
        return sampleQuestions[placeholderIndex.value] || "Ask a question or tell us what you want to learn about...";
    });
    
    // Loading messages
    const loadingMessages = [
        "Analyzing your question...",
        "Generating personalized response...",
        "Finding the best answer...",
        "Processing with AI..."
    ];
    
    const loadingMessage = ref(loadingMessages[0]);
    
    // Rotate placeholder text
    onMounted(() => {
        setInterval(() => {
            if (!form.question.trim()) {
                placeholderIndex.value = (placeholderIndex.value + 1) % sampleQuestions.length;
            }
        }, 3000);
        
        // Rotate loading messages
        let messageIndex = 0;
        setInterval(() => {
            if (form.processing) {
                messageIndex = (messageIndex + 1) % loadingMessages.length;
                loadingMessage.value = loadingMessages[messageIndex];
            }
        }, 2000);
    });
    
    const handleInputChange = (event) => {
        const query = event.target.value;
        if (query.trim().length > 2) {
            // Simple suggestion logic - in a real app, this would be an API call
            const filtered = sampleQuestions.filter(q => 
                q.toLowerCase().includes(query.toLowerCase()) && 
                q.toLowerCase() !== query.toLowerCase()
            );
            searchSuggestions.value = filtered;
        } else {
            searchSuggestions.value = [];
        }
        selectedSuggestionIndex.value = -1;
    };
    
    const handleKeydown = (event) => {
        if (!showSuggestions.value) return;
        
        const totalSuggestions = searchSuggestions.value.length;
        
        if (event.key === 'ArrowDown') {
            event.preventDefault();
            selectedSuggestionIndex.value = Math.min(selectedSuggestionIndex.value + 1, totalSuggestions - 1);
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            selectedSuggestionIndex.value = Math.max(selectedSuggestionIndex.value - 1, -1);
        } else if (event.key === 'Enter' && selectedSuggestionIndex.value >= 0) {
            event.preventDefault();
            const suggestion = searchSuggestions.value[selectedSuggestionIndex.value];
            if (suggestion) {
                selectSuggestion(suggestion);
            }
        } else if (event.key === 'Escape') {
            showSuggestions.value = false;
            selectedSuggestionIndex.value = -1;
        }
    };
    
    const selectSuggestion = (suggestion) => {
        form.question = suggestion;
        showSuggestions.value = false;
        selectedSuggestionIndex.value = -1;
        nextTick(() => {
            if (searchInput.value) {
                searchInput.value.focus();
            }
        });
    };
    
    const hideSuggestions = () => {
        setTimeout(() => {
            showSuggestions.value = false;
        }, 200);
    };
    
    const addToRecentQuestions = (question) => {
        const recent = recentQuestions.value.filter(q => q !== question);
        recent.unshift(question);
        recentQuestions.value = recent.slice(0, 10); // Keep only last 10
        localStorage.setItem('recentQuestions', JSON.stringify(recentQuestions.value));
    };
    
    const copyContent = async () => {
        try {
            const contentElement = document.querySelector('[data-content-text]');
            const text = contentElement ? contentElement.textContent : '';
            await navigator.clipboard.writeText(text);
            copySuccess.value = true;
            setTimeout(() => {
                copySuccess.value = false;
            }, 2000);
        } catch (err) {
            console.error('Failed to copy content:', err);
        }
    };

    const submit = () => {
        if (!form.question.trim()) return;
        
        flagged.value = false;
        addToRecentQuestions(form.question.trim());
        form.post(route('student.prompts.store'));
        localStorage.removeItem('questions');
        showSuggestions.value = false;
    }

    const handleQuestionClicked = (event) => {
        form.question = event.question;
        addToRecentQuestions(event.question);
        form.post(route('student.prompts.store'));
    }

    watch(() => props.result, (result) => {
        flagged.value = result?.flagged;
    })
    
    watch(() => form.processing, (processing) => {
        if (processing) {
            loadingMessage.value = loadingMessages[0];
        }
    })
</script>
