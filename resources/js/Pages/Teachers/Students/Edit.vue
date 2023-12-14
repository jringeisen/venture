<template>
    <Head title="Edit Student" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-8 rounded-lg">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900">Edit {{ student.name }}</h1>
                        <p class="mt-2 text-sm text-gray-700">Update this students details.</p>
                    </div>
                </div>
                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <form class="space-y-6" @submit.prevent="submit()">
                                <div>
                                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                                    <div class="mt-2">
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            autocomplete="name"
                                            required
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                                    <div class="mt-2">
                                        <input
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            autocomplete="email"
                                            required
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label for="grade" class="block text-sm font-medium leading-6 text-gray-900">Grade</label>
                                    <div class="mt-2">
                                        <input
                                            id="grade"
                                            v-model="form.grade"
                                            type="number"
                                            autocomplete="grade"
                                            required
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label for="age" class="block text-sm font-medium leading-6 text-gray-900">Age</label>
                                    <div class="mt-2">
                                        <input
                                            id="age"
                                            v-model="form.age"
                                            type="number"
                                            autocomplete="age"
                                            required
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label for="timezone" class="block text-sm font-medium leading-6 text-gray-900">Timezone</label>
                                    <select id="timezone" v-model="form.timezone" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled>Choose a timezone:</option>
                                        <option v-for="(timezone, index) in timezones" selected :value="timezone.value">{{ timezone.label }}</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.timezone" />
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

defineOptions({layout: AuthenticatedLayout});

const props = defineProps({
    student: Object,
    timezones: Array,
});

const form = useForm({
    name: props.student.name,
    email: props.student.email,
    grade: props.student.grade,
    age: props.student.age,
    timezone: props.student.timezone,
});

const submit = () => {
    form.patch(route('students.update', props.student.id));
}
</script>
