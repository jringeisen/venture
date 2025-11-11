<template>
    <Head title="Prompts" />

    <div class="max-w-7xl mx-auto pb-20 sm:px-8">
        <form @submit.prevent="submit" class="flex items-center mt-6 relative">
            <TextInput
                class="py-2 px-6 text-xl w-full font-bold rounded-full shadow-lg"
                placeholder="Ask a question or tell us what you want to learn about..."
                v-model="form.question"
                @change="form.question = $event.target.value"
            />

            <button type="submit" class="absolute right-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" :strokeWidth="1.5" stroke="currentColor" class="w-6 h-6 dark:text-neutral-400">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </button>
        </form>

        <InputError v-if="errors.question" :message="errors.question" />

        <!-- Initial Thinking Indicator -->
        <div v-if="form.processing" role="status" class="flex flex-col justify-center items-center mt-12">
            <div class="flex items-center mb-4">
                <svg aria-hidden="true" class="w-6 h-6 mr-2 text-gray-200 animate-spin dark:text-neutral-400 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="text-xl dark:text-neutral-400">Analyzing your question<span class="animate-pulse">...</span></span>
            </div>
        </div>

        <div v-if="result && flagged" class="mt-6 bg-red-200 p-4 rounded-lg text-red-600 whitespace-pre-wrap">{{ result.message }}</div>

        <!-- Overall Progress Panel -->
        <div v-if="!form.processing && result && flagged !== true && isAnyLoading" class="mt-8 bg-white dark:bg-neutral-800 rounded-lg shadow-lg p-6">
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold dark:text-neutral-300">Processing your question</h3>
                    <span class="text-sm dark:text-neutral-400">{{ overallProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-neutral-700 rounded-full h-2.5">
                    <div class="bg-primary-yellow h-2.5 rounded-full transition-all duration-500" :style="{ width: overallProgress + '%' }"></div>
                </div>
            </div>

            <div class="space-y-3">
                <!-- Subject Status -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg v-if="loadingStates.subject.completed" class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <svg v-else-if="loadingStates.subject.error" class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <svg v-else aria-hidden="true" class="w-5 h-5 mr-2 text-gray-200 animate-spin dark:text-neutral-400 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>
                        <span class="text-sm dark:text-neutral-300">
                            {{ loadingStates.subject.completed ? 'Subject identified' : loadingStates.subject.error ? 'Subject failed' : 'Finding subject...' }}
                        </span>
                    </div>
                </div>

                <!-- Content Status -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg v-if="loadingStates.content.completed" class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <svg v-else-if="loadingStates.content.error" class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <svg v-else aria-hidden="true" class="w-5 h-5 mr-2 text-gray-200 animate-spin dark:text-neutral-400 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>
                        <span class="text-sm dark:text-neutral-300">
                            {{ loadingStates.content.completed ? 'Content generated' : loadingStates.content.error ? 'Content failed' : 'Generating content...' }}
                        </span>
                    </div>
                </div>

                <!-- Questions Status -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg v-if="loadingStates.questions.completed" class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <svg v-else-if="loadingStates.questions.error" class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <svg v-else aria-hidden="true" class="w-5 h-5 mr-2 text-gray-200 animate-spin dark:text-neutral-400 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>
                        <span class="text-sm dark:text-neutral-300">
                            {{ loadingStates.questions.completed ? 'Related questions ready' : loadingStates.questions.error ? 'Questions failed' : 'Generating related questions...' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!form.processing && result && flagged !== true">
            <div class="flex space-x-8 mt-12">
                <div class="w-7/12">
                    <div>
                        <Subject
                            :question="form.question"
                            :processing="form.processing"
                            @loading="handleSubjectLoading"
                            @error="handleSubjectError"
                        />
                    </div>
                    <div class="mt-3">
                        <Content
                            :question="form.question"
                            :processing="form.processing"
                            @loading="handleContentLoading"
                            @error="handleContentError"
                        />
                    </div>
                </div>
                <div class="w-5/12 mt-8">
                    <div>
                        <Questions
                            :question="form.question"
                            :processing="form.processing"
                            @loading="handleQuestionsLoading"
                            @error="handleQuestionsError"
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
    import { ref, watch, computed } from 'vue'
    import TextInput from "@/Components/TextInput.vue";
    import InputError from "@/Components/InputError.vue";
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

    const flagged = ref(false);

    // Loading state tracking for all three AI processes
    const loadingStates = ref({
        subject: { loading: false, completed: false, error: false },
        content: { loading: false, completed: false, error: false },
        questions: { loading: false, completed: false, error: false },
    });

    // Compute overall progress percentage
    const overallProgress = computed(() => {
        const total = 3;
        const completed = Object.values(loadingStates.value).filter(state => state.completed).length;
        return Math.round((completed / total) * 100);
    });

    // Check if any process is still loading
    const isAnyLoading = computed(() => {
        return Object.values(loadingStates.value).some(state => state.loading);
    });

    // Status handlers for child components
    const handleSubjectLoading = (loading) => {
        loadingStates.value.subject.loading = loading;
        if (!loading) loadingStates.value.subject.completed = true;
    };

    const handleSubjectError = () => {
        loadingStates.value.subject.error = true;
        loadingStates.value.subject.loading = false;
    };

    const handleContentLoading = (loading) => {
        loadingStates.value.content.loading = loading;
        if (!loading) loadingStates.value.content.completed = true;
    };

    const handleContentError = () => {
        loadingStates.value.content.error = true;
        loadingStates.value.content.loading = false;
    };

    const handleQuestionsLoading = (loading) => {
        loadingStates.value.questions.loading = loading;
        if (!loading) loadingStates.value.questions.completed = true;
    };

    const handleQuestionsError = () => {
        loadingStates.value.questions.error = true;
        loadingStates.value.questions.loading = false;
    };

    // Reset loading states when submitting
    const resetLoadingStates = () => {
        loadingStates.value = {
            subject: { loading: false, completed: false, error: false },
            content: { loading: false, completed: false, error: false },
            questions: { loading: false, completed: false, error: false },
        };
    };

    const submit = () => {
        flagged.value = false;
        resetLoadingStates();
        form.post(route('student.prompts.store'));

        localStorage.removeItem('questions');
    }

    const handleQuestionClicked = (event) => {
        form.question = event.question;
        resetLoadingStates();
        form.post(route('student.prompts.store'));
    }

    watch(() => props.result, (result) => {
        flagged.value = result?.flagged;
    })
</script>
