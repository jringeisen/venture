<template>
    <AdminLayout title="Edit Category">
        <div class="max-w-2xl">
            <div class="mb-6">
                <Link :href="route('admin.blog-categories.index')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    &larr; Back to Categories
                </Link>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Edit Category</h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="name" value="Name"/>
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required/>
                            <InputError :message="form.errors.name" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="description" value="Description"/>
                            <TextareaInput id="description" v-model="form.description" class="mt-1 block w-full" rows="3"/>
                            <InputError :message="form.errors.description" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="meta_title" value="Meta Title"/>
                            <TextInput id="meta_title" v-model="form.meta_title" type="text" class="mt-1 block w-full"/>
                            <InputError :message="form.errors.meta_title" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="meta_description" value="Meta Description"/>
                            <TextareaInput id="meta_description" v-model="form.meta_description" class="mt-1 block w-full" rows="2"/>
                            <InputError :message="form.errors.meta_description" class="mt-2"/>
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="form.processing">
                                Update Category
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
import TextareaInput from '@/Components/TextareaInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: props.category.name,
    description: props.category.description || '',
    meta_title: props.category.meta_title || '',
    meta_description: props.category.meta_description || '',
});

const submit = () => {
    form.put(route('admin.blog-categories.update', props.category.id));
};
</script>
