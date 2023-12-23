<template>
    <nav class="flex items-center justify-between bg-white px-4 py-3 sm:px-8" aria-label="Pagination">
        <div class="hidden sm:block">
            <p class="text-sm text-primary-dark-gray dark:text-neutral-400">
                Showing
            <span class="font-medium">{{ data.from }}</span>
                to
            <span class="font-medium">{{ data.to }}</span>
                of
            <span class="font-medium">{{ data.total }}</span>
                results
            </p>
        </div>
        <div class="flex flex-1 justify-between sm:justify-end">
            <Link v-if="data.prev_page_url" :href="data.prev_page_url" preserve-scroll class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 dark:bg-primary-yellow dark:ring-0 dark:border-none">Previous</Link>
            <button v-else disabled class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-primary-yellow dark:ring-0 dark:border-none">Previous</button>

            <Link v-if="data.next_page_url" :href="data.next_page_url" preserve-scroll class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 dark:bg-primary-yellow dark:ring-0 dark:border-none">Next</Link>
            <button v-else disabled class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-primary-yellow dark:ring-0 dark:border-none">Next</button>
        </div>
    </nav>
</template>

<script setup>
    import { Link } from '@inertiajs/vue3';

    defineProps({
        data: Object,
    })
</script>
