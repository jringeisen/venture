<template>
    <Head title="View Student" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-8 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:items-center sm:justify-between sm:flex-row">
                <div>
                    <h1 class="text-2xl font-bold dark:text-neutral-400">{{ student.name }}</h1>
                    <p class="text-sm dark:text-neutral-400">Here's an overview of this student.</p>
                </div>
                <div>
                    <label for="timeframe" class="block text-sm font-medium leading-6 text-gray-900 sr-only">Location</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="absolute top-2.5 left-8 w-5 h-5 dark:text-primary-yellow">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>

                        <select id="timeframe" v-model="form.timeframe" @change="handleTimeframeChange()" class="mt-2 block w-full rounded-full border-0 py-2 px-14 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-yellow dark:bg-neutral-800 dark:text-neutral-400 dark:ring-0 sm:text-sm sm:leading-6">
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>
            </div>
            <div>
                <div class="grid grid-cols-2 gap-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                    <div class="bg-white border p-3 space-y-2 overflow-hidden shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-none">
                        <div class="flex items-center space-x-4">
                            <div class="bg-primary-yellow h-12 w-12 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold dark:text-neutral-400">{{ totalQuestions }}</p>
                                <div class="text-xs text-gray-500 truncate dark:text-neutral-400">Total Questions</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border p-3 space-y-2 overflow-hidden shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-none">
                        <div class="flex items-center space-x-4">
                            <div class="bg-primary-yellow h-12 w-12 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold dark:text-neutral-400">{{ dailyQuestions }}</p>
                                <div class="text-xs text-gray-500 truncate dark:text-neutral-400">Today's Questions</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border p-3 space-y-2 overflow-hidden shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-none">
                        <div class="flex items-center space-x-4">
                            <div class="bg-primary-yellow h-12 w-12 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold dark:text-neutral-400">{{ totalWordsRead }}</p>
                                <div class="text-xs text-gray-500 truncate dark:text-neutral-400">Total Words Read</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border p-3 space-y-2 overflow-hidden shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-none">
                        <div class="flex items-center space-x-4">
                            <div class="bg-primary-yellow h-12 w-12 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold dark:text-neutral-400">{{ $page.props.auth.user.current_streak }}</p>
                                <div class="text-xs text-gray-500 truncate dark:text-neutral-400">Current Streak</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white border p-3 space-y-2 overflow-hidden col-span-2 shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-none xl:col-span-1">
                        <div class="flex items-center space-x-4">
                            <div class="bg-primary-yellow h-12 w-12 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold dark:text-neutral-400">{{ activeTime }}</p>
                                <div class="text-xs text-gray-500 truncate dark:text-neutral-400">Active Time</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <ApexChart v-if="isClient" width="100%" height="300px" type="line" :options="chartOptions" :series="series"></ApexChart>
            </div>

            <div class="bg-white shadow p-8 rounded-lg dark:bg-primary-gray">
                <div class="flex justify-between items-center">
                    <p class="text-2xl font-bold dark:text-neutral-400">What this student is learning...</p>
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
                                                        <div class="flex justify-between">
                                                            <p class="font-semibold text-sm text-gray-500 dark:text-neutral-400">{{ startCase(question.prompt_answer.subject_category) }}</p>
                                                            <p class="font-semibold text-sm text-gray-500 dark:text-neutral-400">{{ question.prompt_answer.word_count }} words</p>
                                                        </div>
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
                        <Pagination v-if="student.prompt_questions.total > student.prompt_questions.per_page" :data="student.prompt_questions" class="dark:bg-primary-gray" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { defineAsyncComponent, onMounted, ref } from 'vue';
import pkg from 'lodash';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const { startCase } = pkg;

const props = defineProps({
    student: Object,
    totalQuestions: Number,
    dailyQuestions: Number,
    totalWordsRead: String,
    lineChartData: Object,
    activeTime: String,
    timeframe: String,
});

const form = useForm({
    timeframe: props.timeframe
});

const toggleContent = ref('');

const handleToggleContent = (id) => {
    if (toggleContent.value === id) {
        toggleContent.value = '';
        return;
    }

    toggleContent.value = id;
};

const ApexChart = defineAsyncComponent(() =>
  import('vue3-apexcharts')
);

const isClient = ref(false);
const isDarkMode = ref(false);

const handleTimeframeChange = () => {
    form.get(route('parent.users.show', { user: props.student, timeframe: form.timeframe }))
}

onMounted(() => {
    isClient.value = true;

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        isDarkMode.value = e.matches;
        chartOptions.value = {
            title: {
                style: {
                    color: e.matches ? '#a3a3a3' : '#0a0a0a',
                }
            }
        }
    });
});

const series = ref([
    {
        name: 'Time Spent Learning',
        data: props.lineChartData.series
    }
]);

const chartOptions = ref({
    chart: {
        height: 350,
        type: 'line',
        zoom: {
            enabled: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',
        colors: ['#FFCC00']
    },
    title: {
        text: 'Time Spent Learning',
        align: 'left',
        style: {
            fontSize: '16px',
            color: isDarkMode.value ? '#a3a3a3' : '#0a0a0a'
        }
    },
    grid: {
        strokeDashArray: 4,
    },
    xaxis: {
        categories: props.lineChartData.labels,
    },
    yaxis: {
        labels: {
            formatter: function (value) {
                const hours = Math.floor(value / 3600);
                const minutes = Math.floor((value % 3600) / 60);
                return `${hours}:${minutes}`;
            }
        }
    }
})
</script>
