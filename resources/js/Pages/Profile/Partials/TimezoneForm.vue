<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Timezone Information</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your timezone so that we can show you the data correctly based on your timezone.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="timezone" value="Timezone" />
                <select id="timezone" v-model="form.timezone" class="border-gray-300 focus:border-primary-gray focus:ring-primary-gray rounded-md shadow-sm dark:focus:border-neutral-900 dark:focus:ring-neutral-900 dark:border-neutral-900 dark:bg-primary-gray dark:text-neutral-400 dark:placeholder:text-neutral-400">
                    <option disabled>Choose a timezone:</option>
                    <option v-for="(timezone, index) in timezones" selected :value="timezone.value">{{ timezone.label }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.timezone" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';

defineProps({
    timezones: Array,
});

const user = usePage().props.auth.user;

const form = useForm({
    timezone: user.timezone,
});
</script>
