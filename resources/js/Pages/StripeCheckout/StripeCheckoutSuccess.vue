<template>
    <Head title="Checkout Success" />

    <div class="relative isolate px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center lg:max-w-4xl">
            <p class="mt-2 text-4xl font-bold tracking-tight text-primary-gray dark:text-neutral-300 sm:text-5xl">
                Checkout was Successful!
            </p>
        </div>
        <p class="mx-auto mt-6 max-w-2xl text-center text-lg leading-8 text-neutral-500 dark:text-neutral-300">
            You now have full access and can ask an unlimited amount of questions. Enjoy!
        </p>
        <div class="max-w-2xl mx-auto text-center mt-6">
            <Link :href="route('parent.dashboard')" class="inline-flex items-center px-4 py-2 bg-primary-yellow border border-transparent rounded-md font-semibold text-xs text-primary-dark-gray dark:text-gray-800 uppercase tracking-widest hover:bg-yellow-500 dark:hover:bg-yellow-500 focus:bg-yellow-500 dark:focus:bg-yellow-500 active:bg-yellow-500 dark:active:bg-bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-primary-yellow focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition ease-in-out duration-150">Back to Dashboard</Link>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
    layout: AuthenticatedLayout,
});
</script>
