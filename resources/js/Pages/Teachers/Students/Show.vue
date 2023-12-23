<template>
    <Head title="View Student" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-3 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                <div class="bg-white border p-6 space-y-2 overflow-hidden shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-none">
                    <div class="text-gray-500 dark:text-neutral-400">Total Questions</div>
                    <p class="text-4xl font-bold dark:text-neutral-400">{{ totalQuestions }}</p>
                </div>

                <div class="bg-white border p-6 space-y-2 overflow-hidden shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-none">
                    <div class="text-gray-500 dark:text-neutral-400">Daily Questions</div>
                    <p class="text-4xl font-bold dark:text-neutral-400">{{ dailyQuestions }}</p>
                </div>

                <div class="bg-white border p-6 space-y-2 overflow-hidden shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-none">
                    <div class="text-gray-500 dark:text-neutral-400">Current Streak</div>
                    <p class="text-4xl font-bold dark:text-neutral-400">{{ student.current_streak }}</p>
                </div>
            </div>
            <div v-if="Object.keys(categoriesWithCounts).length > 0 || series.length > 0" class="grid grid-cols-1 space-y-4 md:grid-cols-12 md:grid-rows-2 md:grid-flow-col md:space-y-0 md:gap-3">
                <div
                    v-if="Object.keys(categoriesWithCounts).length > 0"
                    class="bg-white border p-6 space-y-2 overflow-hidden shadow-sm sm:rounded-lg md:row-span-2 md:col-span-4 dark:bg-primary-gray dark:border-none"
                    :class="Object.keys(categoriesWithCounts).length > 0 && series.length > 0 ? 'md:col-span-4' : 'md:col-span-12'"
                >
                    <div class="text-gray-500 dark:text-neutral-400">Topics</div>
                    <ul>
                        <li v-for="(value, index) in categoriesWithCounts" :key="index" class="flex justify-between items-center">
                            <p class="dark:text-neutral-400">{{ startCase(index) }}</p>
                            <p class="font-bold dark:text-neutral-400">{{ value }}</p>
                        </li>
                    </ul>
                </div>

                <div
                    v-if="series.length > 0"
                    class="relative bg-white p-6 border overflow-hidden shadow-sm sm:rounded-lg md:row-span-2 md:col-span-8 dark:bg-primary-gray dark:border-none"
                    :class="Object.keys(categoriesWithCounts).length > 0 && series.length > 0 ? 'md:col-span-8' : 'md:col-span-12'"
                >
                    <div class="absolute text-gray-500 dark:text-neutral-400">Subjects</div>
                    <apexchart width="100%" height="100%" type="pie" :options="options" :series="series"></apexchart>
                </div>
            </div>

            <div class="bg-white shadow p-8 rounded-lg dark:bg-primary-gray">
                <div class="flex justify-between items-center">
                    <p class="text-2xl font-bold dark:text-neutral-400">{{ student.name }}</p>
                </div>
                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <ul v-if="student.prompt_questions.data.length > 0" role="list" class="-mb-8">
                                <li v-for="(question, index) in student.prompt_questions.data" :key="index">
                                    <div class="relative pb-8">
                                        <span
                                            v-if="index !== student.prompt_questions.data.length - 1"
                                            class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-neutral-700"
                                            aria-hidden="true"
                                        />
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    class="h-8 w-8 bg-primary-yellow rounded-full flex items-center justify-center ring-8 ring-white dark:ring-neutral-700">
                                                    {{ question.prompt_answer?.subject_category ? question.prompt_answer?.subject_category[0].toUpperCase() : '' }}
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p @click.prevent="handleToggleContent(question.id)" class="text-sm text-gray-700 cursor-pointer dark:text-neutral-400">{{ question.question }}</p>
                                                    <div v-if="toggleContent === question.id" class="bg-gray-100 p-2 rounded-lg mt-2 dark:bg-neutral-700">
                                                        <p class="font-semibold text-sm text-gray-500 dark:text-neutral-400">{{ startCase(question.prompt_answer.subject_category) }}</p>
                                                        <p class="mt-2 whitespace-pre-wrap text-sm text-gray-500 dark:text-neutral-400">{{ question.prompt_answer.content }}</p>
                                                    </div>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                    <time :datetime="question.created_at">{{ question.created_at }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div v-else>
                                <p class="text-sm text-gray-500 bg-gray-100 rounded-full p-4 text-center dark:bg-neutral-600 dark:text-primary-dark-gray">No questions</p>
                            </div>
                        </div>
                        <Pagination :data="student.prompt_questions" class="dark:bg-primary-gray" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { startCase  } from 'lodash';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    student: Object,
    categoriesWithCounts: Object,
    totalQuestions: Number,
    dailyQuestions: Number,
    pieChartData: Object
});

const toggleContent = ref('');

const handleToggleContent = (id) => {
    if (toggleContent.value === id) {
        toggleContent.value = '';
        return;
    }

    toggleContent.value = id;
};

const options = ref({
    labels: props.pieChartData.labels,
    legend: {
        labels: {colors: '#a3a3a3'}
    }
});

const series = ref(props.pieChartData.series);
</script>
