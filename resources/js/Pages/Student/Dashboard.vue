<template>
    <Head title="Dashboard" />

    <div class="md:py-12">
        <div class="max-w-7xl mx-auto space-y-3 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                <div class="bg-white border dark:bg-gray-800 p-6 space-y-2 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 dark:text-gray-100">Total Questions</div>
                    <p class="text-4xl font-bold">{{ totalQuestions }}</p>
                </div>

                <div class="bg-white border p-6 dark:bg-gray-800 space-y-2 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 dark:text-gray-100">Daily Questions</div>
                    <p class="text-4xl font-bold">{{ dailyQuestions }}</p>
                </div>

                <div class="bg-white border p-6 dark:bg-gray-800 space-y-2 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-500 dark:text-gray-100">Current Streak</div>
                    <p class="text-4xl font-bold">{{ $page.props.auth.user.current_streak }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 space-y-4 md:grid-cols-12 md:grid-rows-2 md:grid-flow-col md:space-y-0 md:gap-3">
                <div class="bg-white border p-6 dark:bg-gray-800 space-y-2 overflow-hidden shadow-sm sm:rounded-lg md:row-span-2 md:col-span-4">
                    <div class="text-gray-500 dark:text-gray-100">Topics</div>
                    <ul>
                        <li v-for="(value, index) in categoriesWithCounts" :key="index" class="flex justify-between items-center">
                            <Link :href="route('student.topic.show', kebabCase(index))" class="underline text-blue-500">{{ startCase(index) }}</Link>
                            <p class="font-bold">{{ value }}</p>
                        </li>
                    </ul>
                </div>

                <div class="relative bg-white p-6 border dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg md:row-span-2 md:col-span-8">
                    <div class="absolute text-gray-500 dark:text-gray-100">Subjects</div>
                    <apexchart width="100%" height="100%" type="pie" :options="options" :series="series"></apexchart>
                </div>
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
    categoriesWithCounts: Object,
    totalQuestions: Number,
    dailyQuestions: Number,
    pieChartData: Object,
});

const options = ref({
    labels: props.pieChartData.labels
});

const series = ref(props.pieChartData.series);
</script>
