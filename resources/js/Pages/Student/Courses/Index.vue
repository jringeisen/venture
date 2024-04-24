<template>
    <Head title="Courses" />

    <div>
        <div class="w-full bg-primary-gray px-10 py-8 text-white">
            <div class="text-xs space-x-1">
                <Link as="a" :href="route('student.dashboard')" class="text-gray-400">Dashboard</Link>
                <span class="text-gray-400">></span>
                <Link as="a" :href="route('student.courses.index')" class="font-bold">Courses</Link>
            </div>

            <h2 class="text-2xl font-semibold mt-3">Browse Courses</h2>
            <p class="text-xs w-full leading-4 md:w-2/3 lg:w-1/2">Explore a world of learning with our diverse range of courses. Unlock new knowledge and skills at your own pace. Start your journey of discovery today.</p>

            <div class="relative">
                <input type="text" id="search" v-model="form.search" autocomplete="off" class="mt-6 block w-full rounded-full border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-yellow sm:text-sm sm:leading-6" placeholder="Search course, subject, or topic">
                <div @click.prevent="handleSearch" class="bg-primary-yellow rounded-full absolute top-1 right-1.5 p-1.5 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <p class="text-xs mt-2">Popular: Atronomy, Marine Biology, American History</p>
        </div>
        <div class="flex max-w-7xl mx-auto">
            <div class="w-64 px-8 py-4">
                <div>
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold">Courses</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                        </svg>
                    </div>
                    <ul class="py-2">
                        <li v-for="(subject, index) in subjects">
                            <div class="flex items-center">
                                <input :id="subject.text" type="checkbox" v-model="form.subjects" class="h-3 w-3 rounded border-gray-300 text-primary-yellow focus:ring-primary-yellow">
                                <label :for="subject.text" class="ml-3 block text-xs leading-6 text-gray-900 whitespace-nowrap">{{ humanReadableString(subject.text) }} ({{ subject.count }})</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="w-full px-8 py-4">
                <div class="border-b pb-2">
                    <ul class="flex text-xs space-x-4 text-gray-600">
                        <li>All Courses ({{ courses.length }})</li>
                        <li>Courses</li>
                    </ul>
                </div>
                <div class="py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold">Showing {{ courses.length }} results</h3>
                        <p class="text-xs">
                            <span class="text-gray-600">Sort by:</span> <span class="font-semibold">Most Popular</span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-2 mt-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <Link v-for="course in courses" :key="course.id" class="bg-white rounded-lg shadow-md" as="a" :href="route('student.courses.show', course.slug)">
                            <img :src="course.image" alt="course.title" class="w-full h-40 object-cover rounded-t-lg">
                            <div class="p-3">
                                <p class="text-xs font-semibold pb-4">{{ course.title }}</p>
                                <p class="flex items-center text-xs text-gray-700">
                                    Download Curriculum Outline
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </p>
                                <p class="flex text-xs items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-1 w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                    </svg>
                                    {{ humanReadableString(course.length) }}
                                </p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    courses: Array,
    search: String,
    subjects: Array,
});

const form = useForm({
  search: '',
  subjects: [],
})

const handleSearch = () => {
    form.get(route('student.courses.index'), form.search);
};

const humanReadableString = (str) => {
    return str.replace(/_/g, ' ').replace(/\b\w/g, (char) => char.toUpperCase());
};
</script>
