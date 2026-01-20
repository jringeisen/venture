<template>
    <AdminLayout title="Edit Day">
        <div class="max-w-3xl">
            <div class="mb-6">
                <Link :href="route('admin.courses.weeks.edit', [course.id, prompt.id])" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    &larr; Back to Week {{ prompt.week_number }}
                </Link>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
                        Week {{ prompt.week_number }}, Day {{ day.day_number }}: {{ day.title }}
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

                        <div>
                            <div class="flex items-center justify-between">
                                <InputLabel for="content" value="Content"/>
                                <button
                                    type="button"
                                    @click="generateContent"
                                    :disabled="isGenerating || !form.title"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <SparklesIcon class="h-4 w-4 mr-1.5" :class="{ 'animate-pulse': isGenerating }" />
                                    <span v-if="isGenerating">Generating...</span>
                                    <span v-else>Generate Content</span>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Generate educational content and trivia questions using AI based on the title, description, and learning objectives.
                            </p>

                            <!-- Streaming preview -->
                            <div v-if="isGenerating && streamingContent" class="mt-2 p-4 bg-gray-50 dark:bg-neutral-700 rounded-lg border border-gray-200 dark:border-neutral-600">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="animate-spin h-4 w-4 border-2 border-indigo-500 border-t-transparent rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Generating content...</span>
                                </div>
                                <pre class="text-xs text-gray-600 dark:text-gray-400 whitespace-pre-wrap max-h-48 overflow-y-auto font-mono">{{ streamingContent.slice(-1000) }}</pre>
                            </div>

                            <TiptapEditor v-if="!isGenerating" v-model="form.content" class="mt-2" placeholder="Write your day content here..."/>
                            <InputError :message="form.errors.content" class="mt-2"/>
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

                        <!-- Trivia Questions -->
                        <div>
                            <InputLabel value="Trivia Questions"/>
                            <div class="mt-2 space-y-4">
                                <div v-for="(question, qIndex) in form.trivia_questions" :key="qIndex" class="p-4 bg-gray-50 dark:bg-neutral-700 rounded-lg">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Question {{ qIndex + 1 }}</span>
                                        <button type="button" @click="removeQuestion(qIndex)" class="text-red-600 hover:text-red-900">
                                            <XMarkIcon class="h-5 w-5"/>
                                        </button>
                                    </div>
                                    <div class="space-y-3">
                                        <TextInput v-model="question.question" type="text" placeholder="Question" class="block w-full"/>
                                        <div class="grid grid-cols-2 gap-2">
                                            <TextInput v-model="question.option_a" type="text" placeholder="Option A" class="block w-full"/>
                                            <TextInput v-model="question.option_b" type="text" placeholder="Option B" class="block w-full"/>
                                            <TextInput v-model="question.option_c" type="text" placeholder="Option C" class="block w-full"/>
                                            <TextInput v-model="question.option_d" type="text" placeholder="Option D" class="block w-full"/>
                                        </div>
                                        <div>
                                            <label class="text-sm text-gray-600 dark:text-gray-400">Correct Answer</label>
                                            <select v-model="question.correct_answer" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white shadow-sm">
                                                <option :value="0">A</option>
                                                <option :value="1">B</option>
                                                <option :value="2">C</option>
                                                <option :value="3">D</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" @click="addQuestion" class="text-sm text-indigo-600 hover:text-indigo-500">
                                    + Add Question
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="form.processing">
                                Update Day
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import TiptapEditor from '@/Components/TiptapEditor.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { XMarkIcon, SparklesIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    course: Object,
    prompt: Object,
    day: Object,
});

// Sanitize learning objectives to ensure no null/undefined values
const sanitizedObjectives = (props.day.learning_objectives || [])
    .filter(obj => obj != null)
    .map(obj => obj || '');

// Sanitize trivia questions to ensure all properties have values
const sanitizedQuestions = (props.day.trivia_questions || [])
    .filter(q => q != null)
    .map(q => ({
        question: q.question || '',
        option_a: q.option_a || '',
        option_b: q.option_b || '',
        option_c: q.option_c || '',
        option_d: q.option_d || '',
        correct_answer: q.correct_answer ?? 0,
    }));

const form = useForm({
    day_number: props.day.day_number,
    title: props.day.title || '',
    description: props.day.description || '',
    content: props.day.content || '',
    learning_objectives: sanitizedObjectives,
    trivia_questions: sanitizedQuestions,
    estimated_duration_minutes: props.day.estimated_duration_minutes ?? null,
});

const isGenerating = ref(false);
const streamingContent = ref('');

// Get CSRF token from cookie
const getCsrfToken = () => {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : '';
};

const generateContent = async () => {
    if (!form.title) {
        alert('Please enter a title before generating content.');
        return;
    }

    isGenerating.value = true;
    streamingContent.value = '';
    form.content = '';

    try {
        const response = await fetch(
            route('admin.courses.weeks.days.generate-content', [props.course.id, props.prompt.id, props.day.id]),
            {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'text/event-stream',
                    'X-XSRF-TOKEN': getCsrfToken(),
                },
                body: JSON.stringify({
                    title: form.title,
                    description: form.description,
                    learning_objectives: form.learning_objectives.filter(obj => obj && obj.trim()),
                    duration_minutes: form.estimated_duration_minutes || 15,
                }),
            }
        );

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const reader = response.body.getReader();
        const decoder = new TextDecoder();

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;

            const text = decoder.decode(value);
            const lines = text.split('\n');

            for (const line of lines) {
                if (line.startsWith('data: ')) {
                    try {
                        const data = JSON.parse(line.slice(6));

                        if (data.chunk) {
                            streamingContent.value += data.chunk;
                        }

                        if (data.done) {
                            if (data.error) {
                                alert(data.error);
                            } else {
                                form.content = data.content || '';
                                form.trivia_questions = (data.trivia_questions || []).map(q => ({
                                    question: q.question || '',
                                    option_a: q.option_a || '',
                                    option_b: q.option_b || '',
                                    option_c: q.option_c || '',
                                    option_d: q.option_d || '',
                                    correct_answer: q.correct_answer ?? 0,
                                }));
                            }
                        }
                    } catch (e) {
                        // Skip invalid JSON lines
                    }
                }
            }
        }
    } catch (error) {
        console.error('Error generating content:', error);
        alert('Failed to generate content. Please try again.');
    } finally {
        isGenerating.value = false;
        streamingContent.value = '';
    }
};

const addObjective = () => {
    form.learning_objectives.push('');
};

const removeObjective = (index) => {
    form.learning_objectives.splice(index, 1);
};

const addQuestion = () => {
    form.trivia_questions.push({
        question: '',
        option_a: '',
        option_b: '',
        option_c: '',
        option_d: '',
        correct_answer: 0,
    });
};

const removeQuestion = (index) => {
    form.trivia_questions.splice(index, 1);
};

const submit = () => {
    form.put(route('admin.courses.weeks.days.update', [props.course.id, props.prompt.id, props.day.id]));
};
</script>
