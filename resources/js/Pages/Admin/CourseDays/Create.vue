<template>
    <AdminLayout title="Add Day">
        <div class="max-w-3xl">
            <div class="mb-6">
                <Link :href="route('admin.courses.weeks.edit', [course.id, prompt.id])" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    &larr; Back to Week {{ prompt.week_number }}
                </Link>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
                        Add Day to Week {{ prompt.week_number }}: {{ prompt.title }}
                    </h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="day_number" value="Day Number"/>
                                <TextInput id="day_number" v-model="form.day_number" type="number" min="1" max="7" class="mt-1 block w-full" required/>
                                <InputError :message="form.errors.day_number" class="mt-2"/>
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

                        <!-- Learning Objectives -->
                        <div>
                            <InputLabel value="Learning Objectives"/>
                            <div class="mt-2 space-y-2">
                                <div v-for="(objective, index) in form.learning_objectives" :key="index" class="flex gap-2">
                                    <TextInput v-model="form.learning_objectives[index]" type="text" class="block w-full"/>
                                    <button type="button" @click="removeObjective(index)" class="text-red-600 hover:text-red-900">
                                        <XMarkIcon class="h-5 w-5"/>
                                    </button>
                                </div>
                                <button type="button" @click="addObjective" class="text-sm text-indigo-600 hover:text-indigo-500">
                                    + Add Objective
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="form.processing">
                                Create Day
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
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    course: Object,
    prompt: Object,
    nextDay: Number,
});

const form = useForm({
    day_number: props.nextDay,
    title: '',
    description: '',
    learning_objectives: [''],
    estimated_duration_minutes: 15,
});

const addObjective = () => {
    form.learning_objectives.push('');
};

const removeObjective = (index) => {
    form.learning_objectives.splice(index, 1);
};

const submit = () => {
    form.post(route('admin.courses.weeks.days.store', [props.course.id, props.prompt.id]));
};
</script>
