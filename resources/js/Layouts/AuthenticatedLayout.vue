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
                            <!-- Sidebar component, swap this element with another sidebar if you like -->
                            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-primary-gray pb-2">
                                <div class="flex h-16 justify-center items-center bg-primary-gray">
                                    <ApplicationLogo class="w-9 h-9"/>
                                    <p class="text-neutral-100 text-2xl text-center font-bold">VENTURE</p>
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
                        <ApplicationLogo class="w-9 h-9"/>
                        VENTURE
                    </p>
                </div>
                <nav class="flex flex-1 flex-col px-6">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <Link v-for="(item, index) in $page.props.auth.navigation" :key="index"
                                      :href="item.href" as="li" class="cursor-pointer">
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
                                <Link href="/student/topic/all">
                                    <button
                                        class="text-gray-300 hover:text-primary-yellow group flex gap-x-3 rounded-md px-2 pb-1 text-sm leading-6"
                                        :class="{'text-primary-yellow': route().current('student.topic.show', { topic: 'all' })}"
                                    >
                                        <span class="truncate">All</span>
                                        <span class="truncate">{{ totalSubjectsCount }}</span>
                                    </button>
                                </Link>
                                <Link v-for="(subject, index) in $page.props.auth.subjects" :key="index"
                                      :href="subject.href">
                                    <button
                                        class="text-gray-300 hover:text-primary-yellow group flex gap-x-3 rounded-md px-2 pb-1 text-sm leading-6"
                                        :class="{'text-primary-yellow': subject.current}"
                                    >
                                        <span class="truncate">{{ subject.name }}</span>
                                        <span class="truncate">{{ subject.count }}</span>
                                    </button>
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
                                    @click.prevent="router.post(route('logout'))"
                                    class="cursor-pointer px-4 py-1 hover:bg-gray-100 dark:hover:bg-neutral-600"
                                >
                                    Logout
                                </li>
                                <Link v-if="$page.props.auth.type === 'teacher'" :href="route('profile.edit')"
                                      class="cursor-pointer px-4 py-1 block hover:bg-gray-100 dark:hover:bg-neutral-600">
                                    Profile
                                </Link>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div
            class="sticky top-0 z-40 flex justify-between items-center gap-x-6 bg-white px-4 py-4 shadow-sm sm:px-6 lg:hidden dark:bg-primary-gray">
            <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                <span class="sr-only">Open sidebar</span>
                <Bars3Icon class="h-6 w-6 dark:text-neutral-400" aria-hidden="true"/>
            </button>
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
                    <div
                        class="flex justify-center items-center rounded-full bg-gray-200 border border-gray-300 w-7 h-7 dark:bg-primary-yellow dark:border-none">
                        <p class="text-xl text-gray-300 dark:text-primary-gray">{{ $page.props.auth.user.name[0] }}</p>
                    </div>
                    <span class="sr-only">Your profile</span>
                    <span aria-hidden="true" class="dark:text-neutral-400">{{ $page.props.auth.user.name }}</span>
                </a>

                <ul
                    v-if="toggleSettingsMenu"
                    class="absolute top-10 border bg-white ml-3 w-40 shadow-lg py-2 rounded-lg"
                >
                    <li
                        @click.prevent="router.post(route('logout'))"
                        class="cursor-pointer px-4 py-1 hover:bg-gray-100"
                    >
                        Logout
                    </li>
                    <Link v-if="$page.props.auth.type === 'teacher'" :href="route('profile.edit')"
                          class="cursor-pointer px-4 py-1 block hover:bg-gray-100">Profile
                    </Link>
                </ul>
            </div>
        </div>

        <main class="relative py-10 lg:pl-72">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto sm:px-8">
                    <div v-if="isBeingImpersonated()" class="pb-6">
                        <PrimaryButton @click.prevent="router.get(route('users.stop.impersonating'))" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                            </svg>
                            Back to Parent Portal
                        </PrimaryButton>
                    </div>
                </div>
                <slot></slot>
            </div>
        </main>
    </div>
</template>

<script setup>
import {Link, router, usePage} from '@inertiajs/vue3';
import {onMounted, onUnmounted, ref} from 'vue';
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from '@headlessui/vue';
import {
    Bars3Icon,
    BookOpenIcon,
    ChatBubbleLeftEllipsisIcon,
    DocumentIcon,
    HomeIcon,
    UsersIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const page = usePage();

const sidebarOpen = ref(false);
const toggleSettingsMenu = ref(false);

const totalSubjectsCount = Object.values(page.props.auth.subjects || {})
    .reduce(
        (accum, item) => accum + item.count,
        0
    );

const iconMap = {
    'home-icon': HomeIcon,
    'users-icon': UsersIcon,
    'document-icon': DocumentIcon,
    'book-open': BookOpenIcon,
    'chat-bubble-left-ellipsis': ChatBubbleLeftEllipsisIcon,
};

const getIconComponent = (iconName) => {
    return iconMap[iconName] || null;
};

const isBeingImpersonated = () => {
    return page.props.auth.type === 'student'
        && page.props.auth.isImpersonated;
};

const isServer = typeof window === 'undefined'
const timeoutDuration = 600000;
const pollDuration = 45000;
const throttleDuration = 1500;

let startDate = ref(new Date());
let lastTimeOut = ref(null);
let elapsedTime = ref(0);
let timeout = ref(null);
let activityPoll = ref(null);
let isTimedOut = ref(false);
let throttled = ref(false);

const resetStartDate = function () {
    startDate.value = new Date();
};

const updateElapsedTime = function () {
    const endDate = new Date();
    const spentTime = endDate.getTime() - startDate.value.getTime();

    elapsedTime.value += spentTime;

    axios.post(
        route('student.activity.update'),
        {totalSeconds: elapsedTime.value / 1000}
    );
};

const persistElapsedTime = function () {
    const endDate = new Date();
    const spentTime = endDate.getTime() - startDate.value.getTime();

    elapsedTime.value += spentTime;

    if (elapsedTime.value && elapsedTime.value > 0) {
        axios.post(
            route('student.activity.store'),
            {totalSeconds: elapsedTime.value / 1000}
        );
    }

    resetTimeoutAndCheckPoll();
};

const createStudentActiveListeners = () => {
    if (!isServer) {
        document.addEventListener('mousemove', resetTimeoutAndCheckPoll);
        document.addEventListener('keypress', resetTimeoutAndCheckPoll);
        document.addEventListener('scroll', resetTimeoutAndCheckPoll);
        document.addEventListener('mousedown', resetTimeoutAndCheckPoll);
        document.addEventListener('touchstart', resetTimeoutAndCheckPoll);
        document.addEventListener('click', resetTimeoutAndCheckPoll);
        document.addEventListener('keydown', resetTimeoutAndCheckPoll);

        activityPoll.value = setInterval(() => {
            const currentTime = new Date().getTime();
            const timeSinceLastTimeout = currentTime - lastTimeOut.value;

            if (!isTimedOut.value && (timeSinceLastTimeout <= pollDuration || timeSinceLastTimeout > 10000000)) {
                updateElapsedTime();
            }

            resetStartDate();
        }, pollDuration);

        resetTimeoutAndCheckPoll();
    }
}

const removeStudentActiveListeners = () => {
    if (!isServer) {
        clearTimeout(timeout.value);
        clearTimeout(activityPoll.value);

        document.removeEventListener('mousemove', resetTimeoutAndCheckPoll);
        document.removeEventListener('keypress', resetTimeoutAndCheckPoll);
        document.removeEventListener('scroll', resetTimeoutAndCheckPoll);
        document.removeEventListener('mousedown', resetTimeoutAndCheckPoll);
        document.removeEventListener('touchstart', resetTimeoutAndCheckPoll);
        document.removeEventListener('click', resetTimeoutAndCheckPoll);
        document.removeEventListener('keydown', resetTimeoutAndCheckPoll);
    }
}

const resetTimeoutAndCheckPoll = () => {
    throttle(() => {
        isTimedOut.value = false;

        clearTimeout(timeout.value);

        timeout.value = setTimeout(() => {
            isTimedOut.value = true;
            lastTimeOut.value = new Date().getTime();

            persistElapsedTime();

            elapsedTime.value = 0;
        }, timeoutDuration);
    }, throttleDuration)
};

onMounted(() => {
    if (page.props.auth.type === 'student') {
        createStudentActiveListeners();
    }
})

onUnmounted(() => {
    persistElapsedTime();

    removeStudentActiveListeners();
});

const throttle = (fn, wait) => {
    if (!throttled.value) {
        fn.apply(this);

        throttled.value = true;

        setTimeout(() => {
            throttled.value = false;
        }, wait);
    }
}
</script>
