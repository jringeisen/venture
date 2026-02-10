<template>
    <Head title="Dashboard" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-8 sm:px-6 lg:px-8">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold dark:text-neutral-200">Welcome back, {{ $page.props.auth.user.name }}!</h1>
                <p class="text-sm text-beach-text-light dark:text-neutral-400">Here's an overview of your family's learning progress.</p>
            </div>

            <!-- Family Stats -->
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                <StatCard
                    title="Learning This Week"
                    :value="familyStats.learning_hours_this_week"
                    icon="clock"
                />
                <StatCard
                    title="Questions Today"
                    :value="familyStats.questions_today"
                    icon="chat"
                />
                <StatCard
                    title="Active Courses"
                    :value="familyStats.active_courses"
                    icon="book"
                />
                <StatCard
                    :title="familyStats.student_count + ' of ' + familyStats.max_students + ' students'"
                    :value="familyStats.plan_label + ' Plan'"
                    icon="credit-card"
                    :small-value="true"
                />
            </div>

            <!-- Student Cards -->
            <div>
                <h2 class="text-base font-semibold leading-6 text-beach-text dark:text-neutral-200 mb-4">Your Students</h2>
                <div v-if="studentCards.length > 0" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <StudentDashboardCard
                        v-for="student in studentCards"
                        :key="student.id"
                        :student="student"
                    />
                </div>
                <div v-else class="bg-white border border-slate-100 p-8 rounded-lg text-center dark:bg-primary-gray dark:border-neutral-700">
                    <p class="text-beach-text-light dark:text-neutral-400">No students yet.</p>
                    <Link :href="route('parent.users.create')" class="mt-3 inline-flex items-center px-4 py-2 bg-beach-teal text-white text-sm font-medium rounded-md hover:bg-beach-teal/90 transition-colors">
                        Add Your First Student
                    </Link>
                </div>
            </div>

            <!-- Bottom Row: Activity + Quick Actions -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Recent Activity -->
                <div class="lg:col-span-2 bg-white border border-slate-100 p-6 rounded-lg shadow-sm dark:bg-primary-gray dark:border-neutral-700 dark:shadow-neutral-900/50">
                    <h2 class="text-base font-semibold leading-6 text-beach-text dark:text-neutral-200 mb-4">Recent Activity</h2>
                    <ul v-if="recentActivity.length > 0" class="space-y-4">
                        <li v-for="(activity, index) in recentActivity" :key="index" class="flex items-start gap-3">
                            <ActivityIcon :type="activity.type" />
                            <div class="min-w-0">
                                <p class="text-sm text-beach-text dark:text-neutral-200">{{ activity.description }}</p>
                                <p class="text-xs text-beach-text-light dark:text-neutral-400">{{ activity.timestamp }}</p>
                            </div>
                        </li>
                    </ul>
                    <p v-else class="text-sm text-beach-text-light dark:text-neutral-400">No recent activity to show.</p>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white border border-slate-100 p-6 rounded-lg shadow-sm dark:bg-primary-gray dark:border-neutral-700 dark:shadow-neutral-900/50">
                    <h2 class="text-base font-semibold leading-6 text-beach-text dark:text-neutral-200 mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <Link :href="route('parent.users.create')" class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 text-sm font-medium text-beach-text hover:bg-gray-50 transition-colors dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">
                            <UsersIcon class="w-5 h-5 text-beach-teal shrink-0" />
                            Add Student
                        </Link>
                        <Link :href="route('parent.compliance.index')" class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 text-sm font-medium text-beach-text hover:bg-gray-50 transition-colors dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">
                            <DocumentIcon class="w-5 h-5 text-beach-teal shrink-0" />
                            Compliance Reports
                        </Link>
                        <Link :href="route('parent.subscription.index')" class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 text-sm font-medium text-beach-text hover:bg-gray-50 transition-colors dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">
                            <CreditCardIcon class="w-5 h-5 text-beach-teal shrink-0" />
                            Manage Subscription
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { UsersIcon, DocumentIcon, CreditCardIcon } from '@heroicons/vue/24/outline';
import StatCard from './Partials/StatCard.vue';
import StudentDashboardCard from './Partials/StudentDashboardCard.vue';
import ActivityIcon from './Partials/ActivityIcon.vue';

defineOptions({
    layout: AuthenticatedLayout,
});

defineProps({
    familyStats: Object,
    studentCards: Array,
    recentActivity: Array,
});
</script>
