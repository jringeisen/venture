<template>
    <div>
        <div v-if="loading || processing" role="status" class="max-w-sm">
            <!-- Loading State with Spinner -->
            <div class="flex items-center mb-2">
                <svg aria-hidden="true" class="w-5 h-5 mr-2 text-gray-200 animate-spin dark:text-neutral-400 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="text-sm dark:text-neutral-500">Finding subject...</span>
            </div>
            <!-- Skeleton Loaders -->
            <div class="animate-pulse">
                <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-48 mb-4"></div>
                <div class="h-2 bg-gray-200 rounded-full dark:bg-primary-gray w-20 mb-2.5"></div>
            </div>
            <span class="sr-only">Loading subject...</span>
        </div>
        <div v-else-if="error" class="text-red-500 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm">Failed to identify subject -
                <button @click="retry" class="underline hover:text-red-600">Retry</button>
            </span>
        </div>
        <div v-else class="flex items-center divide-x-4 divide-primary-yellow">
            <div class="flex items-center p-2 pl-0">
                <ApplicationLogo class="w-14 h-14" />
                <h1 class="text-3xl font-bold dark:text-neutral-900">{{ subject.toUpperCase() }}</h1>
            </div>
            <div class="p-2">
                <h3 class="text-lg font-bold text-black tracking-widest dark:text-neutral-900">{{ subCategory.toUpperCase() }}</h3>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
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

const emit = defineEmits(['loading', 'error']);

const loading = ref(false);
const error = ref(false);
const subject = ref('');
const subCategory = ref('');

onMounted(() => {
    fetchSubject();
});

const fetchSubject = () => {
    loading.value = true;
    error.value = false;
    emit('loading', true);

    axios.post('/student/prompts/subject', {question: props.question})
        .then(response => {
            subject.value = response.data.subject;
            subCategory.value = response.data.subCategory;
            loading.value = false;
            emit('loading', false);
        })
        .catch(err => {
            console.error('Failed to fetch subject:', err);
            error.value = true;
            loading.value = false;
            emit('error');
            emit('loading', false);
        });
};

const retry = () => {
    fetchSubject();
};
</script>
