<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-neutral-200 hover:bg-gray-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-neutral-800 transition-colors duration-150 ease-in-out"
    >
        <slot />
    </Link>
</template>
