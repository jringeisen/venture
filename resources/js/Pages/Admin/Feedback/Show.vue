<template>
    <AdminLayout title="Feedback Details">
        <div class="max-w-3xl">
            <div class="mb-6">
                <Link :href="route('admin.feedback.index')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    &larr; Back to Feedback
                </Link>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ feedback.title }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Submitted by {{ feedback.user?.name || 'Unknown' }}
                            </p>
                        </div>
                        <span :class="statusClass(feedback.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize">
                            {{ feedback.status?.replace('_', ' ') }}
                        </span>
                    </div>

                    <div class="prose dark:prose-invert max-w-none mb-6">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ feedback.description }}</p>
                    </div>

                    <div class="border-t border-gray-200 dark:border-neutral-700 pt-6">
                        <form @submit.prevent="updateStatus" class="flex items-end gap-4">
                            <div class="flex-1">
                                <InputLabel for="status" value="Update Status"/>
                                <select id="status" v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white shadow-sm">
                                    <option v-for="(label, value) in statuses" :key="value" :value="value">{{ label }}</option>
                                </select>
                            </div>
                            <PrimaryButton :disabled="form.processing">
                                Update
                            </PrimaryButton>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    feedback: Object,
    statuses: Object,
});

const form = useForm({
    status: props.feedback.status,
});

const updateStatus = () => {
    form.put(route('admin.feedback.update', props.feedback.id));
};

const statusClass = (status) => {
    const classes = {
        open: 'bg-blue-100 text-blue-800',
        in_progress: 'bg-yellow-100 text-yellow-800',
        on_hold: 'bg-gray-100 text-gray-800',
        resolved: 'bg-green-100 text-green-800',
        reopened: 'bg-red-100 text-red-800',
        cancelled: 'bg-gray-100 text-gray-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};
</script>
