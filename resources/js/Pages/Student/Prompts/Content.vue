<template>
        <div>
            <div v-if="errorMessage" class="bg-red-300 text-red-700 rounded-lg p-4">
                <p>{{ errorMessage }}</p>
                <p>It looks like we're having issues trying to create the answer to your question. Please refresh the page and try again.</p>
            </div>

            <template v-else>
                <div v-if="loading || processing" role="status" class="animate-pulse">
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-4"></div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-3/4 mb-4"></div>
                    <br/>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-11/12 mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-2.5"></div>
                    <br/>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-11/12 mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-2.5"></div>
                    <br/>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-4"></div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-3/4 mb-4"></div>
                    <br/>
                    <br/>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-11/12 mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-2.5"></div>
                    <span class="sr-only">Loading...</span>
                </div>
                <p v-else class="whitespace-pre-wrap">{{ content }}</p>
            </template>
        </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    question: {
        type: String,
        required: true,
    },
    processing: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const content = ref('');
const loading = ref(false);
const errorMessage = ref('');

onMounted(() => {
    loading.value = true;
    axios.post('/student/prompts/content', {question: props.question})
        .then(response => {
            content.value = response.data.content;
            loading.value = false;
        })
        .catch(error => {
            errorMessage.value = error.response.data.message
            loading.value = false;
        });
});
</script>
