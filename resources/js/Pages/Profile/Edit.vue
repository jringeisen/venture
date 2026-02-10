<template>
    <Head title="Profile" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-primary-gray dark:border-neutral-700 shadow-sm dark:shadow-neutral-900/50 border border-slate-100 sm:rounded-lg">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-primary-gray dark:border-neutral-700 shadow-sm dark:shadow-neutral-900/50 border border-slate-100 sm:rounded-lg">
                <TimezoneForm class="max-w-xl" :timezones="timezones" />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-primary-gray dark:border-neutral-700 shadow-sm dark:shadow-neutral-900/50 border border-slate-100 sm:rounded-lg">
                <SchoolYearForm class="max-w-xl" />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-primary-gray dark:border-neutral-700 shadow-sm dark:shadow-neutral-900/50 border border-slate-100 sm:rounded-lg">
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-primary-gray dark:border-neutral-700 shadow-sm dark:shadow-neutral-900/50 border border-slate-100 sm:rounded-lg">
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import TimezoneForm from './Partials/TimezoneForm.vue';
import SchoolYearForm from './Partials/SchoolYearForm.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({layout: AuthenticatedLayout});

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    timezones: {
        type: Array,
    },
});
</script>
