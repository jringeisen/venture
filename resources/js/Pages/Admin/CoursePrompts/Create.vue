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
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="week_number" value="Week Number"/>
                                <TextInput id="week_number" v-model="form.week_number" type="number" min="1" class="mt-1 block w-full" required/>
                                <InputError :message="form.errors.week_number" class="mt-2"/>
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
                            <InputLabel for="content" value="Content"/>
                            <TiptapEditor v-model="form.content" class="mt-1" placeholder="Write your week content here..."/>
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
import TiptapEditor from '@/Components/TiptapEditor.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    course: Object,
    nextWeek: Number,
});

const form = useForm({
    week_number: props.nextWeek,
    title: '',
    description: '',
    content: '',
    learning_objectives: [],
    trivia_questions: [],
    estimated_duration_minutes: null,
});

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
    form.post(route('admin.courses.prompts.store', props.course.id));
};
</script>
