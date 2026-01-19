<template>
    <AdminLayout title="Create Course">
        <div class="max-w-2xl">
            <div class="mb-6">
                <Link :href="route('admin.courses.index')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    &larr; Back to Courses
                </Link>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Create New Course</h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="title" value="Title"/>
                            <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required/>
                            <InputError :message="form.errors.title" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="description" value="Description"/>
                            <TextareaInput id="description" v-model="form.description" class="mt-1 block w-full" rows="4" required/>
                            <InputError :message="form.errors.description" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="image_url" value="Image URL"/>
                            <TextInput id="image_url" v-model="form.image_url" type="text" class="mt-1 block w-full"/>
                            <InputError :message="form.errors.image_url" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="length_in_weeks" value="Length (weeks)"/>
                            <TextInput id="length_in_weeks" v-model="form.length_in_weeks" type="number" min="1" class="mt-1 block w-full" required/>
                            <InputError :message="form.errors.length_in_weeks" class="mt-2"/>
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="form.processing">
                                Create Course
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

const form = useForm({
    title: '',
    description: '',
    image_url: '',
    length_in_weeks: 1,
});

const submit = () => {
    form.post(route('admin.courses.store'));
};
</script>
