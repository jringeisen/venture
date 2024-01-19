<template>
    <Head title="Students" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-8 rounded-lg dark:bg-primary-gray">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-400">Students</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-neutral-400">
                            A list of all the students in your account including their name, title, email and role.
                        </p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <PrimaryButton @click.prevent="handleAddStudent()">Add student</PrimaryButton
                        >
                    </div>
                </div>
                <div v-if="students.total > 0" class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300 dark:divide-neutral-700">
                                    <thead class="bg-gray-50 dark:bg-neutral-600">
                                        <tr>
                                            <th></th>
                                            <th
                                                scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-neutral-300"
                                            >
                                                Name
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-300"
                                            >
                                                Username
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-300"
                                            >
                                                Questions Asked
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-300"
                                            >
                                                Age
                                            </th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white dark:bg-neutral-500 dark:divide-neutral-600">
                                        <tr v-for="(student, index) in students.data" :key="index">
                                            <td>
                                                <Link :href="route('student.login', {username: student.username})" class="ml-3 inline-flex items-center px-4 py-2 bg-primary-yellow border border-transparent rounded-md font-semibold text-xs text-primary-dark-gray dark:text-gray-800 uppercase tracking-widest hover:bg-yellow-500 dark:hover:bg-yellow-500 focus:bg-yellow-500 dark:focus:bg-yellow-500 active:bg-yellow-500 dark:active:bg-bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-primary-yellow focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition ease-in-out duration-150">Login</Link>
                                            </td>
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 dark:text-primary-gray"
                                            >
                                                {{ student.name }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-primary-gray">
                                                {{ student.username }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-primary-gray">
                                                {{ student.prompt_questions_count }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-primary-gray">
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
                                                        class="absolute bg-white shadow-lg text-left border rounded-lg right-4 -top-6 overflow-hidden"
                                                    >
                                                        <Link
                                                            :href="route('parent.users.show', student.id)"
                                                            class="cursor-pointer px-4 py-1 text-gray-600 hover:bg-gray-100"
                                                            as="li"
                                                        >
                                                            View
                                                        </Link>
                                                        <Link
                                                            :href="route('parent.users.edit', student.id)"
                                                            class="cursor-pointer px-4 py-1 text-gray-600 hover:bg-gray-100"
                                                            as="li">
                                                            Edit
                                                        </Link>
                                                        <li
                                                            @click.prevent="deleteStudent(student)"
                                                            class="cursor-pointer px-4 py-1 text-gray-600 hover:bg-gray-100"
                                                        >
                                                            Delete
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div v-if="students.total > students.per_page" class="border-t">
                                    <Pagination :data="students" class="dark:bg-neutral-600" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { EllipsisVerticalIcon } from '@heroicons/vue/24/outline';
import { Head, router, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    students: Object,
    showInitialPaymentPage: Boolean,
    showExceededQuantityPage: Boolean,
});

const form = useForm({});

const toggleAction = ref(null);

const deleteStudent = (student) => {
    if (confirm('Are you sure you want to delete this student?')) {
        form.delete(route('parent.users.destroy', student.id));
    }
};

const handleAddStudent = () => {
    if (props.showInitialPaymentPage) {
        router.get(route('subscription.checkout.options'));
    } else if (props.showExceededQuantityPage) {
        router.get(route('quantity.exceeded'));
    } else {
        router.get(route('parent.users.create'));
    }
};
</script>
