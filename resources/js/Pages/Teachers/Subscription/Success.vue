<template>
    <Head title="Subscription Confirmed" />

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm border border-slate-100 p-8 rounded-lg text-center dark:bg-primary-gray dark:border-neutral-700">
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center dark:bg-green-900/30">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h1 class="mt-4 text-2xl font-semibold text-beach-text dark:text-neutral-200">Welcome to {{ subscription.plan_label }}!</h1>
                <p class="mt-2 text-beach-text-light dark:text-neutral-400">
                    Your subscription is now active. You have access to all {{ subscription.plan_label }} features.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <Link :href="route('parent.subscription.index')" class="inline-flex items-center justify-center px-4 py-2 bg-beach-teal border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-beach-teal-dark transition-colors">
                        View Subscription
                    </Link>
                    <Link :href="route('parent.dashboard')" class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-neutral-700 border border-gray-300 dark:border-neutral-900 rounded-md font-semibold text-xs text-gray-700 dark:text-neutral-400 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition-colors">
                        Go to Dashboard
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({
    layout: AuthenticatedLayout,
});

defineProps({
    subscription: Object,
});
</script>
