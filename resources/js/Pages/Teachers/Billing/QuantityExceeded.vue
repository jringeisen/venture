<template>
    <div class="max-w-2xl mx-auto bg-primary-gray rounded-lg shadow-lg p-4 text-neutral-300">
        <p>
            Hey there friend! It looks like you've reached your max number of students that you are subscribed to.
            In order to add another student you will need to visit the billing portal and update your quantity.
        </p>

        <div class="flex justify-end">
            <a :href="route('billing.portal')" class="inline-flex items-center px-4 py-2 bg-primary-yellow border border-transparent rounded-md font-semibold text-xs text-primary-dark-gray dark:text-gray-800 uppercase tracking-widest hover:bg-yellow-500 dark:hover:bg-yellow-500 focus:bg-yellow-500 dark:focus:bg-yellow-500 active:bg-yellow-500 dark:active:bg-bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-primary-yellow focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition ease-in-out duration-150">Visit Billing Portal</a>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
    layout: AuthenticatedLayout
});
</script>
