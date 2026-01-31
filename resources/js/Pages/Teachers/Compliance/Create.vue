<template>
    <Head title="Generate Compliance Report"/>

    <div class="pt-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-8 rounded-lg dark:bg-neutral-800">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-400">Generate Compliance Report</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-neutral-400">
                            Generate a compliance report for a student based on Florida homeschool portfolio requirements.
                        </p>
                    </div>
                </div>
                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <form class="space-y-6" @submit.prevent="submit()">
                                <div>
                                    <InputLabel for="student_id" value="Student"/>
                                    <select id="student_id" v-model="form.student_id" class="mt-2 w-full border-gray-300 focus:border-primary-gray focus:ring-primary-gray rounded-md shadow-sm dark:focus:border-neutral-900 dark:focus:ring-neutral-900 dark:border-neutral-900 dark:bg-primary-gray dark:text-neutral-400 dark:placeholder:text-neutral-400" required>
                                        <option value="" disabled>Select a student</option>
                                        <option v-for="student in students" :key="student.id" :value="student.id">
                                            {{ student.name }} <span v-if="student.grade">(Grade {{ student.grade }})</span>
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.student_id"/>
                                </div>

                                <div>
                                    <InputLabel for="state" value="State"/>
                                    <select id="state" v-model="form.state" class="mt-2 w-full border-gray-300 focus:border-primary-gray focus:ring-primary-gray rounded-md shadow-sm dark:focus:border-neutral-900 dark:focus:ring-neutral-900 dark:border-neutral-900 dark:bg-primary-gray dark:text-neutral-400 dark:placeholder:text-neutral-400" required>
                                        <option v-for="(label, value) in states" :key="value" :value="value">{{ label }}</option>
                                    </select>
                                    <InputError :message="form.errors.state"/>
                                </div>

                                <div>
                                    <InputLabel for="title" value="Report Title (optional)"/>
                                    <div class="mt-2">
                                        <TextInput v-model="form.title" id="title" type="text" class="w-full" placeholder="Auto-generated if left blank"/>
                                        <InputError :message="form.errors.title"/>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3">
                                    <SecondaryButton @click.prevent="router.get(route('parent.compliance.index'))">Cancel</SecondaryButton>
                                    <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Generate Report</PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    students: Array,
    states: Object,
});

const preselectedStudentId = typeof window !== 'undefined'
    ? new URLSearchParams(window.location.search).get('student_id') || ''
    : '';

const form = useForm({
    student_id: preselectedStudentId ? parseInt(preselectedStudentId) : '',
    state: 'FL',
    title: '',
});

const submit = () => {
    form.post(route('parent.compliance.reports.store'));
};
</script>
