<template>
    <Head title="Checkout Options" />

    <div class="relative isolate px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center lg:max-w-4xl">
            <p class="mt-2 text-4xl font-bold tracking-tight text-primary-gray dark:text-neutral-300 sm:text-5xl">
                Discover Smarter Learning
            </p>
        </div>
        <p class="mx-auto mt-6 max-w-2xl text-center text-lg leading-8 text-neutral-500 dark:text-neutral-300">
            We offer a free plan that allows you to add one student and that student is alotted 20 questions. If you need more students or more questions, you can upgrade to one of our paid plans.
        </p>
        <div
            class="mx-auto mt-16 grid max-w-lg grid-cols-1 items-center gap-y-6 sm:mt-20 sm:gap-y-0 lg:max-w-4xl lg:grid-cols-2"
        >
            <div
                class="rounded-3xl p-8 ring-1 ring-gray-900/10 sm:p-10 bg-white sm:mx-8 lg:mx-0 rounded-t-3xl sm:rounded-b-none lg:rounded-tr-none lg:rounded-bl-3xl"
            >
                <h3 id="tier-hobby" class="text-base font-semibold leading-7 text-neutral-700">Monthly</h3>
                <p class="mt-4 flex items-baseline gap-x-2">
                    <span class="text-base text-gray-500">Starting At</span>
                    <span class="text-5xl font-bold tracking-tight text-gray-900">$20</span>
                    <span class="text-base text-gray-500">/month</span>
                </p>
                <p class="mt-6 text-base leading-7 text-gray-600">
                    The perfect plan if you&#039;re just getting started with our product.
                </p>
                <ul role="list" class="mt-8 space-y-3 text-sm leading-6 sm:mt-10 text-gray-600">
                    <li class="flex gap-x-3">
                        <svg
                            class="h-6 w-5 flex-none text-neutral-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        1 Student
                    </li>
                    <li class="flex gap-x-3">
                        <svg
                            class="h-6 w-5 flex-none text-neutral-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Each additional student is $20/month
                    </li>
                    <li class="flex gap-x-3">
                        <svg
                            class="h-6 w-5 flex-none text-neutral-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Unlimited Questions
                    </li>
                    <li class="flex gap-x-3">
                        <svg
                            class="h-6 w-5 flex-none text-neutral-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        24-hour support response time
                    </li>
                    <li>
                        <div class="mx-auto max-w-2xl">
                            <label for="students" class="block text-sm font-medium leading-6 text-neutral-500">How many students?</label>
                            <select id="students" v-model="student_count" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-neutral-700 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-gray sm:text-sm sm:leading-6">
                                <option disabled>Choose number of students</option>
                                <option selected :value="1">1 x Student - $20</option>
                                <option :value="2">2 x Student - $40</option>
                                <option :value="3">3 x Student - $60</option>
                                <option :value="4">4 x Student - $80</option>
                                <option :value="5">5 x Student - $100</option>
                                <option :value="6">6 x Student - $120</option>
                            </select>
                        </div>
                    </li>
                </ul>
                <button
                    @click.prevent="handleCheckout('monthly')"
                    type="button"
                    aria-describedby="tier-monthly"
                    class="mt-8 w-full rounded-md py-2.5 px-3.5 text-center text-sm font-semibold focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 sm:mt-10 text-neutral-600 ring-1 ring-inset ring-neutral-200 hover:ring-neutral-300 focus-visible:outline-neutral-600"
                >
                    Get started today
                </button>
                <p class="text-center text-sm mt-2 uppercase">Use <strong>BETA2024</strong> to save 20%</p>
            </div>
            <div class="rounded-3xl p-8 ring-1 ring-gray-900/10 sm:p-10 relative bg-primary-dark-gray shadow-2xl">
                <h3 id="tier-enterprise" class="text-base font-semibold leading-7 text-primary-yellow">Annual</h3>
                <p class="mt-4 flex items-baseline gap-x-2">
                    <span class="text-5xl font-bold tracking-tight text-white">$200</span>
                    <span class="text-base text-gray-400">/year</span>
                </p>
                <p class="mt-6 text-base leading-7 text-gray-300">
                    Dedicated support and infrastructure for your company.
                </p>
                <ul role="list" class="mt-8 space-y-3 text-sm leading-6 sm:mt-10 text-gray-300">
                    <li class="flex gap-x-3">
                        <svg
                            class="h-6 w-5 flex-none text-primary-yellow"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        1 Student
                    </li>
                    <li class="flex gap-x-3">
                        <svg
                            class="h-6 w-5 flex-none text-primary-yellow"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Each additional student is $200/year
                    </li>
                    <li class="flex gap-x-3">
                        <svg
                            class="h-6 w-5 flex-none text-primary-yellow"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Unlimited Questions
                    </li>
                    <li class="flex gap-x-3">
                        <svg
                            class="h-6 w-5 flex-none text-primary-yellow"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Dedicated support representative
                    </li>
                    <li class="flex gap-x-3">
                        <svg
                            class="h-6 w-5 flex-none text-primary-yellow"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Marketing automations
                    </li>
                    <li class="flex gap-x-3">
                        <svg
                            class="h-6 w-5 flex-none text-primary-yellow"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Custom integrations
                    </li>
                    <li>
                        <div class="mx-auto max-w-2xl">
                            <label for="students" class="block text-sm font-medium leading-6 text-neutral-300">How many students?</label>
                            <select id="students" v-model="student_count" class="mt-2 block w-full bg-neutral-800 rounded-md border-0 py-1.5 pl-3 pr-10 text-neutral-300 ring-1 ring-inset ring-neutral-600 focus:ring-2 focus:ring-primary-gray sm:text-sm sm:leading-6">
                                <option disabled>Choose number of students</option>
                                <option selected :value="1">1 x Student - $200</option>
                                <option :value="2">2 x Student - $400</option>
                                <option :value="3">3 x Student - $600</option>
                                <option :value="4">4 x Student - $800</option>
                                <option :value="5">5 x Student - $1000</option>
                                <option :value="6">6 x Student - $1200</option>
                            </select>
                        </div>
                    </li>
                </ul>
                <button
                    @click.prevent="handleCheckout('annual')"
                    type="button"
                    aria-describedby="tier-annual"
                    class="mt-8 w-full rounded-md py-2.5 px-3.5 text-center text-sm font-semibold focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 sm:mt-10 bg-primary-yellow text-neutral-800 shadow-sm hover:bg-yellow-500 focus-visible:outline-yellow-500"
                >
                    Get started today
                </button>
                <p class="text-center text-sm mt-2 uppercase text-neutral-300">Use <strong>BETA2024</strong> to save 20%</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const student_count = ref(1);

defineOptions({
    layout: AuthenticatedLayout,
});

const handleCheckout = (plan) => {
    location.href = route('subscription.checkout', {
        plan: plan,
        student_count: student_count.value
    });
}
</script>
