<template>
    <AdminLayout title="Create Category">
        <div class="max-w-2xl">
            <div class="mb-6">
                <Link :href="route('admin.blog-categories.index')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    &larr; Back to Categories
                </Link>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Create New Category</h3>

                    <Form v-model="formData" :action="route('admin.blog-categories.store')" method="post" class="space-y-6" #default="{ errors, processing }">
                        <div>
                            <InputLabel for="name" value="Name"/>
                            <TextInput id="name" v-model="formData.name" name="name" type="text" class="mt-1 block w-full" required/>
                            <InputError :message="errors.name" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="description" value="Description"/>
                            <TextareaInput id="description" v-model="formData.description" name="description" class="mt-1 block w-full" rows="3"/>
                            <InputError :message="errors.description" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="meta_title" value="Meta Title"/>
                            <TextInput id="meta_title" v-model="formData.meta_title" name="meta_title" type="text" class="mt-1 block w-full"/>
                            <InputError :message="errors.meta_title" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="meta_description" value="Meta Description"/>
                            <TextareaInput id="meta_description" v-model="formData.meta_description" name="meta_description" class="mt-1 block w-full" rows="2"/>
                            <InputError :message="errors.meta_description" class="mt-2"/>
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="processing">
                                Create Category
                            </PrimaryButton>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, Form } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const formData = ref({
    name: '',
    description: '',
    meta_title: '',
    meta_description: '',
});
</script>
