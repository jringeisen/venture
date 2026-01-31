<template>
    <Head title="Compliance"/>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Student Summary Cards -->
            <div class="bg-white shadow p-8 rounded-lg dark:bg-primary-gray">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-400">Student Overview</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-neutral-400">
                            Current year instruction summary for each student.
                        </p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="student in students" :key="student.id" class="rounded-lg border border-gray-200 p-6 dark:border-neutral-600">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-neutral-300">{{ student.name }}</h3>
                            <span v-if="student.grade" class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">Grade {{ student.grade }}</span>
                        </div>
                        <dl class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-xs text-gray-500 dark:text-neutral-500">Instruction Days</dt>
                                <dd class="text-lg font-semibold text-gray-900 dark:text-neutral-300">{{ student.instruction_days }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 dark:text-neutral-500">Instruction Hours</dt>
                                <dd class="text-lg font-semibold text-gray-900 dark:text-neutral-300">{{ student.instruction_hours }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 dark:text-neutral-500">Active Courses</dt>
                                <dd class="text-lg font-semibold text-gray-900 dark:text-neutral-300">{{ student.courses_active }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 dark:text-neutral-500">Completed</dt>
                                <dd class="text-lg font-semibold text-gray-900 dark:text-neutral-300">{{ student.courses_completed }}</dd>
                            </div>
                        </dl>
                        <div class="mt-4">
                            <PrimaryButton @click.prevent="generateReport(student.id)" class="w-full justify-center">Generate Report</PrimaryButton>
                        </div>
                    </div>
                </div>

                <div v-if="students.length === 0" class="mt-6 text-center text-sm text-gray-500 dark:text-neutral-400">
                    No students found. Add a student to get started.
                </div>
            </div>

            <!-- Reports Table -->
            <div class="bg-white shadow p-8 rounded-lg dark:bg-primary-gray">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-400">Compliance Reports</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-neutral-400">
                            Previously generated compliance reports.
                        </p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <PrimaryButton @click.prevent="router.get(route('parent.compliance.reports.create'))">New Report</PrimaryButton>
                    </div>
                </div>

                <div v-if="reports.data.length > 0" class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300 dark:divide-neutral-700">
                                    <thead class="bg-gray-50 dark:bg-neutral-600">
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-neutral-300">Title</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-300">Student</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-300">Period</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-300">Status</th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white dark:bg-neutral-500 dark:divide-neutral-600">
                                        <tr v-for="report in reports.data" :key="report.id">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 dark:text-primary-gray">
                                                {{ report.title }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-primary-gray">
                                                {{ report.student_name }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-primary-gray">
                                                {{ report.period_start }} - {{ report.period_end }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-primary-gray">
                                                <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                                    {{ report.status }}
                                                </span>
                                            </td>
                                            <td class="flex justify-end items-baseline gap-3 whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <Link :href="route('parent.compliance.reports.show', report.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">View</Link>
                                                <a :href="route('parent.compliance.reports.pdf', report.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">PDF</a>
                                                <button @click.prevent="deleteReport(report.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div v-if="reports.total > reports.per_page" class="border-t">
                                    <Pagination :data="reports" class="dark:bg-neutral-600"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="mt-6 text-center text-sm text-gray-500 dark:text-neutral-400">
                    No compliance reports generated yet.
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, router, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';

defineOptions({
    layout: AuthenticatedLayout
});

defineProps({
    students: Array,
    reports: Object,
});

const form = useForm({});

const generateReport = (studentId) => {
    router.get(route('parent.compliance.reports.create'), { student_id: studentId });
};

const deleteReport = (reportId) => {
    if (confirm('Are you sure you want to delete this report?')) {
        form.delete(route('parent.compliance.reports.destroy', reportId));
    }
};
</script>
