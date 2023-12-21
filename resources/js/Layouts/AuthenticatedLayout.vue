<template>
    <div>
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
                    <div class="fixed inset-0 bg-gray-900/80" />
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
                                        <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                    </button>
                                </div>
                            </TransitionChild>
                            <!-- Sidebar component, swap this element with another sidebar if you like -->
                            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white pb-2">
                                <div class="flex h-16 shrink-0 items-center bg-green-600">
                                    <p class="text-green-50 text-2xl text-center font-bold w-full dark:text-neutral-400">MINDFUL</p>
                                </div>
                                <nav class="flex flex-1 flex-col px-6">
                                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                        <li>
                                            <ul role="list" class="-mx-2 space-y-1">
                                                <li v-for="item in $page.props.auth.navigation" :key="item.name">
                                                    <a
                                                        :href="item.href"
                                                        :class="[
                                                            item.current
                                                                ? 'bg-gray-50 text-gray-600'
                                                                : 'text-gray-700 hover:text-gray-600 hover:bg-gray-50',
                                                            'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold',
                                                        ]"
                                                    >
                                                        <component
                                                            :is="getIconComponent(item.icon)"
                                                            :class="[
                                                                item.current
                                                                    ? 'text-gray-600'
                                                                    : 'text-gray-400 group-hover:text-gray-600',
                                                                'h-6 w-6 shrink-0',
                                                            ]"
                                                            aria-hidden="true"
                                                        />
                                                        {{ item.name }}
                                                    </a>
                                                </li>
                                            </ul>
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
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-primary-gray">
                <div class="flex h-16 shrink-0 items-center">
                    <p class="flex justify-center items-center text-white text-2xl font-bold w-full dark:text-neutral-400">
                        <ApplicationLogo class="w-9 h-9" />
                        MINDFUL
                    </p>
                </div>
                <nav class="flex flex-1 flex-col px-6">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <Link v-for="(item, index) in $page.props.auth.navigation" :key="index" :href="item.href" as="li" class="cursor-pointer">
                                    <span
                                        :class="[
                                            item.current
                                                ? 'bg-gray-100 text-primary-gray dark:bg-neutral-700 dark:text-neutral-400'
                                                : 'text-gray-300 hover:text-primary-gray hover:bg-gray-50 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-400',
                                            'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold',
                                        ]"
                                    >
                                        <component
                                            :is="getIconComponent(item.icon)"
                                            :class="[
                                                item.current
                                                    ? 'text-primary-gray dark:text-neutral-400'
                                                    : 'text-gray-300 group-hover:text-primary-gray dark:text-neutral-400 dark:group-hover:text-neutral-400',
                                                'h-6 w-6 shrink-0',
                                            ]"
                                            aria-hidden="true"
                                        />
                                        {{ item.name }}
                                    </span>
                                </Link>
                            </ul>
                        </li>
                        <li v-if="$page.props.auth.subjects && Object.keys($page.props.auth.subjects).length > 0">
                            <div class="text-xs font-semibold leading-6 text-gray-300 uppercase">Subjects</div>
                            <ul role="list" class="mt-2 space-y-1">
                                <Link v-for="(subject, index) in $page.props.auth.subjects" :key="index" :href="subject.href">
                                    <a
                                        href="#"
                                        class="text-gray-300 hover:text-primary-yellow group flex gap-x-3 rounded-md px-2 pb-1 text-sm leading-6"
                                        :class="{'text-primary-yellow': subject.current}"
                                    >
                                        <span class="truncate">{{ subject.name }}</span>
                                        <span class="truncate">{{ subject.count }}</span>
                                    </a>
                                </Link>
                            </ul>
                        </li>
                        <li class="relative -mx-6 mt-auto">
                            <div
                                v-if="toggleSettingsMenu"
                                @click.prevent="toggleSettingsMenu = false"
                                class="fixed inset-0 flex items-center"
                                aria-hidden="true"
                            ></div>

                            <a
                                @click.prevent="toggleSettingsMenu = !toggleSettingsMenu"
                                href="#"
                                class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-gray-300 hover:text-primary-gray hover:bg-gray-50 dark:hover:bg-neutral-700 dark:hover:text-neutral-400"
                            >
                                <div class="flex justify-center items-center rounded-full bg-primary-yellow w-7 h-7">
                                    <p class="text-xl text-primary-gray">{{ $page.props.auth.user.name[0] }}</p>
                                </div>
                                <span class="sr-only">Your profile</span>
                                <span aria-hidden="true">{{ $page.props.auth.user.name }}</span>
                            </a>

                            <ul
                                v-if="toggleSettingsMenu"
                                class="absolute bottom-14 border bg-white ml-3 w-11/12 shadow-lg py-2 rounded-lg dark:bg-neutral-700 dark:text-neutral-400 dark:border-none"
                            >
                                <li
                                    @click.prevent="router.post($page.props.auth.type === 'teacher' ? route('user.logout') : route('student.logout'))"
                                    class="cursor-pointer px-4 py-1 hover:bg-gray-100 dark:hover:bg-neutral-600"
                                >
                                    Logout
                                </li>
                                <Link v-if="$page.props.auth.type === 'teacher'" :href="route('profile.edit')" class="cursor-pointer px-4 py-1 block hover:bg-gray-100">Profile</Link>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-white px-4 py-4 shadow-sm sm:px-6 lg:hidden">
            <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                <span class="sr-only">Open sidebar</span>
                <Bars3Icon class="h-6 w-6" aria-hidden="true" />
            </button>
            <div class="flex-1 text-sm font-semibold leading-6 text-gray-900">Dashboard</div>
            <div
                v-if="toggleSettingsMenu"
                @click.prevent="toggleSettingsMenu = false"
                class="fixed inset-0 flex items-center"
                aria-hidden="true"
            ></div>

            <div class="relative">
                <a
                    @click.prevent="toggleSettingsMenu = !toggleSettingsMenu"
                    href="#"
                    class="flex items-center gap-x-4 px-6 text-sm font-semibold leading-6 text-gray-900"
                >
                    <div class="flex justify-center items-center rounded-full bg-gray-200 border border-gray-300 w-7 h-7">
                        <p class="text-xl text-gray-300">{{ $page.props.auth.user.name[0] }}</p>
                    </div>
                    <span class="sr-only">Your profile</span>
                    <span aria-hidden="true">{{ $page.props.auth.user.name }}</span>
                </a>

                <ul
                    v-if="toggleSettingsMenu"
                    class="absolute top-10 border bg-white ml-3 w-40 shadow-lg py-2 rounded-lg"
                >
                    <li
                        @click.prevent="router.post($page.props.auth.type === 'teacher' ? route('user.logout') : route('student.logout'))"
                        class="cursor-pointer px-4 py-1 hover:bg-gray-100"
                    >
                        Logout
                    </li>
                    <Link v-if="$page.props.auth.type === 'teacher'" :href="route('profile.edit')" class="cursor-pointer px-4 py-1 block hover:bg-gray-100">Profile</Link>
                </ul>
            </div>
        </div>

        <main class="relative py-10 lg:pl-72">
            <MotivationalMessage v-if="$page.props.auth.motivationalMessage" :message="$page.props.auth.motivationalMessage" />
            <div class="px-4 sm:px-6 lg:px-8">
                <slot></slot>
            </div>
        </main>
    </div>
</template>

<script setup>
import { router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { Bars3Icon, XMarkIcon, HomeIcon, UsersIcon, DocumentIcon, BookOpenIcon } from '@heroicons/vue/24/outline';
import MotivationalMessage from '@/Components/MotivationalMessage.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const sidebarOpen = ref(false);
const toggleSettingsMenu = ref(false);

const iconMap = {
    'home-icon': HomeIcon,
    'users-icon': UsersIcon,
    'document-icon': DocumentIcon,
    'book-open': BookOpenIcon,
};

const getIconComponent = (iconName) => {
    return iconMap[iconName] || null;
};
</script>
