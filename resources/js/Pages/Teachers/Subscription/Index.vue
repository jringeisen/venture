<template>
    <Head title="Subscription" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Current Plan -->
            <div class="bg-white shadow-sm border border-slate-100 p-8 rounded-lg dark:bg-primary-gray dark:border-neutral-700 dark:shadow-neutral-900/50">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-base font-semibold leading-6 text-beach-text dark:text-neutral-200">Current Plan</h1>
                        <div class="mt-2 flex items-center gap-3">
                            <span class="text-2xl font-bold text-beach-text dark:text-neutral-200">{{ subscription.plan_label }}</span>
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                :class="subscription.is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : subscription.on_grace_period ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-gray-100 text-gray-700 dark:bg-neutral-700 dark:text-neutral-200'"
                            >
                                {{ subscription.is_active ? 'Active' : subscription.on_grace_period ? 'Grace Period' : 'Free' }}
                            </span>
                        </div>
                    </div>
                    <div v-if="subscription.is_active" class="mt-4 sm:mt-0">
                        <a :href="route('parent.subscription.billing-portal')" class="inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-700 border border-gray-300 dark:border-neutral-900 rounded-md font-semibold text-xs text-gray-700 dark:text-neutral-400 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                            Manage Billing
                        </a>
                    </div>
                </div>
            </div>

            <!-- Usage Meters -->
            <div class="bg-white shadow-sm border border-slate-100 p-8 rounded-lg dark:bg-primary-gray dark:border-neutral-700 dark:shadow-neutral-900/50">
                <h2 class="text-base font-semibold leading-6 text-beach-text dark:text-neutral-200">Usage</h2>
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <!-- AI Questions -->
                    <div class="rounded-lg border border-gray-200 p-5 dark:border-neutral-700">
                        <dt class="text-xs text-beach-text-light dark:text-neutral-400">AI Questions This Hour</dt>
                        <dd class="mt-1 text-lg font-semibold text-beach-text dark:text-neutral-200">
                            {{ subscription.usage.ai_questions_this_hour }}
                            <span class="text-sm font-normal text-beach-text-light">
                                / {{ isUnlimited(subscription.limits.ai_questions_per_hour) ? 'Unlimited' : subscription.limits.ai_questions_per_hour }}
                            </span>
                        </dd>
                        <div v-if="!isUnlimited(subscription.limits.ai_questions_per_hour)" class="mt-2 w-full bg-gray-200 rounded-full h-2 dark:bg-neutral-700">
                            <div class="bg-beach-teal h-2 rounded-full transition-all" :style="{ width: Math.min(100, (subscription.usage.ai_questions_this_hour / subscription.limits.ai_questions_per_hour) * 100) + '%' }"></div>
                        </div>
                    </div>

                    <!-- Students -->
                    <div class="rounded-lg border border-gray-200 p-5 dark:border-neutral-700">
                        <dt class="text-xs text-beach-text-light dark:text-neutral-400">Students</dt>
                        <dd class="mt-1 text-lg font-semibold text-beach-text dark:text-neutral-200">
                            {{ subscription.usage.students }}
                            <span class="text-sm font-normal text-beach-text-light">
                                / {{ subscription.limits.max_students }}
                            </span>
                        </dd>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2 dark:bg-neutral-700">
                            <div class="bg-beach-teal h-2 rounded-full transition-all" :style="{ width: Math.min(100, (subscription.usage.students / subscription.limits.max_students) * 100) + '%' }"></div>
                        </div>
                    </div>

                    <!-- Compliance Reports -->
                    <div class="rounded-lg border border-gray-200 p-5 dark:border-neutral-700">
                        <dt class="text-xs text-beach-text-light dark:text-neutral-400">Compliance Reports This Month</dt>
                        <dd class="mt-1 text-lg font-semibold text-beach-text dark:text-neutral-200">
                            {{ subscription.usage.compliance_reports_this_month }}
                            <span class="text-sm font-normal text-beach-text-light">
                                / {{ isUnlimited(subscription.limits.compliance_reports_per_month) ? 'Unlimited' : subscription.limits.compliance_reports_per_month }}
                            </span>
                        </dd>
                        <div v-if="!isUnlimited(subscription.limits.compliance_reports_per_month) && subscription.limits.compliance_reports_per_month > 0" class="mt-2 w-full bg-gray-200 rounded-full h-2 dark:bg-neutral-700">
                            <div class="bg-beach-teal h-2 rounded-full transition-all" :style="{ width: Math.min(100, (subscription.usage.compliance_reports_this_month / subscription.limits.compliance_reports_per_month) * 100) + '%' }"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upgrade Cards -->
            <div v-if="subscription.plan !== 'classroom'" class="bg-white shadow-sm border border-slate-100 p-8 rounded-lg dark:bg-primary-gray dark:border-neutral-700 dark:shadow-neutral-900/50">
                <h2 class="text-base font-semibold leading-6 text-beach-text dark:text-neutral-200">Upgrade Your Plan</h2>
                <p class="mt-2 text-sm text-beach-text-light dark:text-neutral-400">
                    Unlock more features for your family's learning journey.
                </p>
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Family Card -->
                    <div v-if="subscription.plan === 'free'" class="rounded-lg border-2 border-beach-teal p-6">
                        <h3 class="text-lg font-semibold text-beach-text dark:text-neutral-200">Family</h3>
                        <p class="mt-1 text-sm text-beach-text-light dark:text-neutral-400">Unlimited AI questions, courses, certificates, up to 5 students</p>
                        <p class="mt-3 text-2xl font-bold text-beach-text dark:text-neutral-200">
                            ${{ billingCycle === 'monthly' ? pricing.family.monthly : pricing.family.yearly }}
                            <span class="text-sm font-normal text-beach-text-light">/{{ billingCycle === 'monthly' ? 'mo' : 'yr' }}</span>
                        </p>
                        <div class="mt-4 flex items-center gap-4">
                            <PrimaryButton @click.prevent="handleUpgrade('family')">
                                Upgrade
                            </PrimaryButton>
                            <button @click="billingCycle = billingCycle === 'monthly' ? 'yearly' : 'monthly'" class="text-xs text-beach-teal hover:underline">
                                Switch to {{ billingCycle === 'monthly' ? 'yearly' : 'monthly' }}
                            </button>
                        </div>
                    </div>

                    <!-- Classroom Card -->
                    <div class="rounded-lg border border-gray-200 p-6 dark:border-neutral-700">
                        <h3 class="text-lg font-semibold text-beach-text dark:text-neutral-200">Classroom</h3>
                        <p class="mt-1 text-sm text-beach-text-light dark:text-neutral-400">Up to 25 students, perfect for microschools &amp; co-ops</p>
                        <p class="mt-3 text-2xl font-bold text-beach-text dark:text-neutral-200">
                            ${{ billingCycle === 'monthly' ? pricing.classroom.monthly : pricing.classroom.yearly }}
                            <span class="text-sm font-normal text-beach-text-light">/{{ billingCycle === 'monthly' ? 'mo' : 'yr' }}</span>
                        </p>
                        <div class="mt-4 flex items-center gap-4">
                            <PrimaryButton @click.prevent="handleUpgrade('classroom')">
                                {{ subscription.is_active ? 'Upgrade' : 'Subscribe' }}
                            </PrimaryButton>
                            <button @click="billingCycle = billingCycle === 'monthly' ? 'yearly' : 'monthly'" class="text-xs text-beach-teal hover:underline">
                                Switch to {{ billingCycle === 'monthly' ? 'yearly' : 'monthly' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineOptions({
    layout: AuthenticatedLayout,
});

const props = defineProps({
    subscription: Object,
    pricing: Object,
    plans: Object,
});

const billingCycle = ref('monthly');

const isUnlimited = (value) => value === -1;

const handleUpgrade = (plan) => {
    if (props.subscription.is_active) {
        // Swap existing subscription
        const form = useForm({ plan, billing_cycle: billingCycle.value });
        form.post(route('parent.subscription.swap'));
    } else {
        // New checkout
        const form = useForm({ plan, billing_cycle: billingCycle.value });
        form.post(route('parent.subscription.checkout'));
    }
};
</script>
