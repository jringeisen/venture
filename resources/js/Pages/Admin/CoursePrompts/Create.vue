<template>
    <AdminLayout title="Add Week">
        <div class="max-w-3xl">
            <div class="mb-6">
                <Link :href="route('admin.courses.edit', course.id)" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    &larr; Back to Course
                </Link>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Add Week to {{ course.title }}</h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <InputLabel for="week_number" value="Week Number"/>
                                <TextInput id="week_number" v-model="form.week_number" type="number" min="1" class="mt-1 block w-full" required/>
                                <InputError :message="form.errors.week_number" class="mt-2"/>
                            </div>

                            <div>
                                <InputLabel for="days_count" value="Days per Week"/>
                                <select id="days_count" v-model="form.days_count" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white shadow-sm">
                                    <option :value="1">1 day</option>
                                    <option :value="2">2 days</option>
                                    <option :value="3">3 days</option>
                                    <option :value="4">4 days</option>
                                    <option :value="5">5 days</option>
                                    <option :value="6">6 days</option>
                                    <option :value="7">7 days</option>
                                </select>
                                <InputError :message="form.errors.days_count" class="mt-2"/>
                            </div>

                            <div>
                                <InputLabel for="estimated_duration_minutes" value="Duration (minutes)"/>
                                <TextInput id="estimated_duration_minutes" v-model="form.estimated_duration_minutes" type="number" min="1" class="mt-1 block w-full"/>
                                <InputError :message="form.errors.estimated_duration_minutes" class="mt-2"/>
                            </div>
                        </div>

                        <div>
                            <InputLabel for="title" value="Title"/>
                            <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required/>
                            <InputError :message="form.errors.title" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="description" value="Description"/>
                            <TextareaInput id="description" v-model="form.description" class="mt-1 block w-full" rows="3"/>
                            <InputError :message="form.errors.description" class="mt-2"/>
                        </div>

                        <p class="text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-neutral-700 p-3 rounded-lg">
                            Content, learning objectives, and trivia questions are managed at the day level. After creating this week, you can add days and their content.
                        </p>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="form.processing">
                                Add Week
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    course: Object,
    nextWeek: Number,
});

const form = useForm({
    week_number: props.nextWeek,
    days_count: 5,
    title: '',
    description: '',
    estimated_duration_minutes: null,
});

const submit = () => {
    form.post(route('admin.courses.weeks.store', props.course.id));
};
</script>
