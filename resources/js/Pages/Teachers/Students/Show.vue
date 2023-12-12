<template>
    <Head title="Student" />

    <AuthenticatedLayout>
        <div class="py-12">
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
                        <p class="text-4xl font-bold">{{ student.current_streak }}</p>
                    </div>
                </div>
                <div v-if="Object.keys(categoriesWithCounts).length > 0 || series.length > 0" class="grid grid-cols-1 space-y-4 md:grid-cols-12 md:grid-rows-2 md:grid-flow-col md:space-y-0 md:gap-3">
                    <div
                        v-if="Object.keys(categoriesWithCounts).length > 0"
                        class="bg-white border p-6 dark:bg-gray-800 space-y-2 overflow-hidden shadow-sm sm:rounded-lg md:row-span-2 md:col-span-4"
                        :class="Object.keys(categoriesWithCounts).length > 0 && series.length > 0 ? 'md:col-span-4' : 'md:col-span-12'"
                    >
                        <div class="text-gray-500 dark:text-gray-100">Topics</div>
                        <ul>
                            <li v-for="(value, index) in categoriesWithCounts" :key="index" class="flex justify-between items-center">
                                <Link :href="route('student.topic.show', kebabCase(index))" class="underline text-blue-500">{{ startCase(index) }}</Link>
                                <p class="font-bold">{{ value }}</p>
                            </li>
                        </ul>
                    </div>

                    <div
                        v-if="series.length > 0"
                        class="relative bg-white p-6 border dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg md:row-span-2 md:col-span-8"
                        :class="Object.keys(categoriesWithCounts).length > 0 && series.length > 0 ? 'md:col-span-8' : 'md:col-span-12'"
                    >
                        <div class="absolute text-gray-500 dark:text-gray-100">Subjects</div>
                        <apexchart width="100%" height="100%" type="pie" :options="options" :series="series"></apexchart>
                    </div>
                </div>

                <div class="bg-white shadow p-8 rounded-lg">
                    <div class="flex justify-between items-center">
                        <p class="text-2xl font-bold">{{ student.name }}</p>
                        <div>
                            <label for="filter-by-date" class="block text-sm font-medium leading-6 text-gray-900 sr-only">Location</label>
                            <input
                                id="filter-by-date"
                                type="date"
                                name="filter-by-date"
                                v-model="filterByDate"
                                @change="router.visit(route('students.show', student.id) + '?date=' + filterByDate)"
                                class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            >
                        </div>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <ul v-if="student.prompt_questions.length > 0" role="list" class="-mb-8">
                                    <li v-for="(question, index) in student.prompt_questions" :key="index">
                                        <div class="relative pb-8">
                                            <span
                                                v-if="index !== student.prompt_questions.length - 1"
                                                class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200"
                                                aria-hidden="true"
                                            />
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span
                                                        :class="{
                                                            'bg-yellow-500 text-yellow-800': question.prompt_answer.subject_category === 'science',
                                                            'bg-blue-500 text-blue-800': question.prompt_answer.subject_category === 'technology',
                                                        }"
                                                        class="h-8 w-8 bg-green-600 rounded-full flex items-center justify-center ring-8 ring-white">
                                                        {{ question.prompt_answer.subject_category[0].toUpperCase() }}
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p @click.prevent="handleToggleContent(question.id)" class="text-sm text-gray-700 cursor-pointer">{{ question.question }}</p>
                                                        <div v-if="toggleContent === question.id" class="bg-gray-100 p-2 rounded-lg mt-2">
                                                            <p class="font-semibold text-sm text-gray-500">{{ capitalize(question.prompt_answer.subject_category) }}</p>
                                                            <p class="mt-2 whitespace-pre-wrap text-sm text-gray-500">{{ question.prompt_answer.content }}</p>
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
                                    <p class="text-sm text-gray-500 bg-gray-100 rounded-full p-4 text-center">No questions</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { capitalize } from 'lodash';
import { startCase, kebabCase } from 'lodash';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    student: Object,
    date: String,
    categoriesWithCounts: Object,
    totalQuestions: Number,
    dailyQuestions: Number,
    pieChartData: Object
});

const toggleContent = ref('');
const filterByDate = ref(props.date);

const handleToggleContent = (id) => {
    if (toggleContent.value === id) {
        toggleContent.value = '';
        return;
    }

    toggleContent.value = id;
};

const options = ref({
    labels: props.pieChartData.labels
});

const series = ref(props.pieChartData.series);
</script>
