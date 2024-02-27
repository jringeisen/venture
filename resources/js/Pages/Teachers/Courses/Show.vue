<template>
    <Head title="Course"/>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 mt-[5rem]">
        <div>
            <h1 class="leading-6 text-3xl sm:text-5xl font-semibold mb-[6rem]">{{ course.title }}</h1>
            <div class="mt-8">
                <div class="text-4xl">Course Content</div>
                <div class="mx-auto max-w-7xl pr-5">
                    <div class="mx-auto max-w-4xl divide-y divide-gray-900/10">
                        <dl class="mt-5 space-y-6 divide-y divide-gray-900/10">
                            <Disclosure as="div" v-for="(questions, week) in weeksOfQuestions" :key="week" class="pt-6"
                                        v-slot="{ open }">
                                <dt>
                                    <DisclosureButton
                                        class="flex w-full items-start justify-between text-left text-gray-900">
                                        <span class="text-base font-semibold leading-7">Week {{ week }}</span>
                                        <span class="ml-6 flex h-7 items-center">
                  <PlusSmallIcon v-if="!open" class="h-6 w-6" aria-hidden="true"/>
                  <MinusSmallIcon v-else class="h-6 w-6" aria-hidden="true"/>
                </span>
                                    </DisclosureButton>
                                </dt>
                                <DisclosurePanel as="dd" class="mt-2 pr-12">
                                    <ol class="list-decimal my-5 ml-4">
                                        <li class="text-base leading-7 text-gray-600 my-3" v-for="question in questions">
                                            {{ question.prompt }}
                                        </li>
                                    </ol>
                                </DisclosurePanel>
                            </Disclosure>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="border rounded-md border-primary-yellow p-6 mt-0 sm:-mt-[3rem] min-h-[40rem]">
                <img :src="course.image" class="rounded-md shadow-sm" alt="Course Image"/>
                <button
                    class="my-5 w-full rounded-full bg-primary-yellow px-4 py-2.5 text-lg font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">
                    Enroll Student
                </button>
                <hr class="my-3"/>
                <div class="mt-3">
                    <h2 class="text-2xl mt-3">Course Description</h2>
                    <div class="mt-3">
                        <p>{{ course.description }}</p>
                    </div>
                </div>
                <div class="mt-6">
                    <h2 class="text-2xl mt-3">Course Details</h2>
                    <div class="mt-3">
                        <div v-for="detail in details" class="flex mb-3">
                            <component :is="detail.icon" class="h-5 mr-2 mt-0.5"/>
                            {{ detail.name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {Head} from '@inertiajs/vue3';
import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {ClockIcon, GlobeAmericasIcon, MinusSmallIcon, PlusSmallIcon} from "@heroicons/vue/24/outline/index.js";
import pkg from 'lodash';

const {groupBy, sortBy} = pkg;

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    course: Object
});

const weeksOfQuestions = groupBy(
    props.course.course_prompts,
    (prompt) => prompt.week_number
);

console.warn(weeksOfQuestions);

const details = [
    {
        icon: ClockIcon,
        name: Math.max(...props.course.course_prompts.map(cp => cp.week_number), 0) + ' Weekly Lessons'
    },
    {
        icon: GlobeAmericasIcon,
        name: 'Available Worldwide'
    }
];
</script>
