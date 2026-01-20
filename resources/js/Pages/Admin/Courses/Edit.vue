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

                        <div>
                            <InputLabel for="age_group" value="Target Age Group"/>
                            <select
                                id="age_group"
                                v-model="courseForm.age_group"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            >
                                <option value="">All Ages</option>
                                <option v-for="group in ageGroups" :key="group.value" :value="group.value">
                                    {{ group.label }}
                                </option>
                            </select>
                            <InputError :message="courseForm.errors.age_group" class="mt-2"/>
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
                        <div class="flex items-center space-x-3">
                            <button
                                @click="generateWeeks"
                                :disabled="isGenerating"
                                class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg v-if="isGenerating" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg v-else class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                {{ isGenerating ? 'Generating...' : 'Generate Weeks & Days' }}
                            </button>
                            <Link :href="route('admin.courses.weeks.create', course.id)" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Add Week
                            </Link>
                        </div>
                    </div>

                    <!-- Generation Status -->
                    <div v-if="generationStatus" class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex items-center">
                            <svg v-if="isGenerating" class="animate-spin h-4 w-4 text-blue-600 dark:text-blue-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="h-4 w-4 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-blue-700 dark:text-blue-300">{{ generationStatus }}</span>
                        </div>
                    </div>

                    <div v-if="course.course_prompts?.length" class="space-y-3">
                        <div v-for="prompt in course.course_prompts" :key="prompt.id" class="flex items-center justify-between p-4 bg-gray-50 dark:bg-neutral-700 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Week {{ prompt.week_number }}: {{ prompt.title }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ prompt.description?.substring(0, 80) }}{{ prompt.description?.length > 80 ? '...' : '' }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    {{ prompt.days_count || prompt.days?.length || 5 }} days
                                    <span v-if="prompt.days?.length"> ({{ prompt.days.length }} created)</span>
                                </p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <Link :href="route('admin.courses.weeks.edit', [course.id, prompt.id])" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 text-sm">
                                    Edit
                                </Link>
                                <button @click="deletePrompt(prompt)" class="text-red-600 hover:text-red-900 dark:text-red-400 text-sm">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-gray-500 dark:text-gray-400">No weeks added yet. Click "Generate Weeks & Days" to create the course structure.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    course: Object,
    ageGroups: {
        type: Array,
        default: () => [],
    },
});

const courseForm = useForm({
    title: props.course.title,
    description: props.course.description,
    image_url: props.course.image_url || '',
    length_in_weeks: props.course.length_in_weeks,
    age_group: props.course.age_group || '',
});

const isGenerating = ref(false);
const generationStatus = ref('');
const generatedWeeks = ref([]);

const submitCourse = () => {
    courseForm.put(route('admin.courses.update', props.course.id));
};

const deletePrompt = (prompt) => {
    if (confirm('Are you sure you want to delete this week?')) {
        router.delete(route('admin.courses.weeks.destroy', [props.course.id, prompt.id]));
    }
};

// Helper to get CSRF token from cookie
const getCsrfToken = () => {
    const name = 'XSRF-TOKEN=';
    const decodedCookie = decodeURIComponent(document.cookie);
    const cookies = decodedCookie.split(';');
    for (let cookie of cookies) {
        cookie = cookie.trim();
        if (cookie.indexOf(name) === 0) {
            return cookie.substring(name.length);
        }
    }
    return '';
};

const generateWeeks = async () => {
    // Check if course has title and description
    if (!courseForm.title || !courseForm.description) {
        alert('Please enter a course title and description before generating weeks.');
        return;
    }

    // Confirm if weeks already exist
    if (props.course.course_prompts?.length > 0) {
        if (!confirm('This will replace all existing weeks. Are you sure you want to continue?')) {
            return;
        }
    }

    isGenerating.value = true;
    generationStatus.value = 'Starting generation...';
    generatedWeeks.value = [];

    try {
        const response = await fetch(route('admin.courses.generate-weeks', props.course.id), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-XSRF-TOKEN': getCsrfToken(),
                'Accept': 'text/event-stream',
            },
        });

        const reader = response.body.getReader();
        const decoder = new TextDecoder();

        while (true) {
            const { value, done } = await reader.read();
            if (done) break;

            const text = decoder.decode(value);
            const lines = text.split('\n');

            for (const line of lines) {
                if (line.startsWith('data: ')) {
                    try {
                        const data = JSON.parse(line.slice(6));

                        if (data.status === 'generating') {
                            generationStatus.value = data.message;
                        } else if (data.status === 'created') {
                            generatedWeeks.value.push(data.week);
                            generationStatus.value = `Created Week ${data.week.week_number}: ${data.week.title}`;
                        } else if (data.done) {
                            if (data.error) {
                                generationStatus.value = `Error: ${data.error}`;
                                setTimeout(() => {
                                    isGenerating.value = false;
                                    generationStatus.value = '';
                                }, 3000);
                            } else {
                                generationStatus.value = data.message;
                                // Refresh the page to show updated weeks
                                setTimeout(() => {
                                    router.reload();
                                }, 1000);
                            }
                        }
                    } catch (e) {
                        // Ignore JSON parse errors for incomplete chunks
                    }
                }
            }
        }
    } catch (error) {
        generationStatus.value = `Error: ${error.message}`;
        setTimeout(() => {
            isGenerating.value = false;
            generationStatus.value = '';
        }, 3000);
    }
};
</script>
