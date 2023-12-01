<template>
    <Head title="Students" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow p-8 rounded-lg">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-base font-semibold leading-6 text-gray-900">Students</h1>
                            <p class="mt-2 text-sm text-gray-700">
                                A list of all the students in your account including their name, title, email and role.
                            </p>
                        </div>
                        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                            <PrimaryButton @click.prevent="router.get(route('students.create'))"
                                >Add student</PrimaryButton
                            >
                        </div>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                                >
                                                    Name
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Email
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Questions Asked
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Age
                                                </th>
                                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                    <span class="sr-only">Edit</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            <tr v-for="(student, index) in students.data" :key="index">
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                                >
                                                    {{ student.name }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ student.email }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ student.prompt_questions_count }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ student.age }}
                                                </td>
                                                <td
                                                    class="flex justify-end items-baseline space-x-2 whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                                >
                                                    <div
                                                        v-if="toggleAction === student.id"
                                                        @click.away="toggleAction = null"
                                                        class="fixed inset-0 z-10"
                                                    ></div>
                                                    <div class="absolute z-20">
                                                        <EllipsisVerticalIcon
                                                            @click.prevent="toggleAction = student.id"
                                                            class="h-5 w-5 cursor-pointer"
                                                        />

                                                        <ul
                                                            v-if="toggleAction === student.id"
                                                            class="absolute bg-white shadow-lg py-1 text-left border rounded-lg right-4 -top-6"
                                                        >
                                                            <li>
                                                                <Link :href="route('students.show', student.id)"
                                                                    class="cursor-pointer px-2 text-gray-600 hover:bg-gray-100"
                                                                >
                                                                    View
                                                                </Link>
                                                            </li>
                                                            <li
                                                                class="cursor-pointer px-2 text-gray-600 hover:bg-gray-100"
                                                            >
                                                                Edit
                                                            </li>
                                                            <li
                                                                @click.prevent="deleteStudent(student)"
                                                                class="cursor-pointer px-2 text-gray-600 hover:bg-gray-100"
                                                            >
                                                                Delete
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <nav
                                        class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6"
                                        aria-label="Pagination"
                                    >
                                        <div class="hidden sm:block">
                                            <p class="text-sm text-gray-700">
                                                Showing
                                                <span class="font-medium">{{ students.from }}</span>
                                                to
                                                <span class="font-medium">{{ students.to }}</span>
                                                of
                                                <span class="font-medium">{{ students.total }}</span>
                                                results
                                            </p>
                                        </div>
                                        <div class="flex flex-1 justify-between sm:justify-end">
                                            <Link
                                                v-if="students.prev_page_url"
                                                :href="students.prev_page_url"
                                                class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0"
                                            >
                                                Previous
                                            </Link>
                                            <Link
                                                v-if="students.next_page_url"
                                                :href="students.next_page_url"
                                                class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0"
                                            >
                                                Next
                                            </Link>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { EllipsisVerticalIcon } from '@heroicons/vue/24/outline';
import { Head, router, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    students: {
        type: Object,
        required: true,
    },
});

const form = useForm({});

const toggleAction = ref(null);

const deleteStudent = (student) => {
    if (confirm('Are you sure you want to delete this student?')) {
        form.delete(route('students.destroy', student.id));
    }
};
</script>
