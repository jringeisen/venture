<template>
    <Head title="Course"/>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 mt-[5rem]">
        <div>
            <h1 class="leading-6 text-center sm:text-left text-3xl sm:text-5xl font-semibold mb-[6rem]">{{
                    course.title
                }}</h1>
            <div class="my-8 text-center sm:text-left">
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
                                        <li class="text-base leading-7 text-gray-600 my-3"
                                            v-for="question in questions">
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
                    class="my-5 w-full rounded-full bg-primary-yellow px-4 py-2.5 text-lg font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600"
                    @click="showEnrollStudentForm = true"
                >
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
        <TransitionRoot as="template" :show="showEnrollStudentForm">
            <Dialog as="div" class="relative z-10" @close="showEnrollStudentForm = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                                 enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                                 leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"/>
                </TransitionChild>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div
                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0 pb-[50%] sm:pb-0">
                        <TransitionChild as="template" enter="ease-out duration-300"
                                         enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                                         leave-from="opacity-100 translate-y-0 sm:scale-100"
                                         leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <DialogPanel
                                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                                <div>
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary-yellow">
                                        <AcademicCapIcon class="h-6 w-6 text-primary-500" aria-hidden="true"/>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-5">
                                        <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">
                                            Enroll Student
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <Combobox as="div" v-model="selectedStudent">
                                                <ComboboxLabel
                                                    class="block text-sm font-medium leading-6 text-gray-900">Search
                                                </ComboboxLabel>
                                                <div class="relative mt-2">
                                                    <ComboboxInput
                                                        class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-yellow sm:text-sm sm:leading-6"
                                                        @change="query = $event.target.value"
                                                        :display-value="(student) => student?.name"/>
                                                    <ComboboxButton
                                                        class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400"
                                                                           aria-hidden="true"/>
                                                    </ComboboxButton>

                                                    <ComboboxOptions v-if="filteredStudents.length > 0"
                                                                     class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                                                        <ComboboxOption v-for="student in filteredStudents"
                                                                        :key="student.id" :value="student" as="template"
                                                                        v-slot="{ active, selected }">
                                                            <li :class="['relative cursor-default select-none py-2 pl-3 pr-9', active ? 'bg-primary-yellow text-primary-500' : 'text-gray-900']">
                                                                <span
                                                                    :class="['block truncate', selected && 'font-semibold']">
                                                                  {{ student.name }}
                                                                </span>

                                                                <span v-if="selected"
                                                                      :class="['absolute inset-y-0 right-0 flex items-center pr-4', active ? 'text-primary-500' : 'text-primary-yellow']">
                                                                  <CheckIcon class="h-5 w-5" aria-hidden="true"/>
                                                                </span>
                                                            </li>
                                                        </ComboboxOption>
                                                    </ComboboxOptions>
                                                </div>
                                            </Combobox>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-6">
                                    <button type="button"
                                            class="inline-flex w-full justify-center rounded-md bg-primary-yellow px-3 py-2 text-sm font-semibold text-primary-500 shadow-sm hover:bg-primary-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-700"
                                            @click="enrollStudent">Enroll
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>

<script setup>
import {Head} from '@inertiajs/vue3';
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxLabel,
    ComboboxOption,
    ComboboxOptions,
    Dialog,
    DialogPanel,
    DialogTitle,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    TransitionChild,
    TransitionRoot
} from '@headlessui/vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {ClockIcon, GlobeAmericasIcon, MinusSmallIcon, PlusSmallIcon, AcademicCapIcon} from "@heroicons/vue/24/outline";
import {CheckIcon, ChevronUpDownIcon} from '@heroicons/vue/20/solid'
import pkg from 'lodash';
import {ref, computed} from "vue";
import axios from "axios";

const {groupBy} = pkg;

const showEnrollStudentForm = ref(false)

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    course: Object,
    students: Object
});

const query = ref('');

const selectedStudent = ref(null);

const filteredStudents = computed(() =>
    query.value === ''
        ? props.students
        : props.students.filter((student) => {
            return student.name.toLowerCase().includes(query.value.toLowerCase())
        })
);

const weeksOfQuestions = groupBy(
    props.course.course_prompts,
    (prompt) => prompt.week_number
);

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

const enrollStudent = () => {
    axios.post(
        route(
            'parent.courses.enroll',
            {
                course: props.course.id,
                user: selectedStudent.value.id
            }
        )
    )
        .then(
            () => {
                showEnrollStudentForm.value = false;
            }
        )
        .catch(
            () => {
                showEnrollStudentForm.value = false;
            }
        )
}
</script>
