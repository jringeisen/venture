<template>
    <Head title="Student" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white shadow rounded-lg overflow-hidden dark:bg-primary-gray">
                    <div class="py-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <ul v-if="questions.data.length > 0" role="list" class="-mb-8">
                                <li v-for="(question, index) in questions.data" :key="index">
                                    <div class="relative pb-8">
                                        <span
                                            v-if="index !== questions.data.length - 1"
                                            class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-neutral-700"
                                            aria-hidden="true"
                                        />
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    :class="{
                                                        'bg-yellow-500 text-yellow-800': question.prompt_answer.subject_category === 'science',
                                                        'bg-blue-500 text-blue-800': question.prompt_answer.subject_category === 'technology',
                                                    }"
                                                    class="h-8 w-8 bg-green-600 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-neutral-700">
                                                    {{ question.prompt_answer.subject_category[0].toUpperCase() }}
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p @click.prevent="handleToggleContent(question.id)" class="text-sm text-gray-700 cursor-pointer dark:text-neutral-400">{{ question.question }}</p>
                                                    <div v-if="toggleContent === question.id" class="bg-gray-100 p-2 rounded-lg mt-2 dark:bg-neutral-700">
                                                        <p class="font-semibold text-sm text-gray-500 dark:text-neutral-400">{{ capitalize(question.prompt_answer.subject_category) }}</p>
                                                        <p class="mt-2 whitespace-pre-wrap text-sm text-gray-500 dark:text-neutral-400">{{ question.prompt_answer.content }}</p>
                                                    </div>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500 dark:text-neutral-400">
                                                    <time :datetime="question.created_at">{{ question.created_at }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div v-else>
                                <p class="text-sm text-gray-500 bg-gray-100 rounded-full p-4 text-center dark:bg-neutral-700 dark:text-neutral-400">No questions</p>
                            </div>
                        </div>
                    </div>
                    <Pagination v-if="questions.total > questions.per_page" :data="questions" class="dark:bg-neutral-600" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import pkg from 'lodash';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    questions: Object,
    topic: String,
});

const { capitalize } = pkg;

const toggleContent = ref('');

const handleToggleContent = (id) => {
    if (toggleContent.value === id) {
        toggleContent.value = '';
        return;
    }

    toggleContent.value = id;
};
</script>
