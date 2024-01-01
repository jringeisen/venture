<template>
    <Head title="Dashboard" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 space-y-4 md:grid-cols-12 md:grid-rows-2 md:grid-flow-col md:space-y-0 md:gap-4">
                <div class="bg-white border p-6 space-y-2 overflow-hidden shadow-sm sm:rounded-lg md:col-span-3 dark:bg-primary-gray dark:border-none">
                    <div class="text-gray-500 dark:text-neutral-400">Total Questions</div>
                    <p class="text-4xl font-bold dark:text-neutral-400">{{ totalQuestions }}</p>
                </div>

                <div class="bg-white border p-6 space-y-2 overflow-hidden shadow-sm sm:rounded-lg md:col-span-3 dark:bg-primary-gray dark:border-none">
                    <div class="text-gray-500 dark:text-neutral-400">Daily Questions</div>
                    <p class="text-4xl font-bold dark:text-neutral-400">{{ dailyQuestions }}</p>
                </div>

                <div v-if="isClient" class="relative bg-white p-6 border overflow-hidden shadow-sm sm:rounded-lg md:row-span-2 md:col-span-9 dark:bg-primary-gray dark:border-none">
                    <div class="absolute text-gray-500 dark:text-neutral-400">Subjects</div>
                    <div v-if="series.length === 0" class="flex justify-center items-center w-full h-full">
                        <p class="bg-neutral-200 w-full text-center p-5 rounded-lg text-neutral-500">No Data</p>
                    </div>
                    <ApexChart v-else width="100%" height="100%" type="pie" :options="options" :series="series"></ApexChart>
                </div>
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
    pieChartData: Object
})

const options = ref({
    labels: props.pieChartData.labels,
    legend: {
        labels: {colors: '#a3a3a3'}
    }
});

const series = ref(props.pieChartData.series);
</script>
