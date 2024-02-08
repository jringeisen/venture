<template>
    <Head title="Dashboard" />

    <div class="md:py-12">
        <div class="max-w-7xl mx-auto space-y-8 sm:px-6 lg:px-8">
            <div>
                <h1 class="text-2xl font-bold">Welcome back, {{ $page.props.auth.user.name }}!</h1>
                <p class="text-sm">Here's an overview of your study progress this week.</p>
            </div>
            <div>
                <p>Overview</p>
                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <div class="bg-white border p-3 space-y-2 overflow-hidden shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-none">
                        <div class="flex items-center space-x-4">
                            <div class="bg-primary-yellow h-12 w-12 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold dark:text-neutral-400">{{ totalQuestions }}</p>
                                <div class="text-gray-500 dark:text-neutral-400">Total Questions</div>
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
                                <div class="text-gray-500 dark:text-neutral-400">Today's Questions</div>
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
                                <div class="text-gray-500 dark:text-neutral-400">Total Words Read</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border p-3 space-y-2 overflow-hidden shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-none">
                        <div class="flex items-center space-x-4">
                            <div class="bg-primary-yellow h-12 w-12 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold dark:text-neutral-400">{{ $page.props.auth.user.current_streak }}</p>
                                <div class="text-gray-500 dark:text-neutral-400">Current Streak</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <ApexChart v-if="isClient" width="100%" height="300px" type="line" :options="chartOptions" :series="series"></ApexChart>
            </div>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineAsyncComponent, onMounted, ref } from 'vue';

defineOptions({
    layout: AuthenticatedLayout
});

const ApexChart = defineAsyncComponent(() =>
  import('vue3-apexcharts')
);

const isClient = ref(false);

onMounted(() => {
  isClient.value = true;
});

const props = defineProps({
    totalQuestions: Number,
    dailyQuestions: Number,
    totalWordsRead: String,
    pieChartData: Object,
    randomQuestion: Object,
});

const series = ref([
    {
        name: "Desktops",
        data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
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
        align: 'left'
    },
    grid: {
        strokeDashArray: 4,
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
    }
})
</script>
