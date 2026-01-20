<template>
    <AdminLayout title="Edit User">
        <div class="max-w-2xl">
            <div class="mb-6">
                <Link :href="route('admin.users.index')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    &larr; Back to Users
                </Link>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Edit User</h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="name" value="Name"/>
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required/>
                            <InputError :message="form.errors.name" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="email" value="Email"/>
                            <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required/>
                            <InputError :message="form.errors.email" class="mt-2"/>
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="form.processing">
                                Update User
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
});

const submit = () => {
    form.put(route('admin.users.update', props.user.id));
};
</script>
