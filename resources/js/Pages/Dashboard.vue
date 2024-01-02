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
        <Modal :show="!viewedStarterGuide" max-width="3xl">
            <div class="mx-auto bg-white p-10 prose prose-neutral">
                <h1>Venture Starting Guide</h1>
                <p>Welcome to Venture, the AI-driven learning platform where education meets innovation. This guide will walk you through the essential steps to get started.</p>

                <h2>1. Create a Student Account</h2>
                <p>Click Student in the left navigation bar, then Add Student. You'll need to add details like the name, age, grade, and timezone.</p>
                <p>An email will be sent to the student with a temporary password. They will need to login and reset their password.</p>

                <h2>2. Student Login and Password Change</h2>
                <p>
                    The student will need to click the link in the email they receive and use the password provided to login. Once they
                    have logged in they will be prompted to change their password to something unique.
                </p>

                <h2>3. Engaging with the AI Learning System</h2>
                <p>
                    Now the student can begin learning, they will need to click the Learn link in the left navigation bar. This is where they can start asking questions
                    and start their journey with Venture.
                </p>

                <h2>4. Parental Dashboard and Monitoring Progress</h2>
                <p>
                    As a parent, you can monitor your child's progress by clicking the Dashboard link in the left navigation bar. This will show you the total number of questions
                    asked by your child, as well as the number of questions asked today. You can also see the subjects they are learning and how many questions they have asked
                    in each subject.
                </p>

                <PrimaryButton @click.prevent="addStudent()" class="w-full py-4 justify-center">Add Student!</PrimaryButton>
            </div>
        </Modal>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { defineAsyncComponent, onMounted, ref } from 'vue';
import { router, Head, useForm } from '@inertiajs/vue3';

defineOptions({
    layout: AuthenticatedLayout
});

const form = useForm({
    'viewed_starter_guide': true,
    'redirect_route': 'students.create',
});

const ApexChart = defineAsyncComponent(() =>
  import('vue3-apexcharts')
);

const isClient = ref(false);

onMounted(() => {
  isClient.value = true;
});

const props = defineProps({
    viewedStarterGuide: Boolean,
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

const addStudent = () => {
    form.patch(route('profile.update'));
}
</script>
