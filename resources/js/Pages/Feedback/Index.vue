<template>
    <Head title="Feedback" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-8 rounded-lg dark:bg-primary-gray">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-400">Feedback</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-neutral-400">
                            A list of all the feedback you've created.
                        </p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <PrimaryButton @click.prevent="handleAddFeedback()">Add Feedback</PrimaryButton>
                    </div>
                </div>
                <div v-if="feedback.total > 0" class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300 dark:divide-neutral-700">
                                    <thead class="bg-gray-50 dark:bg-neutral-600">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-neutral-300"
                                            >
                                                Title
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-300"
                                            >
                                                Status
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-300"
                                            >
                                                Created On
                                            </th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white dark:bg-neutral-500 dark:divide-neutral-600">
                                        <tr v-for="(item, index) in feedback.data" :key="index">
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 dark:text-primary-gray"
                                            >
                                                {{ item.title }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-primary-gray">
                                                {{ item.status }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-primary-gray">
                                                {{ item.created_at }}
                                            </td>
                                            <td
                                                class="flex justify-end items-baseline space-x-2 whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                            >
                                                <div
                                                    v-if="toggleAction === item.id"
                                                    @click.away="toggleAction = null"
                                                    class="fixed inset-0 z-10"
                                                ></div>
                                                <div class="absolute z-20">
                                                    <EllipsisVerticalIcon
                                                        @click.prevent="toggleAction = item.id"
                                                        class="h-5 w-5 cursor-pointer"
                                                    />

                                                    <ul
                                                        v-if="toggleAction === item.id"
                                                        class="absolute bg-white shadow-lg text-left border rounded-lg right-4 -top-6 overflow-hidden"
                                                    >
                                                        <Link
                                                            :href="route('feedback.show', item.id)"
                                                            class="cursor-pointer px-4 py-1 text-gray-600 hover:bg-gray-100"
                                                            as="li"
                                                        >
                                                            View
                                                        </Link>
                                                        <Link
                                                            :href="route('feedback.edit', item.id)"
                                                            class="cursor-pointer px-4 py-1 text-gray-600 hover:bg-gray-100"
                                                            as="li">
                                                            Edit
                                                        </Link>
                                                        <li
                                                            @click.prevent="deleteFeedback(item)"
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
                                <div v-if="feedback.total > feedback.per_page" class="border-t">
                                    <Pagination :data="feedback" class="dark:bg-neutral-600" />
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
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    feedback: Object,
});

const form = useForm({});
const toggleAction = ref(null);

const deleteFeedback = (feedback) => {
    if (confirm('Are you sure you want to delete this feedback?')) {
        form.delete(route('feedback.destroy', feedback.id));
    }
};

const handleAddFeedback = () => {
    router.get(route('feedback.create'));
};
</script>
