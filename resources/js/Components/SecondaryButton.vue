<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-700 border border-gray-300 dark:border-neutral-900 rounded-md font-semibold text-xs text-gray-700 dark:text-neutral-400 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
