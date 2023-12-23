<template>
    <Head title="Create Student" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-8 rounded-lg dark:bg-neutral-800">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-400">Create Student</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-neutral-400">Create a new student in your account.</p>
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
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="email" value="Email" />
                                    <div class="mt-2">
                                        <TextInput v-model="form.email" id="email" type="text" autocomplete="email" class="w-full" required />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="grade" value="Grade" />
                                    <div class="mt-2">
                                        <TextInput v-model="form.grade" id="grade" type="text" autocomplete="grade" class="w-full" required />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="age" value="Age" />
                                    <div class="mt-2">
                                        <TextInput v-model="form.age" id="age" type="text" autocomplete="age" class="w-full" required />
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <SecondaryButton @click.prevent="router.get(route('students.index'))">Cancel</SecondaryButton>
                                    <PrimaryButton type="submit">Submit</PrimaryButton>
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

defineOptions({
    layout: AuthenticatedLayout
});

const form = useForm({
    name: '',
    email: '',
    grade: '',
    age: '',
});

const submit = () => {
    form.post(route('students.store'));
}
</script>
