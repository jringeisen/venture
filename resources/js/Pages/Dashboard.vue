<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex space-x-3 w-full">
                    <div class="space-y-3 w-1/3">
                        <div class="bg-white h-32 dark:bg-gray-800 p-6 space-y-2 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="text-gray-500 dark:text-gray-100">Total Questions</div>
                            <p class="text-4xl font-bold">{{ totalQuestions }}</p>
                        </div>

                        <div class="bg-white h-32 p-6 dark:bg-gray-800 space-y-2 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="text-gray-500 dark:text-gray-100">Daily Questions</div>
                            <p class="text-4xl font-bold">{{ dailyQuestions }}</p>
                        </div>
                    </div>

                    <div class="w-2/3 bg-white p-6 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="text-gray-500 dark:text-gray-100">Subjects</div>
                        <apexchart width="100%" type="pie" :options="options" :series="series"></apexchart>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    totalQuestions: Number,
    dailyQuestions: Number,
    pieChartData: Object
})

const options = ref({
    labels: props.pieChartData.labels
});

const series = ref(props.pieChartData.series);
</script>
