<template>
    <div>
        <div v-if="loading || processing" role="status" class="max-w-sm animate-pulse">
            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-32 mb-4"></div>
            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-60 mb-4"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-72 mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-72 mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-56 mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-72 mb-2.5"></div>
            <span class="sr-only">Loading...</span>
        </div>
        <div v-else>
            <h3 class="text-lg font-bold text-amber-700">Explore More</h3>
            <div class="whitespace-pre-wrap">
                <ul>
                    <li
                        v-for="(question, index) in questions"
                        :key="index"
                        class="mb-2.5 cursor-pointer text-blue-400 underline"
                        @click.prevent="questionClicked(question)"
                    >
                        {{ question }}
                    </li>
                </ul>
            </div>
        </div>
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
        required: true,
    },
})

const emit = defineEmits(['questionClicked']);

const questions = ref([]);
const loading = ref(false);

onMounted(() => {
    loading.value = true;
    axios.post('/student/prompts/questions', {question: props.question}).then(response => {
        questions.value = response.data.questions;
        loading.value = false;
    });
});

const questionClicked = (question) => {
    emit('questionClicked', question);
}
</script>
