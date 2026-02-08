<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-beach-text dark:text-gray-100">School Year Dates</h2>

            <p class="mt-1 text-sm text-beach-text-light dark:text-gray-400">
                Set your school year start and end dates. These are used to calculate year-to-date attendance totals.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <InputLabel for="school_year_start" value="Start Date" />
                    <input
                        id="school_year_start"
                        type="date"
                        v-model="form.school_year_start"
                        class="mt-1 block w-full border-gray-300 focus:border-beach-teal focus:ring-beach-teal rounded-md shadow-sm dark:focus:border-neutral-900 dark:focus:ring-neutral-900 dark:border-neutral-900 dark:bg-primary-gray dark:text-neutral-400"
                    />
                    <InputError class="mt-2" :message="form.errors.school_year_start" />
                </div>

                <div>
                    <InputLabel for="school_year_end" value="End Date" />
                    <input
                        id="school_year_end"
                        type="date"
                        v-model="form.school_year_end"
                        class="mt-1 block w-full border-gray-300 focus:border-beach-teal focus:ring-beach-teal rounded-md shadow-sm dark:focus:border-neutral-900 dark:focus:ring-neutral-900 dark:border-neutral-900 dark:bg-primary-gray dark:text-neutral-400"
                    />
                    <InputError class="mt-2" :message="form.errors.school_year_end" />
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-beach-text-light dark:text-gray-400">Saved.</p>
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

const user = usePage().props.auth.user;

const form = useForm({
    school_year_start: user.school_year_start ? user.school_year_start.substring(0, 10) : '',
    school_year_end: user.school_year_end ? user.school_year_end.substring(0, 10) : '',
});
</script>
