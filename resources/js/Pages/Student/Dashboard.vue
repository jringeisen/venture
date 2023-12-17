<template>
    <Head title="Dashboard" />

    <div class="md:py-12">
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
                    <p class="text-4xl font-bold dark:text-neutral-400">{{ $page.props.auth.user.current_streak }}</p>
                </div>
            </div>
            <div class="relative bg-white h-96 p-6 border overflow-hidden shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-none">
                <div class="absolute text-gray-500 dark:text-neutral-400">Subjects</div>
                <apexchart width="100%" height="100%" type="pie" :options="options" :series="series"></apexchart>
            </div>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { startCase, kebabCase } from 'lodash';
import { ref } from 'vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    totalQuestions: Number,
    dailyQuestions: Number,
    pieChartData: Object,
});

const options = ref({
    labels: props.pieChartData.labels,
    legend: {
        labels: {colors: '#a3a3a3'}
    }
});

const series = ref(props.pieChartData.series);
</script>
