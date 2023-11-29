<template>
    <div>
        <div v-if="loading || processing" role="status" class="max-w-sm animate-pulse">
            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-20 mb-2.5"></div>
            <span class="sr-only">Loading...</span>
        </div>
        <div v-else>
            <h1 class="text-2xl font-bold text-teal-600">{{ capitalize(subject) }}</h1>
            <h3 class="text-lg font-bold text-amber-700">{{ capitalize(subCategory) }}</h3>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { capitalize } from 'lodash';

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

onMounted(() => {
    loading.value = true;
    axios.post('/student/prompts/subject', {question: props.question}).then(response => {
        subject.value = response.data.subject;
        subCategory.value = response.data.sub_category;
        loading.value = false;
    });
});
</script>
