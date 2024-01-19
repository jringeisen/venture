<template>
    <Head title="Create Student" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-8 rounded-lg dark:bg-neutral-800">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-400">Create Student</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-neutral-400">
                            Create a new student.
                        </p>
                    </div>
                </div>
                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <form class="space-y-6" @submit.prevent="submit()">
                                <div>
                                    <InputLabel for="name" value="Name" />
                                    <div class="mt-2">
                                        <TextInput v-model="form.name" id="name" type="text" autocomplete="name" class="w-full" required />
                                        <InputError :message="form.errors.name" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="username" value="Username" />
                                    <div class="mt-2">
                                        <TextInput v-model="form.username" id="username" type="text" autocomplete="username" class="w-full" required />
                                        <InputError :message="form.errors.username" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="password" value="Password" />
                                    <div class="mt-2">
                                        <TextInput v-model="form.password" id="password" type="password" autocomplete="password" class="w-full" required />
                                        <InputError :message="form.errors.password" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="password_confirmation" value="Confirm Password" />
                                    <div class="mt-2">
                                        <TextInput v-model="form.password_confirmation" id="password_confirmation" type="password" autocomplete="password_confirmation" class="w-full" required />
                                        <InputError :message="form.errors.password_confirmation" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="grade" value="Grade" />
                                    <div class="mt-2">
                                        <TextInput v-model="form.grade" id="grade" type="text" autocomplete="grade" class="w-full" required />
                                        <InputError :message="form.errors.grade" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="age" value="Age" />
                                    <div class="mt-2">
                                        <TextInput v-model="form.age" id="age" type="text" autocomplete="age" class="w-full" required />
                                        <InputError :message="form.errors.age" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="timezone" value="Timezone" />
                                    <select id="timezone" v-model="form.timezone" class="w-full border-gray-300 focus:border-primary-gray focus:ring-primary-gray rounded-md shadow-sm dark:focus:border-neutral-900 dark:focus:ring-neutral-900 dark:border-neutral-900 dark:bg-primary-gray dark:text-neutral-400 dark:placeholder:text-neutral-400">
                                        <option disabled>Choose a timezone:</option>
                                        <option v-for="(timezone, index) in timezones" selected :value="timezone.value">{{ timezone.label }}</option>
                                    </select>
                                    <InputError :message="form.errors.timezone" />
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <SecondaryButton @click.prevent="router.get(route('students.index'))">Cancel</SecondaryButton>
                                    <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Submit</PrimaryButton>
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
import { Head, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

defineOptions({
    layout: AuthenticatedLayout
});

defineProps({
    timezones: Array,
});

const form = useForm({
    name: '',
    username: '',
    password: '',
    password_confirmation: '',
    grade: '',
    age: '',
    timezone: '',
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        grade: parseInt(data.grade),
        age: parseInt(data.age),
    })).post(route('parent.users.store'));
}
</script>
