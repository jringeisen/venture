<template>
    <div>
        <div v-if="loading || processing" role="status" class="max-w-sm animate-pulse">
            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-48 mb-4"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-primary-gray w-20 mb-2.5"></div>
            <span class="sr-only">Loading...</span>
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

const loading = ref(false);
const subject = ref('');
const subCategory = ref('');

onMounted(() => {
    loading.value = true;
    axios.post('/student/prompts/subject', {question: props.question}).then(response => {
        subject.value = response.data.subject;
        subCategory.value = response.data.subCategory;
        loading.value = false;
    });
});
</script>
