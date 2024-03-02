<template>
    <div>
        <div v-if="loading || processing" role="status" class="max-w-sm animate-pulse">
            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-32 mb-4"></div>
            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-60 mb-4"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-primary-gray w-72 mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-primary-gray w-72 mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-primary-gray w-56 mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-primary-gray w-72 mb-2.5"></div>
            <span class="sr-only">Loading...</span>
        </div>
        <div v-else>
            <div class="whitespace-pre-wrap mt-14">
                <ul>
                    <li
                        v-for="(question, index) in questions"
                        :key="index"
                        class="mb-2.5 cursor-pointer text-primary-yellow underline"
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
    questions: {
        type: Array,
        required: false,
    },
    linkClicked: {
        type: Boolean,
        required: true,
        default: false,
    }
})

const emit = defineEmits(['questionClicked']);

const questions = ref(props.questions);
const loading = ref(false);

onMounted(() => {
    if (!props.linkClicked) {
        loading.value = true;

        axios.post('/student/prompts/questions', {question: props.question}).then(response => {
            questions.value = response.data.questions;
            loading.value = false;
        });
    }
});

const questionClicked = (question) => {
    emit('questionClicked', {
        question: question,
        questions: questions.value,
    });
}
</script>
