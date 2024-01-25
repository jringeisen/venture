<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="mt-4">
                <InputLabel for="referred_by" value="Referred By" />

                <select v-model="form.referred_by" class="w-full border-gray-300 focus:border-primary-gray focus:ring-primary-gray rounded-md shadow-sm dark:focus:border-neutral-900 dark:focus:ring-neutral-900 dark:border-neutral-900 dark:bg-primary-gray dark:text-neutral-400 dark:placeholder:text-neutral-400">
                    <option value="" disabled>Choose an option:</option>
                    <option value="facebook">Facebook</option>
                    <option value="google">Google</option>
                    <option value="instagram">Instagram</option>
                    <option value="newsletter">Newsletter</option>
                    <option value="pinterest">Pinterest</option>
                    <option value="other">Other</option>
                </select>

                <InputError class="mt-2" :message="form.errors.referred_by" />
            </div>

            <div v-if="form.referred_by === 'other'" class="mt-4">
                <InputLabel for="other" value="Please let us know where you found us." />

                <TextInput
                    id="other"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.other"
                    autocomplete="other"
                />

                <InputError class="mt-2" :message="form.errors.other" />
            </div>

            <div class="mt-4">
                <div class="flex items-center space-x-3">
                    <Checkbox
                        id="terms-of-service"
                        v-model:checked="form.terms_of_service"
                        required
                        autocomplete="terms-of-service"
                    />
                    <label for="password_confirmation" class="text-sm">
                        I agree to the <Link :href="route('terms-of-service')" class="text-dark-gray font-bold">Terms of Service</Link> and <Link :href="route('privacy-policy')" class="text-dark-gray font-bold">Privacy Policy</Link>
                    </label>
                </div>

                <InputError class="mt-2" :message="form.errors.terms_of_service" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('parent.login')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    referred_by: '',
    other: '',
    terms_of_service: false,
});

const submit = () => {
    form
    .transform((data) => ({
        ...data,
        referred_by: data.other ? data.other : data.referred_by,
    }))
    .post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
