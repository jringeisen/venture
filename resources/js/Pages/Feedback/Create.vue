<template>
    <Head title="Create Feedback" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-8 rounded-lg dark:bg-neutral-800">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-400">
                            Create Feedback
                        </h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-neutral-400">
                            Providing feedback allows us to continue to improve our services.
                        </p>
                    </div>
                </div>
                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <form class="space-y-6" @submit.prevent="submit()">
                                <div>
                                    <InputLabel for="title" value="Title" />
                                    <div class="mt-2">
                                        <TextInput
                                            v-model="form.title"
                                            id="title"
                                            type="text"
                                            autocomplete="title"
                                            class="w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.title" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="description" value="Description" />
                                    <div class="mt-2">
                                        <TextareaInput
                                            v-model="form.description"
                                            id="description"
                                            type="text"
                                            autocomplete="description"
                                            class="w-full"
                                            required
                                        />
                                        <InputError :message="form.errors.description" />
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <SecondaryButton @click.prevent="router.get(route('feedback.index'))"
                                        >Cancel</SecondaryButton
                                    >
                                    <PrimaryButton
                                        type="submit"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing"
                                        >Submit</PrimaryButton
                                    >
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import TextareaInput from '@/Components/TextareaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

defineOptions({
    layout: AuthenticatedLayout,
});

const form = useForm({
    title: '',
    description: '',
});

const submit = () => {
    form.post(route('feedback.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
