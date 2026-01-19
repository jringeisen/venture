<template>
    <AdminLayout title="Edit Blog Post">
        <div class="max-w-3xl">
            <div class="mb-6">
                <Link :href="route('admin.blog-posts.index')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    &larr; Back to Blog Posts
                </Link>
            </div>

            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Edit Blog Post</h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="title" value="Title"/>
                            <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required/>
                            <InputError :message="form.errors.title" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="blog_category_id" value="Category"/>
                            <select id="blog_category_id" v-model="form.blog_category_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white shadow-sm">
                                <option :value="null">No Category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                            <InputError :message="form.errors.blog_category_id" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="excerpt" value="Excerpt"/>
                            <TextareaInput id="excerpt" v-model="form.excerpt" class="mt-1 block w-full" rows="2"/>
                            <InputError :message="form.errors.excerpt" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="content" value="Content"/>
                            <TextareaInput id="content" v-model="form.content" class="mt-1 block w-full" rows="10" required/>
                            <InputError :message="form.errors.content" class="mt-2"/>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="featured_image" value="Featured Image URL"/>
                                <TextInput id="featured_image" v-model="form.featured_image" type="text" class="mt-1 block w-full"/>
                                <InputError :message="form.errors.featured_image" class="mt-2"/>
                            </div>
                            <div>
                                <InputLabel for="alt_text" value="Image Alt Text"/>
                                <TextInput id="alt_text" v-model="form.alt_text" type="text" class="mt-1 block w-full"/>
                                <InputError :message="form.errors.alt_text" class="mt-2"/>
                            </div>
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

                        <div class="flex items-center">
                            <Checkbox id="is_published" v-model:checked="form.is_published"/>
                            <InputLabel for="is_published" value="Published" class="ml-2"/>
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :disabled="form.processing">
                                Update Post
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
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    post: Object,
    categories: Array,
});

const form = useForm({
    title: props.post.title,
    blog_category_id: props.post.blog_category_id,
    excerpt: props.post.excerpt || '',
    content: props.post.content,
    featured_image: props.post.featured_image || '',
    alt_text: props.post.alt_text || '',
    meta_title: props.post.meta_title || '',
    meta_description: props.post.meta_description || '',
    is_published: props.post.is_published,
});

const submit = () => {
    form.put(route('admin.blog-posts.update', props.post.id));
};
</script>
