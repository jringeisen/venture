<template>
    <div class="min-h-screen bg-slate-50 dark:bg-primary-dark-gray flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <Link :href="route('landing')" class="flex items-center">
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
                <p class="text-beach-ocean-deep dark:text-beach-teal-light text-5xl">VENTURE</p>
            </Link>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-primary-gray shadow-lg dark:shadow-neutral-900/50 overflow-hidden sm:rounded-lg border border-slate-100 dark:border-neutral-700">
            <slot />
        </div>
    </div>
</template>

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>
