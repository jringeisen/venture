<template>
    <div class="bg-primary-yellow rounded-lg text-center p-4">
        <p class="text-yellow-900">
            You are currently on the free plan. <Link :href="route('subscription.checkout.options')" class="font-bold underline">Upgrade</Link> to the premium plan to unlock all features.
        </p>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
