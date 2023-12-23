<template>
    <Head title="Edit Student" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow p-8 rounded-lg dark:bg-primary-gray">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-400">Edit {{ student.name }}</h1>
                        <p class="mt-2 text-sm text-gray-700 dark:text-neutral-400">Update this students details.</p>
                    </div>
                </div>
                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <form class="space-y-6" @submit.prevent="submit()">
                                <div>
                                    <InputLabel for="name" value="Name" />
                                    <div class="mt-2">
                                        <TextInput id="name" v-model="form.name" autocomplete="name" required class="w-full" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="email" value="Email" />
                                    <div class="mt-2">
                                        <TextInput id="email" v-model="form.email" type="email" autocomplete="email" required class="w-full" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="grade" value="Grade" />
                                    <div class="mt-2">
                                        <TextInput id="grade" v-model="form.grade" type="number" autocomplete="grade" required class="w-full" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="age" value="Age" />
                                    <div class="mt-2">
                                        <TextInput id="age" v-model="form.age" type="number" autocomplete="age" required class="w-full" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="timezone" value="Timezone" />
                                    <select id="timezone" v-model="form.timezone" class="w-full border-gray-300 focus:border-primary-gray focus:ring-primary-gray rounded-md shadow-sm dark:focus:border-neutral-900 dark:focus:ring-neutral-900 dark:border-neutral-900 dark:bg-primary-gray dark:text-neutral-400 dark:placeholder:text-neutral-400">
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
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

defineOptions({
    layout: AuthenticatedLayout
});

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
