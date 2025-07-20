<template>
    <Head :title="formatTopicTitle(topic)" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Topic Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ formatTopicTitle(topic) }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ getTopicDescription(topic) }}
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0 flex items-center space-x-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Questions</span>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ questions?.total || 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <!-- Search Input -->
                        <div class="sm:col-span-6">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    @input="handleSearch"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                                    placeholder="Search questions and answers..."
                                />
                            </div>
                        </div>
                        
                        <!-- Sort Dropdown -->
                        <div class="sm:col-span-3">
                            <select 
                                v-model="sortBy" 
                                @change="handleSort"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            >
                                <option value="date_desc">Newest First</option>
                                <option value="date_asc">Oldest First</option>
                                <option value="word_count_desc">Longest Answers</option>
                                <option value="word_count_asc">Shortest Answers</option>
                            </select>
                        </div>
                        
                        <!-- View Toggle -->
                        <div class="sm:col-span-3">
                            <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                                <button
                                    @click="viewMode = 'card'"
                                    :class="viewMode === 'card' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400'"
                                    class="flex-1 px-3 py-1.5 text-sm font-medium rounded-md transition-all duration-200"
                                >
                                    Cards
                                </button>
                                <button
                                    @click="viewMode = 'list'"
                                    :class="viewMode === 'list' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400'"
                                    class="flex-1 px-3 py-1.5 text-sm font-medium rounded-md transition-all duration-200"
                                >
                                    List
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Filters -->
                    <div v-if="searchQuery" class="mt-4 flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search: "{{ searchQuery }}"
                            <button @click="clearSearch" class="ml-2 hover:text-blue-600 dark:hover:text-blue-300">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            <div v-if="filteredQuestions.length > 0">
                <!-- Card View -->
                <div v-if="viewMode === 'card'" class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                    <div
                        v-for="(question, index) in paginatedQuestions"
                        :key="question.id"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 group self-start relative"
                    >
                        <!-- Card Header -->
                        <div class="p-4 sm:p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div 
                                        :class="getSubjectColorClasses(question?.prompt_answer?.subject_category)"
                                        class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm shadow-sm"
                                    >
                                        {{ getSubjectInitial(question?.prompt_answer?.subject_category) }}
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="getSubjectBadgeClasses(question?.prompt_answer?.subject_category)">
                                            {{ capitalize(question?.prompt_answer?.subject_category || 'General') }}
                                        </span>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ question?.created_at || 'Unknown' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ question?.prompt_answer?.word_count || 0 }} words</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">•</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ getReadingTime(question?.prompt_answer?.word_count) }} min</span>
                                </div>
                            </div>
                            
                            <!-- Question -->
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2">
                                {{ question?.question || 'No question available' }}
                            </h3>
                            
                            <!-- Answer Preview -->
                            <div class="mb-4">
                                <p class="text-gray-600 dark:text-gray-300 line-clamp-3">
                                    {{ getAnswerPreview(question?.prompt_answer?.content) }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Card Actions -->
                        <div class="border-t border-gray-200 dark:border-gray-700 px-4 sm:px-6 py-3">
                            <div class="flex items-center justify-between">
                                <button
                                    @click="toggleContent(question.id)"
                                    class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors"
                                >
                                    <span>{{ expandedCards.has(question.id) ? 'Show Less' : 'Read Full Answer' }}</span>
                                    <svg 
                                        :class="{ 'rotate-180': expandedCards.has(question.id) }"
                                        class="w-4 h-4 ml-1 transition-transform duration-200" 
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="flex items-center space-x-3">
                                    <button
                                        @click="copyAnswer(question?.prompt_answer?.content, question?.id)"
                                        :class="{ 'text-green-600 dark:text-green-400': copiedAnswers.has(question?.id) }"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                        :title="copiedAnswers.has(question?.id) ? 'Copied!' : 'Copy answer'"
                                    >
                                        <svg v-if="!copiedAnswers.has(question?.id)" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="toggleBookmark(question?.id)"
                                        :class="bookmarkedQuestions.has(question?.id) ? 'text-yellow-500' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300'"
                                        class="transition-colors"
                                        title="Bookmark"
                                    >
                                        <svg class="w-4 h-4" :fill="bookmarkedQuestions.has(question?.id) ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Expanded Content -->
                        <Transition name="expand">
                            <div v-if="expandedCards.has(question?.id)" class="absolute top-full left-0 right-0 z-50 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-b-xl shadow-lg max-h-96 overflow-y-auto">
                                <div class="p-4 sm:p-6">
                                    <div class="prose prose-sm dark:prose-invert max-w-none">
                                        <div class="whitespace-pre-wrap text-gray-700 dark:text-gray-300">
                                            {{ question?.prompt_answer?.content || 'No content available' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Transition>
                    </div>
                </div>
                
                <!-- List View -->
                <div v-else class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div
                            v-for="(question, index) in paginatedQuestions"
                            :key="question.id"
                            class="p-4 sm:p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                        >
                            <div class="flex items-start space-x-4">
                                <div 
                                    :class="getSubjectColorClasses(question?.prompt_answer?.subject_category)"
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-semibold text-xs flex-shrink-0"
                                >
                                    {{ getSubjectInitial(question?.prompt_answer?.subject_category) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-base font-medium text-gray-900 dark:text-white line-clamp-1">
                                            {{ question?.question || 'No question available' }}
                                        </h3>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                            <span>{{ question?.prompt_answer?.word_count || 0 }} words</span>
                                            <span>{{ question?.created_at || 'Unknown' }}</span>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300 line-clamp-2 mb-3">
                                        {{ getAnswerPreview(question?.prompt_answer?.content) }}
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="getSubjectBadgeClasses(question?.prompt_answer?.subject_category)">
                                            {{ capitalize(question?.prompt_answer?.subject_category || 'General') }}
                                        </span>
                                        <div class="flex items-center space-x-3">
                                            <button
                                                @click="toggleContent(question?.id)"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 text-sm font-medium transition-colors"
                                            >
                                                {{ expandedCards.has(question?.id) ? 'Show Less' : 'Read More' }}
                                            </button>
                                            <button
                                                @click="copyAnswer(question?.prompt_answer?.content, question?.id)"
                                                :class="{ 'text-green-600 dark:text-green-400': copiedAnswers.has(question?.id) }"
                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path v-if="!copiedAnswers.has(question?.id)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Expanded Content -->
                                    <Transition name="expand">
                                        <div v-if="expandedCards.has(question?.id)" class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                            <div class="prose prose-sm dark:prose-invert max-w-none">
                                                <div class="whitespace-pre-wrap text-gray-700 dark:text-gray-300">
                                                    {{ question?.prompt_answer?.content || 'No content available' }}
                                                </div>
                                            </div>
                                        </div>
                                    </Transition>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Empty State -->
            <div v-else class="text-center py-16">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 sm:p-12">
                    <div class="max-w-md mx-auto">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                            {{ searchQuery ? 'No matching questions found' : 'No questions in this topic yet' }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            {{ searchQuery ? 'Try adjusting your search terms or clear the search to see all questions.' : 'Start exploring by asking questions in the main prompts section.' }}
                        </p>
                        <div class="space-y-3">
                            <button v-if="searchQuery" @click="clearSearch" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                Clear Search
                            </button>
                            <a href="/student/prompts" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                Ask a Question
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced Pagination -->
            <div v-if="totalPages > 1" class="mt-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <nav class="flex items-center justify-between px-4 py-3 sm:px-6" aria-label="Pagination">
                        <div class="hidden sm:block">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Showing <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span> to 
                                <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredQuestions.length) }}</span> of 
                                <span class="font-medium">{{ filteredQuestions.length }}</span> results
                            </p>
                        </div>
                        <div class="flex flex-1 justify-between sm:justify-end space-x-3">
                            <button
                                @click="previousPage"
                                :disabled="currentPage === 1"
                                class="relative inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                Previous
                            </button>
                            <button
                                @click="nextPage"
                                :disabled="currentPage === totalPages"
                                class="relative inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                Next
                            </button>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';
import pkg from 'lodash';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    questions: Object,
    topic: String,
});

const { capitalize, startCase } = pkg;

// Reactive state
const searchQuery = ref('');
const sortBy = ref('date_desc');
const viewMode = ref('card');
const expandedCards = ref(new Set());
const copiedAnswers = ref(new Set());
const bookmarkedQuestions = ref(new Set());
const currentPage = ref(1);
const itemsPerPage = 10;

// Load bookmarks from localStorage
onMounted(() => {
    const saved = localStorage.getItem('bookmarkedQuestions');
    if (saved) {
        bookmarkedQuestions.value = new Set(JSON.parse(saved));
    }
});

// Watch for bookmark changes and save to localStorage
watch(bookmarkedQuestions, (newBookmarks) => {
    localStorage.setItem('bookmarkedQuestions', JSON.stringify([...newBookmarks]));
}, { deep: true });

// Computed properties
const filteredQuestions = computed(() => {
    if (!props.questions?.data) return [];
    let filtered = [...props.questions.data];
    
    // Apply search filter
    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(question => {
            const questionText = question?.question || '';
            const answerContent = question?.prompt_answer?.content || '';
            const subjectCategory = question?.prompt_answer?.subject_category || '';
            
            return questionText.toLowerCase().includes(query) ||
                   answerContent.toLowerCase().includes(query) ||
                   subjectCategory.toLowerCase().includes(query);
        });
    }
    
    // Apply sorting
    switch (sortBy.value) {
        case 'date_asc':
            filtered.sort((a, b) => {
                const dateA = new Date(a?.created_at || 0);
                const dateB = new Date(b?.created_at || 0);
                return dateA - dateB;
            });
            break;
        case 'date_desc':
            filtered.sort((a, b) => {
                const dateA = new Date(a?.created_at || 0);
                const dateB = new Date(b?.created_at || 0);
                return dateB - dateA;
            });
            break;
        case 'word_count_asc':
            filtered.sort((a, b) => {
                const countA = a?.prompt_answer?.word_count || 0;
                const countB = b?.prompt_answer?.word_count || 0;
                return countA - countB;
            });
            break;
        case 'word_count_desc':
            filtered.sort((a, b) => {
                const countA = a?.prompt_answer?.word_count || 0;
                const countB = b?.prompt_answer?.word_count || 0;
                return countB - countA;
            });
            break;
    }
    
    return filtered;
});

const totalPages = computed(() => {
    return Math.ceil(filteredQuestions.value.length / itemsPerPage);
});

const paginatedQuestions = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredQuestions.value.slice(start, end);
});

// Helper functions
const formatTopicTitle = (topic) => {
    if (topic === 'all') return 'All Topics';
    return startCase(topic.replace(/-/g, ' '));
};

const getTopicDescription = (topic) => {
    const descriptions = {
        'all': 'Browse through all your questions and answers across different subjects.',
        'science': 'Explore your scientific discoveries and understanding.',
        'technology': 'Review your tech-related questions and insights.',
        'mathematics': 'Navigate through your mathematical problem-solving journey.',
        'history': 'Revisit historical questions and learning moments.',
        'literature': 'Discover your literary explorations and analyses.'
    };
    return descriptions[topic] || `Explore your ${formatTopicTitle(topic).toLowerCase()} questions and answers.`;
};

const getSubjectColorClasses = (category) => {
    const colors = {
        'science': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'technology': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'mathematics': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'history': 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
        'literature': 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
        'art': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'music': 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
        'geography': 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200'
    };
    return colors[category.toLowerCase()] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
};

const getSubjectBadgeClasses = (category) => {
    const colors = {
        'science': 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
        'technology': 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
        'mathematics': 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300',
        'history': 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300',
        'literature': 'bg-pink-100 text-pink-800 dark:bg-pink-900/50 dark:text-pink-300',
        'art': 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
        'music': 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300',
        'geography': 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-300'
    };
    return colors[category.toLowerCase()] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-300';
};

const getSubjectInitial = (category) => {
    if (!category || typeof category !== 'string') return 'G';
    return category.charAt(0).toUpperCase();
};

const getAnswerPreview = (content, length = 200) => {
    if (!content || typeof content !== 'string') return 'No content available';
    if (content.length <= length) return content;
    return content.substring(0, length) + '...';
};

const getReadingTime = (wordCount) => {
    if (!wordCount || isNaN(wordCount)) return 1;
    return Math.ceil(wordCount / 200); // Assuming 200 words per minute
};

// Event handlers
const handleSearch = () => {
    currentPage.value = 1; // Reset to first page when searching
};

const handleSort = () => {
    currentPage.value = 1; // Reset to first page when sorting
};

const clearSearch = () => {
    searchQuery.value = '';
    currentPage.value = 1;
};

const toggleContent = (questionId) => {
    if (expandedCards.value.has(questionId)) {
        expandedCards.value.delete(questionId);
    } else {
        expandedCards.value.add(questionId);
    }
};

const copyAnswer = async (content, questionId) => {
    try {
        await navigator.clipboard.writeText(content);
        copiedAnswers.value.add(questionId);
        setTimeout(() => {
            copiedAnswers.value.delete(questionId);
        }, 2000);
    } catch (err) {
        console.error('Failed to copy content:', err);
    }
};

const toggleBookmark = (questionId) => {
    if (bookmarkedQuestions.value.has(questionId)) {
        bookmarkedQuestions.value.delete(questionId);
    } else {
        bookmarkedQuestions.value.add(questionId);
    }
};

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

// Legacy function for backward compatibility
const handleToggleContent = (id) => {
    toggleContent(id);
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

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.expand-enter-active,
.expand-leave-active {
    transition: all 0.3s ease;
    overflow: hidden;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
}

.expand-enter-to,
.expand-leave-from {
    max-height: 500px;
    opacity: 1;
}

.prose {
    line-height: 1.7;
}

.prose p {
    margin-bottom: 1rem;
}

.prose ul, 
.prose ol {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

.prose strong {
    font-weight: 600;
    color: #1f2937;
}

.dark .prose strong {
    color: #f9fafb;
}

.prose em {
    font-style: italic;
    color: #374151;
}

.dark .prose em {
    color: #d1d5db;
}
</style>