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
                        v-for="(item, index) in questions"
                        :key="index"
                        class="mb-2.5 cursor-pointer text-primary-yellow underline"
                        :class="{'text-yellow-300 font-bold': item.selected}"
                        @click.prevent="questionClicked(item)"
                    >
                        {{ item.question }}
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

const questions = ref(JSON.parse(localStorage.getItem('questions')) || []);
const loading = ref(false);

onMounted(() => {
    if (questions.value.length === 0) {
        loading.value = true;

        axios.post('/student/prompts/questions', {question: props.question}).then(response => {
            questions.value = response.data.questions;
            loading.value = false;
        });
    }
});

const questionClicked = (item) => {
    const formattedQuestions = questions.value.map((q, index) => {
        return {
            'question': q.question,
            'selected': q.question === item.question || q.selected
        }
    });

    localStorage.setItem('questions', JSON.stringify(formattedQuestions));

    emit('questionClicked', {question: item.question});
}
</script>
