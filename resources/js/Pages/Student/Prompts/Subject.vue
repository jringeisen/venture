<template>
    <div>
        <!-- Loading State -->
        <div v-if="loading || processing" role="status" class="animate-pulse" aria-live="polite">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-6 sm:h-8 bg-gray-200 dark:bg-gray-700 rounded w-32 sm:w-48"></div>
                    <div class="h-4 sm:h-5 bg-gray-200 dark:bg-gray-700 rounded w-16 sm:w-20"></div>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm text-gray-500 dark:text-gray-400">
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Categorizing question...</span>
            </div>
        </div>
        
        <!-- Subject Display -->
        <div v-else-if="subject" class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-4 sm:p-6 border border-blue-200 dark:border-gray-600">
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-4 sm:space-y-0">
                <!-- Logo and Main Subject -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="flex-shrink-0">
                        <ApplicationLogo class="w-10 h-10 sm:w-12 sm:h-12" />
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-200">
                            {{ subject.charAt(0).toUpperCase() + subject.slice(1).toLowerCase() }}
                        </h1>
                    </div>
                </div>
                
                <!-- Divider -->
                <div class="hidden sm:block w-px h-12 bg-blue-300 dark:bg-gray-600"></div>
                <div class="block sm:hidden h-px w-full bg-blue-300 dark:bg-gray-600"></div>
                
                <!-- Subcategory -->
                <div class="flex-grow">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                        </svg>
                        <h3 class="text-sm sm:text-lg font-semibold text-gray-700 dark:text-gray-300 tracking-wide">
                            {{ subCategory.charAt(0).toUpperCase() + subCategory.slice(1).toLowerCase() }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h4 class="text-red-800 dark:text-red-400 font-medium">Classification Error</h4>
                    <p class="text-red-700 dark:text-red-300 text-sm mt-1">{{ error }}</p>
                    <button 
                        @click="retry" 
                        class="mt-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 underline"
                    >
                        Try again
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const props = defineProps({
    question: {
        type: String,
        required: true,
    },
    processing: {
        type: Boolean,
        required: true,
    }
})

const loading = ref(false);
const subject = ref('');
const subCategory = ref('');
const error = ref('');
let retryCount = 0;
const maxRetries = 3;

// Watch for question changes
watch(() => props.question, (newQuestion, oldQuestion) => {
    if (newQuestion && newQuestion !== oldQuestion) {
        fetchSubject();
    }
});

onMounted(() => {
    if (props.question) {
        fetchSubject();
    }
});

const fetchSubject = async () => {
    if (!props.question) return;
    
    loading.value = true;
    error.value = '';
    
    try {
        const response = await axios.post('/student/prompts/subject', {
            question: props.question
        });
        
        subject.value = response.data.subject || 'General';
        subCategory.value = response.data.subCategory || 'Learning';
        retryCount = 0;
    } catch (err) {
        console.error('Error fetching subject:', err);
        error.value = 'Failed to categorize question';
        
        if (retryCount < maxRetries) {
            retryCount++;
            setTimeout(() => {
                fetchSubject();
            }, 2000 * retryCount);
        }
    } finally {
        loading.value = false;
    }
};

const retry = () => {
    retryCount = 0;
    fetchSubject();
};
</script>
