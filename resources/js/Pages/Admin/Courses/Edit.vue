<template>
    <AdminLayout title="Edit Course">
        <div class="space-y-6">
            <div class="mb-6">
                <Link :href="route('admin.courses.index')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    &larr; Back to Courses
                </Link>
            </div>

            <!-- Course Details -->
            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Course Details</h3>

                    <form @submit.prevent="submitCourse" class="space-y-6">
                        <div>
                            <InputLabel for="title" value="Title"/>
                            <TextInput id="title" v-model="courseForm.title" type="text" class="mt-1 block w-full" required/>
                            <InputError :message="courseForm.errors.title" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="description" value="Description"/>
                            <TextareaInput id="description" v-model="courseForm.description" class="mt-1 block w-full" rows="4" required/>
                            <InputError :message="courseForm.errors.description" class="mt-2"/>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="image_url" value="Image URL"/>
                                <TextInput id="image_url" v-model="courseForm.image_url" type="text" class="mt-1 block w-full"/>
                                <InputError :message="courseForm.errors.image_url" class="mt-2"/>
                            </div>

                            <div>
                                <InputLabel for="length_in_weeks" value="Length (weeks)"/>
                                <TextInput id="length_in_weeks" v-model="courseForm.length_in_weeks" type="number" min="1" class="mt-1 block w-full" required/>
                                <InputError :message="courseForm.errors.length_in_weeks" class="mt-2"/>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="courseForm.processing">
                                Update Course
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Course Weeks -->
            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Course Weeks</h3>
                        <Link :href="route('admin.courses.prompts.create', course.id)" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Add Week
                        </Link>
                    </div>

                    <div v-if="course.course_prompts?.length" class="space-y-3">
                        <div v-for="prompt in course.course_prompts" :key="prompt.id" class="flex items-center justify-between p-4 bg-gray-50 dark:bg-neutral-700 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Week {{ prompt.week_number }}: {{ prompt.title }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ prompt.description?.substring(0, 80) }}...</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <Link :href="route('admin.courses.prompts.edit', [course.id, prompt.id])" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 text-sm">
                                    Edit
                                </Link>
                                <button @click="deletePrompt(prompt)" class="text-red-600 hover:text-red-900 dark:text-red-400 text-sm">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-gray-500 dark:text-gray-400">No weeks added yet.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    course: Object,
});

const courseForm = useForm({
    title: props.course.title,
    description: props.course.description,
    image_url: props.course.image_url || '',
    length_in_weeks: props.course.length_in_weeks,
});

const submitCourse = () => {
    courseForm.put(route('admin.courses.update', props.course.id));
};

const deletePrompt = (prompt) => {
    if (confirm('Are you sure you want to delete this week?')) {
        router.delete(route('admin.courses.prompts.destroy', [props.course.id, prompt.id]));
    }
};
</script>
