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

                    <Form v-model="formData" :action="route('admin.courses.store')" method="post" class="space-y-6" #default="{ errors, processing }">
                        <div>
                            <InputLabel for="title" value="Title"/>
                            <TextInput id="title" v-model="formData.title" name="title" type="text" class="mt-1 block w-full" required/>
                            <InputError :message="errors.title" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="description" value="Description"/>
                            <TextareaInput id="description" v-model="formData.description" name="description" class="mt-1 block w-full" rows="4" required/>
                            <InputError :message="errors.description" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="image_url" value="Image URL"/>
                            <TextInput id="image_url" v-model="formData.image_url" name="image_url" type="text" class="mt-1 block w-full"/>
                            <InputError :message="errors.image_url" class="mt-2"/>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="length_in_weeks" value="Length (weeks)"/>
                                <TextInput id="length_in_weeks" v-model="formData.length_in_weeks" name="length_in_weeks" type="number" min="1" class="mt-1 block w-full" required/>
                                <InputError :message="errors.length_in_weeks" class="mt-2"/>
                            </div>

                            <div>
                                <InputLabel for="age_group" value="Target Age Group"/>
                                <select
                                    id="age_group"
                                    v-model="formData.age_group"
                                    name="age_group"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                >
                                    <option value="">All Ages</option>
                                    <option v-for="group in ageGroups" :key="group.value" :value="group.value">
                                        {{ group.label }}
                                    </option>
                                </select>
                                <InputError :message="errors.age_group" class="mt-2"/>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="processing">
                                Create Course
                            </PrimaryButton>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, Form } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    ageGroups: {
        type: Array,
        default: () => [],
    },
});

const formData = ref({
    title: '',
    description: '',
    image_url: '',
    length_in_weeks: 1,
    age_group: '',
});
</script>
