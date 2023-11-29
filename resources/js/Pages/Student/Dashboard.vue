<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="pl-4 py-2 bg-gray-200 font-bold">
                            Topics
                        </div>
                        <ul class="p-4">
                            <li v-for="(value, index) in promptsWithCounts" :key="index" class="flex justify-between items-center">
                                <Link :href="route('student.topic.show', index)" class="underline text-blue-500">{{ startCase(index) }}</Link>
                                <p class="font-bold">{{ value }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { startCase } from 'lodash';

defineProps({
    promptsWithCounts: {
        type: Object,
        required: true,
    },
});
</script>
