<template>
    <AdminLayout title="Courses">
        <div class="space-y-6">
            <!-- Flash Messages -->
            <div v-if="$page.props.flash.success" class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ $page.props.flash.success }}</p>
                    </div>
                </div>
            </div>

            <div v-if="$page.props.flash.error" class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ $page.props.flash.error }}</p>
                    </div>
                </div>
            </div>

            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-beach-text dark:text-neutral-200">Courses</h2>
                    <p class="mt-1 text-sm text-beach-text-light dark:text-neutral-400">Manage your educational courses.</p>
                </div>
                <div class="mt-4 flex gap-3 sm:mt-0">
                    <button
                        @click="showImportModal = true"
                        class="inline-flex items-center justify-center rounded-md border border-beach-teal px-3 py-2 text-sm font-semibold text-beach-teal shadow-sm hover:bg-beach-teal/5"
                    >
                        Import Courses
                    </button>
                    <Link :href="route('admin.courses.create')" class="inline-flex items-center justify-center rounded-md bg-beach-teal px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-beach-teal-dark">
                        Add Course
                    </Link>
                </div>
            </div>

            <div class="bg-white dark:bg-primary-gray shadow-sm dark:shadow-neutral-900/50 border border-slate-100 dark:border-neutral-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-primary-dark-gray">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-beach-text-light dark:text-neutral-400 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-beach-text-light dark:text-neutral-400 uppercase tracking-wider">Weeks</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-beach-text-light dark:text-neutral-400 uppercase tracking-wider">Enrolled</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-primary-gray divide-y divide-gray-200 dark:divide-neutral-700">
                        <tr v-for="course in courses.data" :key="course.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-beach-text dark:text-neutral-200">{{ course.title }}</div>
                                <div class="text-sm text-beach-text-light dark:text-neutral-400">{{ course.description?.substring(0, 50) }}...</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-beach-text-light dark:text-neutral-400">
                                {{ course.course_prompts_count }} weeks
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-beach-text-light dark:text-neutral-400">
                                {{ course.enrolled_users_count }} users
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <Link :href="route('admin.courses.edit', course.id)" class="text-beach-teal hover:text-beach-teal-dark">Edit</Link>
                                <button @click="deleteCourse(course)" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="courses.data.length === 0">
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-beach-text-light dark:text-neutral-400">
                                No courses yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :data="courses"/>
        </div>

        <!-- Import Modal -->
        <Modal :show="showImportModal" @close="closeImportModal" max-width="lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-beach-text dark:text-neutral-200">Import Courses</h3>
                <p class="mt-1 text-sm text-beach-text-light dark:text-neutral-400">
                    Upload a CSV or XLSX file to bulk import courses with their week and day structure.
                </p>

                <div class="mt-4">
                    <a
                        :href="route('admin.courses.import-template')"
                        class="text-sm text-beach-teal hover:text-beach-teal-dark underline"
                    >
                        Download CSV template
                    </a>
                </div>

                <form @submit.prevent="submitImport" class="mt-4 space-y-4">
                    <div>
                        <label for="import-file" class="block text-sm font-medium text-beach-text dark:text-neutral-200">File</label>
                        <input
                            id="import-file"
                            type="file"
                            accept=".csv,.xlsx"
                            @change="handleFileChange"
                            class="mt-1 block w-full text-sm text-beach-text-light file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-beach-teal/10 file:text-beach-teal hover:file:bg-beach-teal/20"
                        />
                        <p v-if="importForm.errors.file" class="mt-1 text-sm text-red-600">{{ importForm.errors.file }}</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="closeImportModal"
                            class="rounded-md border border-gray-300 dark:border-neutral-700 px-3 py-2 text-sm font-semibold text-beach-text dark:text-neutral-200 shadow-sm hover:bg-gray-50 dark:hover:bg-neutral-800"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="importForm.processing || !importForm.file"
                            class="rounded-md bg-beach-teal px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-beach-teal-dark disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ importForm.processing ? 'Importing...' : 'Import' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    courses: Object,
});

const showImportModal = ref(false);

const importForm = useForm({
    file: null,
}, { forceFormData: true });

const handleFileChange = (e) => {
    importForm.file = e.target.files[0] || null;
};

const submitImport = () => {
    importForm.post(route('admin.courses.import'), {
        onSuccess: () => closeImportModal(),
        forceFormData: true,
    });
};

const closeImportModal = () => {
    showImportModal.value = false;
    importForm.reset();
    importForm.clearErrors();
};

const deleteCourse = (course) => {
    if (confirm('Are you sure you want to delete this course?')) {
        router.delete(route('admin.courses.destroy', course.id));
    }
};
</script>
