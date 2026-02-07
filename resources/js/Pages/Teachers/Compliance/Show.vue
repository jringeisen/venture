<template>
    <Head :title="report.title"/>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Header -->
            <div class="bg-white shadow-sm border border-slate-100 p-8 rounded-lg dark:bg-primary-gray dark:border-neutral-700">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-lg font-semibold text-beach-text dark:text-neutral-300">{{ report.title }}</h1>
                        <p class="mt-1 text-sm text-beach-text-light dark:text-neutral-400">
                            {{ report.student.name }} &middot; {{ report.state }} &middot; {{ report.period_start }} - {{ report.period_end }}
                        </p>
                    </div>
                    <div class="mt-4 flex gap-3 sm:mt-0">
                        <SecondaryButton @click.prevent="router.get(route('parent.compliance.index'))">Back</SecondaryButton>
                        <a :href="route('parent.compliance.reports.pdf', report.id)">
                            <PrimaryButton>Download PDF</PrimaryButton>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Instruction Days</dt>
                    <dd class="mt-1 text-2xl font-semibold text-beach-text dark:text-neutral-300">{{ report.summary.total_instruction_days }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Instruction Hours</dt>
                    <dd class="mt-1 text-2xl font-semibold text-beach-text dark:text-neutral-300">{{ report.summary.total_instruction_hours }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Active Courses</dt>
                    <dd class="mt-1 text-2xl font-semibold text-beach-text dark:text-neutral-300">{{ report.summary.courses_active }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Completed</dt>
                    <dd class="mt-1 text-2xl font-semibold text-beach-text dark:text-neutral-300">{{ report.summary.courses_completed }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Avg Trivia Score</dt>
                    <dd class="mt-1 text-2xl font-semibold text-beach-text dark:text-neutral-300">{{ report.summary.average_trivia_score }}%</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Interactions</dt>
                    <dd class="mt-1 text-2xl font-semibold text-beach-text dark:text-neutral-300">{{ report.summary.total_interactions }}</dd>
                </div>
            </div>

            <!-- Attendance Summary -->
            <div v-if="attendanceSummary" class="bg-white shadow-sm border border-slate-100 rounded-lg dark:bg-primary-gray dark:border-neutral-700">
                <button @click.prevent="toggleSection('attendance')" class="flex w-full items-center justify-between p-6">
                    <h2 class="text-base font-semibold text-beach-text dark:text-neutral-300">Attendance</h2>
                    <ChevronDownIcon :class="['h-5 w-5 text-beach-text-light transition-transform', openSections.attendance ? 'rotate-180' : '']"/>
                </button>
                <div v-if="openSections.attendance" class="border-t border-gray-200 dark:border-neutral-600 p-6">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-5 mb-4">
                        <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <dd class="text-xl font-semibold text-green-600 dark:text-green-400">{{ attendanceSummary.present }}</dd>
                            <dt class="text-xs text-green-700 dark:text-green-400">Present</dt>
                        </div>
                        <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <dd class="text-xl font-semibold text-blue-600 dark:text-blue-400">{{ attendanceSummary.field_trip }}</dd>
                            <dt class="text-xs text-blue-700 dark:text-blue-400">Field Trips</dt>
                        </div>
                        <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <dd class="text-xl font-semibold text-purple-600 dark:text-purple-400">{{ attendanceSummary.offline_day }}</dd>
                            <dt class="text-xs text-purple-700 dark:text-purple-400">Offline Days</dt>
                        </div>
                        <div class="text-center p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                            <dd class="text-xl font-semibold text-orange-600 dark:text-orange-400">{{ attendanceSummary.excused_absence }}</dd>
                            <dt class="text-xs text-orange-700 dark:text-orange-400">Excused</dt>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-neutral-700 rounded-lg col-span-2 sm:col-span-1">
                            <dd class="text-xl font-semibold text-beach-text dark:text-neutral-300">{{ attendanceSummary.total_attendance_days }}</dd>
                            <dt class="text-xs text-beach-text-light dark:text-neutral-400">Total</dt>
                        </div>
                    </div>

                    <div v-if="attendanceLog.length > 0" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-neutral-700">
                            <thead class="bg-gray-50 dark:bg-neutral-600">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-beach-text dark:text-neutral-300">Date</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-beach-text dark:text-neutral-300">Type</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-beach-text dark:text-neutral-300">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:bg-neutral-500 dark:divide-neutral-600">
                                <tr v-for="entry in attendanceLog" :key="entry.date">
                                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-beach-text dark:text-primary-gray">{{ entry.date }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-beach-text-light dark:text-primary-gray">{{ entry.label }}</td>
                                    <td class="px-3 py-4 text-sm text-beach-text-light dark:text-primary-gray">{{ entry.notes || 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else class="text-sm text-beach-text-light dark:text-neutral-400">No attendance records for this period.</p>
                </div>
            </div>

            <!-- Daily Activity Log -->
            <div class="bg-white shadow-sm border border-slate-100 rounded-lg dark:bg-primary-gray dark:border-neutral-700">
                <button @click.prevent="toggleSection('activity')" class="flex w-full items-center justify-between p-6">
                    <h2 class="text-base font-semibold text-beach-text dark:text-neutral-300">Daily Activity Log</h2>
                    <ChevronDownIcon :class="['h-5 w-5 text-beach-text-light transition-transform', openSections.activity ? 'rotate-180' : '']"/>
                </button>
                <div v-if="openSections.activity" class="border-t border-gray-200 dark:border-neutral-600">
                    <div v-if="activityLog.length > 0" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-neutral-700">
                            <thead class="bg-gray-50 dark:bg-neutral-600">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-beach-text dark:text-neutral-300">Date</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-beach-text dark:text-neutral-300">Duration</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-beach-text dark:text-neutral-300">Sessions</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-beach-text dark:text-neutral-300">Reading Materials</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:bg-neutral-500 dark:divide-neutral-600">
                                <tr v-for="entry in activityLog" :key="entry.date">
                                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-beach-text dark:text-primary-gray">{{ entry.date }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-beach-text-light dark:text-primary-gray">{{ formatDuration(entry.total_seconds) }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-beach-text-light dark:text-primary-gray">{{ entry.sessions }}</td>
                                    <td class="px-3 py-4 text-sm text-beach-text-light dark:text-primary-gray">{{ entry.courses_studied.join(', ') || 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else class="p-6 text-sm text-beach-text-light dark:text-neutral-400">No activity recorded for this period.</p>
                </div>
            </div>

            <!-- Course Progress -->
            <div class="bg-white shadow-sm border border-slate-100 rounded-lg dark:bg-primary-gray dark:border-neutral-700">
                <button @click.prevent="toggleSection('courses')" class="flex w-full items-center justify-between p-6">
                    <h2 class="text-base font-semibold text-beach-text dark:text-neutral-300">Course Progress & Reading Materials</h2>
                    <ChevronDownIcon :class="['h-5 w-5 text-beach-text-light transition-transform', openSections.courses ? 'rotate-180' : '']"/>
                </button>
                <div v-if="openSections.courses" class="border-t border-gray-200 dark:border-neutral-600">
                    <div v-if="courseProgress.length > 0" class="divide-y divide-gray-200 dark:divide-neutral-600">
                        <div v-for="course in courseProgress" :key="course.title" class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-beach-text dark:text-neutral-300">{{ course.title }}</h3>
                                <span class="text-sm text-beach-text-light dark:text-neutral-400">{{ course.progress }}%</span>
                            </div>
                            <div class="mt-2 h-2 w-full rounded-full bg-gray-200 dark:bg-neutral-600">
                                <div class="h-2 rounded-full bg-beach-teal" :style="{ width: course.progress + '%' }"></div>
                            </div>
                            <div class="mt-2 flex gap-4 text-xs text-beach-text-light dark:text-neutral-400">
                                <span v-if="course.started_at">Started: {{ course.started_at }}</span>
                                <span v-if="course.completed_at">Completed: {{ course.completed_at }}</span>
                                <span>Time: {{ course.time_spent_minutes }} min</span>
                            </div>
                            <div v-if="course.learning_objectives.length > 0" class="mt-3">
                                <p class="text-xs font-medium text-beach-text-light dark:text-neutral-400">Learning Objectives:</p>
                                <ul class="mt-1 list-disc pl-5 text-xs text-beach-text-light dark:text-neutral-400">
                                    <li v-for="(obj, i) in course.learning_objectives.slice(0, 5)" :key="i">{{ obj }}</li>
                                    <li v-if="course.learning_objectives.length > 5" class="text-gray-400">...and {{ course.learning_objectives.length - 5 }} more</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <p v-else class="p-6 text-sm text-beach-text-light dark:text-neutral-400">No course data for this period.</p>
                </div>
            </div>

            <!-- Assessment Results -->
            <div class="bg-white shadow-sm border border-slate-100 rounded-lg dark:bg-primary-gray dark:border-neutral-700">
                <button @click.prevent="toggleSection('assessments')" class="flex w-full items-center justify-between p-6">
                    <h2 class="text-base font-semibold text-beach-text dark:text-neutral-300">Assessment Results</h2>
                    <ChevronDownIcon :class="['h-5 w-5 text-beach-text-light transition-transform', openSections.assessments ? 'rotate-180' : '']"/>
                </button>
                <div v-if="openSections.assessments" class="border-t border-gray-200 dark:border-neutral-600">
                    <div v-if="assessmentResults.length > 0" class="divide-y divide-gray-200 dark:divide-neutral-600">
                        <div v-for="assessment in assessmentResults" :key="assessment.course_title" class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-beach-text dark:text-neutral-300">{{ assessment.course_title }}</h3>
                                <span class="text-sm font-medium text-beach-text-light dark:text-neutral-400">Avg: {{ assessment.average }}%</span>
                            </div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span v-for="(score, key) in assessment.scores" :key="key" class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-neutral-600 dark:text-neutral-300">
                                    {{ formatScoreKey(key) }}: {{ score.score ?? score }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    <p v-else class="p-6 text-sm text-beach-text-light dark:text-neutral-400">No assessment data for this period.</p>
                </div>
            </div>

            <!-- Student Interactions -->
            <div class="bg-white shadow-sm border border-slate-100 rounded-lg dark:bg-primary-gray dark:border-neutral-700">
                <button @click.prevent="toggleSection('interactions')" class="flex w-full items-center justify-between p-6">
                    <h2 class="text-base font-semibold text-beach-text dark:text-neutral-300">Student Interactions</h2>
                    <ChevronDownIcon :class="['h-5 w-5 text-beach-text-light transition-transform', openSections.interactions ? 'rotate-180' : '']"/>
                </button>
                <div v-if="openSections.interactions" class="border-t border-gray-200 dark:border-neutral-600">
                    <div v-if="interactionLog.length > 0" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-neutral-700">
                            <thead class="bg-gray-50 dark:bg-neutral-600">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-beach-text dark:text-neutral-300">Date</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-beach-text dark:text-neutral-300">Question</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-beach-text dark:text-neutral-300">Word Count</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:bg-neutral-500 dark:divide-neutral-600">
                                <tr v-for="(interaction, i) in interactionLog" :key="i">
                                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-beach-text dark:text-primary-gray">{{ interaction.date }}</td>
                                    <td class="px-3 py-4 text-sm text-beach-text-light dark:text-primary-gray max-w-md truncate">{{ interaction.question }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-beach-text-light dark:text-primary-gray">{{ interaction.word_count }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else class="p-6 text-sm text-beach-text-light dark:text-neutral-400">No interactions recorded for this period.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { reactive, computed } from 'vue';
import { ChevronDownIcon } from '@heroicons/vue/24/outline';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    report: Object,
});

const openSections = reactive({
    attendance: true,
    activity: true,
    courses: true,
    assessments: false,
    interactions: false,
});

const toggleSection = (section) => {
    openSections[section] = !openSections[section];
};

const attendanceSummary = computed(() => props.report.metadata?.attendance_summary ?? null);
const attendanceLog = computed(() => props.report.metadata?.attendance_log ?? []);
const activityLog = computed(() => props.report.metadata?.daily_activity_log ?? []);
const courseProgress = computed(() => props.report.metadata?.course_progress ?? []);
const assessmentResults = computed(() => props.report.metadata?.assessment_results ?? []);
const interactionLog = computed(() => props.report.metadata?.interaction_log ?? []);

const formatDuration = (seconds) => {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    if (hours > 0) {
        return `${hours}h ${minutes}m`;
    }
    return `${minutes}m`;
};

const formatScoreKey = (key) => {
    return key.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());
};
</script>
