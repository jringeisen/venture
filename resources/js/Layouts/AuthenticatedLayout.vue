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
                    <div class="fixed inset-0 bg-black/20"/>
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
                                        <XMarkIcon class="h-6 w-6 text-beach-text" aria-hidden="true"/>
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white pb-2">
                                <div class="flex h-16 justify-center items-center">
                                    <ApplicationLogo class="w-9 h-9"/>
                                    <p class="text-beach-ocean-deep text-2xl text-center font-bold">VENTURE</p>
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
                                                                ? 'bg-beach-teal/10 text-beach-teal'
                                                                : 'text-beach-text hover:text-beach-teal hover:bg-slate-50',
                                                            'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold',
                                                        ]"
                                                    >
                                                        <component
                                                            :is="getIconComponent(item.icon)"
                                                            :class="[
                                                                item.current
                                                                    ? 'text-beach-teal'
                                                                    : 'text-beach-text-light group-hover:text-beach-teal',
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
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white border-r border-slate-200">
                <div class="flex h-16 shrink-0 items-center">
                    <p class="flex justify-center items-center text-beach-ocean-deep text-2xl font-bold w-full">
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
                                                ? 'bg-beach-teal/10 text-beach-teal'
                                                : 'text-beach-text hover:text-beach-teal hover:bg-slate-50',
                                            'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold',
                                        ]"
                                    >
                                        <component
                                            :is="getIconComponent(item.icon)"
                                            :class="[
                                                item.current
                                                    ? 'text-beach-teal'
                                                    : 'text-beach-text-light group-hover:text-beach-teal',
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
                            <div class="text-xs font-semibold leading-6 text-beach-text-light uppercase">Subjects</div>
                            <ul role="list" class="mt-2 space-y-1">
                                <Link href="/student/topic/all">
                                    <button
                                        class="text-beach-text-light hover:text-beach-teal group flex gap-x-3 rounded-md px-2 pb-1 text-sm leading-6"
                                        :class="{'text-beach-teal font-semibold': route().current('student.topic.show', { topic: 'all' })}"
                                    >
                                        <span class="truncate">All</span>
                                        <span class="truncate">{{ totalSubjectsCount }}</span>
                                    </button>
                                </Link>
                                <Link v-for="(subject, index) in $page.props.auth.subjects" :key="index"
                                      :href="subject.href">
                                    <button
                                        class="text-beach-text-light hover:text-beach-teal group flex gap-x-3 rounded-md px-2 pb-1 text-sm leading-6"
                                        :class="{'text-beach-teal font-semibold': subject.current}"
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
                                class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-beach-text hover:text-beach-teal hover:bg-slate-50"
                            >
                                <div class="flex justify-center items-center rounded-full bg-beach-teal w-7 h-7">
                                    <p class="text-xl text-white">{{ $page.props.auth.user.name[0] }}</p>
                                </div>
                                <span class="sr-only">Your profile</span>
                                <span aria-hidden="true">{{ $page.props.auth.user.name }}</span>
                            </a>

                            <ul
                                v-if="toggleSettingsMenu"
                                class="absolute bottom-14 border border-slate-200 bg-white ml-3 w-11/12 shadow-lg py-2 rounded-lg"
                            >
                                <li
                                    @click.prevent="router.post(route('logout'))"
                                    class="cursor-pointer px-4 py-1 text-beach-text hover:bg-slate-50 hover:text-beach-teal"
                                >
                                    Logout
                                </li>
                                <Link v-if="$page.props.auth.type === 'teacher'" :href="route('profile.edit')"
                                      class="cursor-pointer px-4 py-1 block text-beach-text hover:bg-slate-50 hover:text-beach-teal">
                                    Profile
                                </Link>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div
            class="sticky top-0 z-40 flex justify-between items-center gap-x-6 bg-white px-4 py-4 shadow-sm sm:px-6 lg:hidden">
            <button type="button" class="-m-2.5 p-2.5 text-beach-text lg:hidden" @click="sidebarOpen = true">
                <span class="sr-only">Open sidebar</span>
                <Bars3Icon class="h-6 w-6" aria-hidden="true"/>
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
                    class="flex items-center gap-x-4 px-6 text-sm font-semibold leading-6 text-beach-text"
                >
                    <div
                        class="flex justify-center items-center rounded-full bg-beach-teal w-7 h-7">
                        <p class="text-xl text-white">{{ $page.props.auth.user.name[0] }}</p>
                    </div>
                    <span class="sr-only">Your profile</span>
                    <span aria-hidden="true">{{ $page.props.auth.user.name }}</span>
                </a>

                <ul
                    v-if="toggleSettingsMenu"
                    class="absolute top-10 border border-slate-200 bg-white ml-3 w-40 shadow-lg py-2 rounded-lg"
                >
                    <li
                        @click.prevent="router.post(route('logout'))"
                        class="cursor-pointer px-4 py-1 text-beach-text hover:bg-slate-50 hover:text-beach-teal"
                    >
                        Logout
                    </li>
                    <Link v-if="$page.props.auth.type === 'teacher'" :href="route('profile.edit')"
                          class="cursor-pointer px-4 py-1 block text-beach-text hover:bg-slate-50 hover:text-beach-teal">Profile
                    </Link>
                </ul>
            </div>
        </div>

        <main class="relative py-10 lg:pl-72 bg-slate-50">
            <div class="px-4 sm:px-6 lg:px-8">
                <div v-if="isBeingImpersonated()" class="max-w-7xl mx-auto sm:px-8 pb-6">
                    <PrimaryButton @click.prevent="router.get(route('users.stop.impersonating'))" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                        </svg>
                        Back to Parent Portal
                    </PrimaryButton>
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
    AcademicCapIcon,
    Bars3Icon,
    BookOpenIcon,
    CalendarDaysIcon,
    ChatBubbleLeftEllipsisIcon,
    CreditCardIcon,
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
    'academic-cap': AcademicCapIcon,
    'credit-card': CreditCardIcon,
    'calendar-days': CalendarDaysIcon,
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
    ).catch(() => {});
};

const persistElapsedTime = function () {
    const endDate = new Date();
    const spentTime = endDate.getTime() - startDate.value.getTime();

    elapsedTime.value += spentTime;

    if (elapsedTime.value && elapsedTime.value > 0) {
        axios.post(
            route('student.activity.store'),
            {totalSeconds: elapsedTime.value / 1000}
        ).catch(() => {});
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
    if (page.props.auth?.type === 'student') {
        persistElapsedTime();
        removeStudentActiveListeners();
    }
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
