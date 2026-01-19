<template>
    <div class="min-h-screen bg-gray-100 dark:bg-neutral-900">
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" class="relative z-50 lg:hidden" @close="sidebarOpen = false">
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-neutral-900/80"/>
                </TransitionChild>

                <div class="fixed inset-0 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                                    <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                                        <span class="sr-only">Close sidebar</span>
                                        <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true"/>
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-indigo-600 px-6 pb-4">
                                <div class="flex h-16 shrink-0 items-center">
                                    <ApplicationLogo class="h-8 w-auto"/>
                                    <span class="ml-2 text-xl font-bold text-white">Admin</span>
                                </div>
                                <nav class="flex flex-1 flex-col">
                                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                        <li>
                                            <ul role="list" class="-mx-2 space-y-1">
                                                <li v-for="item in navigation" :key="item.name">
                                                    <Link
                                                        :href="item.href"
                                                        :class="[
                                                            item.current
                                                                ? 'bg-indigo-700 text-white'
                                                                : 'text-indigo-200 hover:text-white hover:bg-indigo-700',
                                                            'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold',
                                                        ]"
                                                    >
                                                        <component :is="item.icon" class="h-6 w-6 shrink-0" aria-hidden="true"/>
                                                        {{ item.name }}
                                                    </Link>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="mt-auto">
                                            <Link
                                                :href="route('parent.dashboard')"
                                                class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-indigo-200 hover:bg-indigo-700 hover:text-white"
                                            >
                                                <ArrowLeftIcon class="h-6 w-6 shrink-0" aria-hidden="true"/>
                                                Back to App
                                            </Link>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-indigo-600 px-6 pb-4">
                <div class="flex h-16 shrink-0 items-center">
                    <ApplicationLogo class="h-8 w-auto"/>
                    <span class="ml-2 text-xl font-bold text-white">Admin</span>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li v-for="item in navigation" :key="item.name">
                                    <Link
                                        :href="item.href"
                                        :class="[
                                            item.current
                                                ? 'bg-indigo-700 text-white'
                                                : 'text-indigo-200 hover:text-white hover:bg-indigo-700',
                                            'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold',
                                        ]"
                                    >
                                        <component :is="item.icon" class="h-6 w-6 shrink-0" aria-hidden="true"/>
                                        {{ item.name }}
                                    </Link>
                                </li>
                            </ul>
                        </li>
                        <li class="mt-auto">
                            <Link
                                :href="route('parent.dashboard')"
                                class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-indigo-200 hover:bg-indigo-700 hover:text-white"
                            >
                                <ArrowLeftIcon class="h-6 w-6 shrink-0" aria-hidden="true"/>
                                Back to App
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="lg:pl-72">
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 dark:bg-neutral-800 dark:border-neutral-700">
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true"/>
                </button>

                <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"/>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex flex-1 items-center">
                        <h1 v-if="title" class="text-lg font-semibold text-gray-900 dark:text-white">{{ title }}</h1>
                    </div>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <div class="flex items-center gap-x-4 lg:gap-x-6">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $page.props.auth.user.name }}</span>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                            >
                                Logout
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    <slot/>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import {
    Bars3Icon,
    XMarkIcon,
    HomeIcon,
    AcademicCapIcon,
    DocumentTextIcon,
    FolderIcon,
    UsersIcon,
    ChatBubbleLeftRightIcon,
    ArrowLeftIcon,
} from '@heroicons/vue/24/outline';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    title: String,
});

const page = usePage();
const sidebarOpen = ref(false);

const navigation = computed(() => [
    {
        name: 'Dashboard',
        href: route('admin.dashboard'),
        icon: HomeIcon,
        current: route().current('admin.dashboard'),
    },
    {
        name: 'Courses',
        href: route('admin.courses.index'),
        icon: AcademicCapIcon,
        current: route().current('admin.courses.*'),
    },
    {
        name: 'Blog Posts',
        href: route('admin.blog-posts.index'),
        icon: DocumentTextIcon,
        current: route().current('admin.blog-posts.*'),
    },
    {
        name: 'Blog Categories',
        href: route('admin.blog-categories.index'),
        icon: FolderIcon,
        current: route().current('admin.blog-categories.*'),
    },
    {
        name: 'Users',
        href: route('admin.users.index'),
        icon: UsersIcon,
        current: route().current('admin.users.*'),
    },
    {
        name: 'Feedback',
        href: route('admin.feedback.index'),
        icon: ChatBubbleLeftRightIcon,
        current: route().current('admin.feedback.*'),
    },
]);
</script>
