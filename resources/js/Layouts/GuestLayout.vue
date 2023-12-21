<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div class="min-h-screen bg-primary-dark-gray flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <a href="/" class="flex items-center">
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
                <p class="text-white text-5xl">VENTURE</p>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-lg overflow-hidden sm:rounded-lg dark:bg-neutral-700">
            <slot />
        </div>
    </div>
</template>
