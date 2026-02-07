<template>
    <AdminLayout title="Edit Course">
        <div class="space-y-6">
            <div class="mb-6">
                <Link :href="route('admin.courses.index')" class="text-sm text-beach-text-light hover:text-beach-text">
                    &larr; Back to Courses
                </Link>
            </div>

            <!-- Course Details -->
            <div class="bg-white shadow-sm border border-slate-100 rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-beach-text mb-6">Course Details</h3>

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
                                class="mt-1 block w-full border-gray-300 focus:border-beach-teal focus:ring-beach-teal rounded-md shadow-sm"
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
            <div class="bg-white shadow-sm border border-slate-100 rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-beach-text">Course Weeks</h3>
                        <div class="flex items-center gap-3">
                            <button
                                @click="generateWeeks"
                                :disabled="isBusy"
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
                            <Link
                                :href="route('admin.courses.weeks.create', course.id)"
                                class="inline-flex items-center justify-center rounded-md bg-beach-teal px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-beach-teal-dark"
                                :class="{ 'opacity-50 pointer-events-none': isBusy }"
                                :tabindex="isBusy ? -1 : 0"
                            >
                                Add Week
                            </Link>
                        </div>
                    </div>

                    <!-- Generation Status -->
                    <div v-if="generationStatus" class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center">
                            <svg v-if="isGenerating" class="animate-spin h-4 w-4 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="h-4 w-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-blue-700">{{ generationStatus }}</span>
                        </div>
                    </div>

                    <!-- Content Generation Progress -->
                    <div v-if="isGeneratingContent || contentProgress.total > 0" class="mb-4 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-indigo-700">Content Generation Progress</span>
                            <span class="text-sm text-indigo-600">
                                {{ contentProgress.completed }} / {{ contentProgress.total }} days
                            </span>
                        </div>
                        <div class="w-full bg-indigo-200 rounded-full h-2.5">
                            <div
                                class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300"
                                :style="{ width: progressPercentage + '%' }"
                            ></div>
                        </div>
                        <div class="flex items-center gap-4 mt-2 text-xs text-indigo-600">
                            <span v-if="contentProgress.generating > 0" class="flex items-center gap-1">
                                <span class="inline-block w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
                                {{ contentProgress.generating }} generating
                            </span>
                            <span v-if="contentProgress.completed > 0" class="flex items-center gap-1">
                                <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                                {{ contentProgress.completed }} completed
                            </span>
                            <span v-if="contentProgress.failed > 0" class="flex items-center gap-1">
                                <span class="inline-block w-2 h-2 rounded-full bg-red-500"></span>
                                {{ contentProgress.failed }} failed
                            </span>
                            <span v-if="contentProgress.pending > 0" class="flex items-center gap-1">
                                <span class="inline-block w-2 h-2 rounded-full bg-gray-400"></span>
                                {{ contentProgress.pending }} pending
                            </span>
                        </div>
                    </div>

                    <!-- Generate All Content Button -->
                    <div v-if="hasWeeks && !isGeneratingContent" class="mb-4">
                        <button
                            @click="generateAllContent"
                            :disabled="isBusy"
                            class="inline-flex items-center justify-center rounded-md bg-purple-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            Generate All Content
                        </button>
                        <p class="text-xs text-gray-500 mt-1">Queues AI content generation for all days across all weeks.</p>
                    </div>

                    <div v-if="course.course_prompts?.length" class="space-y-3">
                        <div v-for="prompt in course.course_prompts" :key="prompt.id" class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="flex items-center justify-between p-4 bg-gray-50">
                                <div>
                                    <p class="font-medium text-beach-text">Week {{ prompt.week_number }}: {{ prompt.title }}</p>
                                    <p class="text-sm text-beach-text-light">{{ prompt.description?.substring(0, 80) }}{{ prompt.description?.length > 80 ? '...' : '' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ prompt.days_count || prompt.days?.length || 5 }} days
                                        <span v-if="prompt.days?.length"> ({{ prompt.days.length }} created)</span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <Link :href="route('admin.courses.weeks.edit', [course.id, prompt.id])" class="text-beach-teal hover:text-beach-teal-dark text-sm">
                                        Edit
                                    </Link>
                                    <button @click="deletePrompt(prompt)" class="text-red-600 hover:text-red-900 text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>

                            <!-- Days with content status -->
                            <div v-if="prompt.days?.length" class="border-t border-gray-200 px-4 py-2 bg-white">
                                <div class="flex flex-wrap gap-2">
                                    <div
                                        v-for="day in prompt.days"
                                        :key="day.id"
                                        class="flex items-center gap-1.5 text-xs px-2 py-1 rounded-full"
                                        :class="dayStatusClass(day)"
                                    >
                                        <span v-if="getDayStatus(day) === 'generating'" class="inline-block w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                                        <svg v-else-if="getDayStatus(day) === 'completed'" class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <svg v-else-if="getDayStatus(day) === 'failed'" class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span v-else class="inline-block w-2 h-2 rounded-full bg-gray-400"></span>
                                        Day {{ day.day_number }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-beach-text-light">No weeks added yet. Click "Generate Weeks & Days" to create the course structure.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
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
const isGeneratingContent = ref(false);
const dayStatuses = ref({});

const hasWeeks = computed(() => props.course.course_prompts?.length > 0);
const isBusy = computed(() => isGenerating.value || isGeneratingContent.value);

const contentProgress = computed(() => {
    const statuses = Object.values(dayStatuses.value);
    if (statuses.length === 0) {
        return { total: 0, pending: 0, generating: 0, completed: 0, failed: 0 };
    }

    return {
        total: statuses.length,
        pending: statuses.filter(s => s === 'pending').length,
        generating: statuses.filter(s => s === 'generating').length,
        completed: statuses.filter(s => s === 'completed').length,
        failed: statuses.filter(s => s === 'failed').length,
    };
});

const progressPercentage = computed(() => {
    if (contentProgress.value.total === 0) {
        return 0;
    }

    return Math.round((contentProgress.value.completed / contentProgress.value.total) * 100);
});

const getDayStatus = (day) => {
    return dayStatuses.value[day.id] || day.content_status || 'pending';
};

const dayStatusClass = (day) => {
    const status = getDayStatus(day);
    switch (status) {
        case 'generating':
            return 'bg-yellow-100 text-yellow-700';
        case 'completed':
            return 'bg-green-100 text-green-700';
        case 'failed':
            return 'bg-red-100 text-red-700';
        default:
            return 'bg-gray-100 text-gray-600';
    }
};

// Initialize day statuses from props
const initDayStatuses = () => {
    const statuses = {};
    props.course.course_prompts?.forEach(week => {
        week.days?.forEach(day => {
            statuses[day.id] = day.content_status || 'pending';
        });
    });
    dayStatuses.value = statuses;

    // Check if generation is in progress
    const values = Object.values(statuses);
    if (values.some(s => s === 'generating' || s === 'pending') && values.some(s => s === 'completed' || s === 'generating')) {
        isGeneratingContent.value = true;
    }
};

const submitCourse = () => {
    courseForm.put(route('admin.courses.update', props.course.id));
};

const deletePrompt = (prompt) => {
    if (confirm('Are you sure you want to delete this week?')) {
        router.delete(route('admin.courses.weeks.destroy', [props.course.id, prompt.id]));
    }
};

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
    if (!courseForm.title || !courseForm.description) {
        alert('Please enter a course title and description before generating weeks.');
        return;
    }

    if (props.course.course_prompts?.length > 0) {
        if (!confirm('This will replace all existing weeks. Are you sure you want to continue?')) {
            return;
        }
    }

    isGenerating.value = true;
    generationStatus.value = 'Generating weeks & days... This runs in the background.';

    try {
        const response = await fetch(route('admin.courses.generate-weeks', props.course.id), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-XSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            generationStatus.value = `Error: ${data.error || 'Failed to queue week generation'}`;
            setTimeout(() => {
                isGenerating.value = false;
                generationStatus.value = '';
            }, 3000);
            return;
        }

        generationStatus.value = 'Week generation queued. Waiting for completion...';
        // The Echo listener will handle the reload when the job finishes
    } catch (error) {
        generationStatus.value = `Error: ${error.message}`;
        setTimeout(() => {
            isGenerating.value = false;
            generationStatus.value = '';
        }, 3000);
    }
};

const generateAllContent = async () => {
    if (!confirm('This will queue AI content generation for all days. This may take several minutes. Continue?')) {
        return;
    }

    isGeneratingContent.value = true;

    // Mark all days as pending in the UI
    const statuses = {};
    props.course.course_prompts?.forEach(week => {
        week.days?.forEach(day => {
            statuses[day.id] = 'pending';
        });
    });
    dayStatuses.value = statuses;

    try {
        const response = await fetch(route('admin.courses.generate-all-content', props.course.id), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-XSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            isGeneratingContent.value = false;
            alert(data.error || 'Failed to queue content generation');
        }
    } catch (error) {
        isGeneratingContent.value = false;
        alert(`Error: ${error.message}`);
    }
};

// Echo listener for real-time updates
let echoChannel = null;

const setupEchoListener = () => {
    if (!window.Echo) {
        return;
    }

    echoChannel = window.Echo.private(`courses.${props.course.id}`);

    echoChannel.listen('CourseWeeksGenerated', (event) => {
        isGenerating.value = false;

        if (event.status === 'completed') {
            generationStatus.value = event.message;
            setTimeout(() => {
                router.reload();
            }, 500);
        } else {
            generationStatus.value = `Error: ${event.message}`;
            setTimeout(() => {
                generationStatus.value = '';
            }, 5000);
        }
    });

    echoChannel.listen('DayContentGenerated', (event) => {
        dayStatuses.value[event.day_id] = event.status;

        // Check if all done
        const statuses = Object.values(dayStatuses.value);
        const allDone = statuses.every(s => s === 'completed' || s === 'failed');
        if (allDone && isGeneratingContent.value) {
            isGeneratingContent.value = false;
        }
    });
};

const teardownEchoListener = () => {
    if (echoChannel) {
        echoChannel.stopListening('CourseWeeksGenerated');
        echoChannel.stopListening('DayContentGenerated');
        window.Echo?.leave(`courses.${props.course.id}`);
        echoChannel = null;
    }
};

onMounted(() => {
    initDayStatuses();
    setupEchoListener();
});

onUnmounted(() => {
    teardownEchoListener();
});
</script>
