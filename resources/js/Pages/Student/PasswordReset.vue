<template>
    <Head title="Password Reset" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
                <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                    <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
                        Welcome to Venture!
                    </h2>
                    <p class="text-center">To get started we need you to reset your password to something unique and that you can remember.</p>
                </div>

                <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                    <form class="space-y-6" @submit.prevent="submit">
                        <div>
                            <InputLabel for="password" value="Password" />
                            <div class="mt-2">
                                <TextInput
                                    id="password"
                                    class="w-full"
                                    v-model="form.password"
                                    type="password"
                                    autocomplete="password"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.password"
                                />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="password_confirmation" value="Confirm Password" />
                            <div class="mt-2">
                                <TextInput
                                    id="password_confirmation"
                                    class="w-full"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    autocomplete="password_confirmation"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.password_confirmation"
                                />
                            </div>
                        </div>

                        <div>
                            <PrimaryButton
                                type="submit"
                                class="w-full flex justify-center"
                            >
                                Reset Password
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const form = useForm({
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('student.password-reset.store'));
};
</script>
