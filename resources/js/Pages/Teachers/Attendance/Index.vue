<template>
    <Head title="Attendance" />

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-beach-text dark:text-neutral-300">Attendance</h1>
                <p class="text-sm text-beach-text-light dark:text-neutral-400">Track and manage student attendance records.</p>
            </div>
            <div v-if="students.length > 1">
                <select
                    v-model="selectedStudent"
                    @change="navigateToStudent"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-beach-teal focus:ring-beach-teal dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-300 sm:text-sm"
                >
                    <option v-for="student in students" :key="student.id" :value="student.id">
                        {{ student.name }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Month Navigation -->
        <div class="bg-white shadow-sm border border-slate-100 rounded-lg dark:bg-primary-gray dark:border-neutral-700">
            <div class="flex items-center justify-between p-4">
                <button @click="prevMonth" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 text-beach-text-light dark:text-neutral-400">
                    <ChevronLeftIcon class="h-5 w-5" />
                </button>
                <h2 class="text-lg font-semibold text-beach-text dark:text-neutral-300">
                    {{ monthName }} {{ currentYear }}
                </h2>
                <button @click="nextMonth" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 text-beach-text-light dark:text-neutral-400">
                    <ChevronRightIcon class="h-5 w-5" />
                </button>
            </div>

            <!-- Calendar Grid -->
            <div class="border-t border-gray-200 dark:border-neutral-600">
                <!-- Day Headers -->
                <div class="grid grid-cols-7 text-center text-xs font-medium text-beach-text-light dark:text-neutral-500 border-b border-gray-200 dark:border-neutral-600">
                    <div v-for="day in weekDays" :key="day" class="py-2">{{ day }}</div>
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7">
                    <div
                        v-for="(day, index) in calendarDays"
                        :key="index"
                        class="min-h-[80px] sm:min-h-[100px] border-b border-r border-gray-100 dark:border-neutral-700 p-1 sm:p-2"
                        :class="{
                            'bg-gray-50 dark:bg-neutral-800/50': !day.isCurrentMonth,
                            'cursor-pointer hover:bg-gray-50 dark:hover:bg-neutral-700/50': day.isCurrentMonth,
                        }"
                        @click="day.isCurrentMonth ? handleDayClick(day) : null"
                    >
                        <div class="flex items-start justify-between">
                            <span
                                class="text-xs sm:text-sm"
                                :class="{
                                    'text-gray-300 dark:text-neutral-600': !day.isCurrentMonth,
                                    'text-beach-text dark:text-neutral-300': day.isCurrentMonth && !day.isToday,
                                    'font-bold text-beach-teal': day.isToday,
                                }"
                            >
                                {{ day.dayNumber }}
                            </span>
                        </div>
                        <div v-if="day.attendance" class="mt-1">
                            <span
                                class="inline-block w-full text-center rounded px-1 py-0.5 text-[10px] sm:text-xs font-medium truncate"
                                :class="typeColorClass(day.attendance.type)"
                            >
                                {{ day.attendance.label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Year-to-Date Summary -->
        <div v-if="yearSummary">
            <div class="flex items-center gap-2 mb-3">
                <h3 class="text-sm font-semibold text-beach-text dark:text-neutral-300">Year-to-Date</h3>
                <span class="text-xs text-beach-text-light dark:text-neutral-500">{{ schoolYearLabel }}</span>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Present</dt>
                    <dd class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">{{ yearSummary.present }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Field Trips</dt>
                    <dd class="mt-1 text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ yearSummary.field_trip }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Offline Days</dt>
                    <dd class="mt-1 text-2xl font-semibold text-purple-600 dark:text-purple-400">{{ yearSummary.offline_day }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Excused</dt>
                    <dd class="mt-1 text-2xl font-semibold text-orange-600 dark:text-orange-400">{{ yearSummary.excused_absence }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700 col-span-2 sm:col-span-1">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Total Attendance</dt>
                    <dd class="mt-1 text-2xl font-semibold text-beach-text dark:text-neutral-300">{{ yearSummary.total_attendance_days }}</dd>
                </div>
            </div>
        </div>

        <!-- Monthly Summary -->
        <div v-if="summary">
            <h3 class="text-sm font-semibold text-beach-text dark:text-neutral-300 mb-3">Monthly</h3>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Present</dt>
                    <dd class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">{{ summary.present }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Field Trips</dt>
                    <dd class="mt-1 text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ summary.field_trip }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Offline Days</dt>
                    <dd class="mt-1 text-2xl font-semibold text-purple-600 dark:text-purple-400">{{ summary.offline_day }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Excused</dt>
                    <dd class="mt-1 text-2xl font-semibold text-orange-600 dark:text-orange-400">{{ summary.excused_absence }}</dd>
                </div>
                <div class="rounded-lg bg-white p-4 shadow-sm border border-slate-100 dark:bg-primary-gray dark:border-neutral-700 col-span-2 sm:col-span-1">
                    <dt class="text-xs text-beach-text-light dark:text-neutral-500">Total Attendance</dt>
                    <dd class="mt-1 text-2xl font-semibold text-beach-text dark:text-neutral-300">{{ summary.total_attendance_days }}</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Attendance Modal -->
    <Modal :show="showModal" max-width="lg" @close="closeModal">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-beach-text dark:text-neutral-300 mb-4">
                {{ editingAttendance ? 'Edit Attendance' : 'Add Attendance' }}
            </h3>
            <p class="text-sm text-beach-text-light dark:text-neutral-400 mb-4">
                {{ selectedDate }}
            </p>

            <div class="space-y-4">
                <div>
                    <InputLabel for="type" value="Type" />
                    <select
                        id="type"
                        v-model="modalForm.type"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-beach-teal focus:ring-beach-teal dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-300 sm:text-sm"
                    >
                        <option v-for="t in attendanceTypes" :key="t.value" :value="t.value">{{ t.label }}</option>
                    </select>
                    <InputError :message="modalForm.errors.type" class="mt-1" />
                </div>

                <div>
                    <InputLabel for="notes" value="Notes (optional)" />
                    <TextareaInput
                        id="notes"
                        v-model="modalForm.notes"
                        class="mt-1 block w-full"
                        rows="3"
                        placeholder="e.g. Science museum field trip"
                    />
                    <InputError :message="modalForm.errors.notes" class="mt-1" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                <DangerButton v-if="editingAttendance" @click="deleteAttendance" :disabled="deleteForm.processing">
                    Delete
                </DangerButton>
                <PrimaryButton @click="saveAttendance" :disabled="modalForm.processing">
                    {{ modalForm.processing ? 'Saving...' : 'Save' }}
                </PrimaryButton>
            </div>
        </div>
    </Modal>

    <!-- Daily Overview Modal -->
    <Modal :show="showOverview" max-width="2xl" @close="closeOverview">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-beach-text dark:text-neutral-300">
                        Daily Overview
                    </h3>
                    <p class="text-sm text-beach-text-light dark:text-neutral-400">{{ selectedDate }}</p>
                </div>
                <div v-if="overviewAttendance" class="flex gap-2">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" :class="typeColorClass(overviewAttendance.type)">
                        {{ overviewAttendance.label }}
                    </span>
                    <button @click="editFromOverview" class="text-beach-teal hover:text-beach-teal-dark text-sm font-medium">
                        Edit
                    </button>
                </div>
            </div>

            <div v-if="overviewAttendance?.notes" class="mb-4 p-3 bg-gray-50 dark:bg-neutral-800 rounded-lg">
                <p class="text-sm text-beach-text-light dark:text-neutral-400">
                    <span class="font-medium text-beach-text dark:text-neutral-300">Notes:</span> {{ overviewAttendance.notes }}
                </p>
            </div>

            <!-- Loading State -->
            <div v-if="loadingOverview" class="space-y-4">
                <div class="animate-pulse space-y-3">
                    <div class="h-4 bg-gray-200 dark:bg-neutral-700 rounded w-1/3"></div>
                    <div class="h-10 bg-gray-200 dark:bg-neutral-700 rounded"></div>
                    <div class="h-4 bg-gray-200 dark:bg-neutral-700 rounded w-1/4"></div>
                    <div class="h-10 bg-gray-200 dark:bg-neutral-700 rounded"></div>
                </div>
            </div>

            <!-- Overview Content -->
            <div v-else-if="dailyOverview" class="space-y-4">
                <!-- Active Time -->
                <div class="p-3 bg-gray-50 dark:bg-neutral-800 rounded-lg">
                    <h4 class="text-sm font-medium text-beach-text dark:text-neutral-300 mb-1">Active Time</h4>
                    <p class="text-xl font-semibold text-beach-teal">{{ formatDuration(dailyOverview.active_time_seconds) }}</p>
                </div>

                <!-- Courses -->
                <div v-if="dailyOverview.courses.length > 0">
                    <h4 class="text-sm font-medium text-beach-text dark:text-neutral-300 mb-2">Courses Studied</h4>
                    <div class="space-y-2">
                        <div v-for="course in dailyOverview.courses" :key="course.title" class="flex items-center justify-between p-2 bg-gray-50 dark:bg-neutral-800 rounded-lg">
                            <span class="text-sm text-beach-text dark:text-neutral-300">{{ course.title }}</span>
                            <span class="text-xs text-beach-text-light dark:text-neutral-400">{{ formatDuration(course.duration_seconds) }}</span>
                        </div>
                    </div>
                </div>
                <p v-else class="text-sm text-beach-text-light dark:text-neutral-400">No course sessions recorded.</p>

                <!-- Questions -->
                <div v-if="dailyOverview.questions.length > 0">
                    <h4 class="text-sm font-medium text-beach-text dark:text-neutral-300 mb-2">
                        Questions Asked ({{ dailyOverview.question_count }})
                    </h4>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        <div v-for="(q, i) in dailyOverview.questions" :key="i" class="p-2 bg-gray-50 dark:bg-neutral-800 rounded-lg">
                            <p class="text-sm text-beach-text dark:text-neutral-300 truncate">{{ q.question }}</p>
                            <p class="text-xs text-beach-text-light dark:text-neutral-400">{{ q.time }}</p>
                        </div>
                    </div>
                </div>
                <p v-else class="text-sm text-beach-text-light dark:text-neutral-400">No questions asked.</p>
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeOverview">Close</SecondaryButton>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextareaInput from '@/Components/TextareaInput.vue';

defineOptions({
    layout: AuthenticatedLayout,
});

const props = defineProps({
    students: Array,
    selectedStudentId: Number,
    year: Number,
    month: Number,
    attendance: Object,
    summary: Object,
    yearSummary: Object,
    schoolYearLabel: String,
    attendanceTypes: Array,
});

const selectedStudent = ref(props.selectedStudentId);
const currentYear = ref(props.year);
const currentMonth = ref(props.month);

const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

const monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December',
];

const monthName = computed(() => monthNames[currentMonth.value - 1]);

const calendarDays = computed(() => {
    const year = currentYear.value;
    const month = currentMonth.value;
    const firstDay = new Date(year, month - 1, 1);
    const lastDay = new Date(year, month, 0);
    const daysInMonth = lastDay.getDate();
    const startWeekday = firstDay.getDay();

    const days = [];

    // Previous month padding
    const prevLastDay = new Date(year, month - 1, 0).getDate();
    for (let i = startWeekday - 1; i >= 0; i--) {
        days.push({
            dayNumber: prevLastDay - i,
            isCurrentMonth: false,
            isToday: false,
            dateString: null,
            attendance: null,
        });
    }

    // Current month days
    const today = new Date();
    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
        const isToday = today.getFullYear() === year && today.getMonth() + 1 === month && today.getDate() === d;

        days.push({
            dayNumber: d,
            isCurrentMonth: true,
            isToday,
            dateString: dateStr,
            attendance: props.attendance[dateStr] || null,
        });
    }

    // Next month padding
    const remaining = 7 - (days.length % 7);
    if (remaining < 7) {
        for (let i = 1; i <= remaining; i++) {
            days.push({
                dayNumber: i,
                isCurrentMonth: false,
                isToday: false,
                dateString: null,
                attendance: null,
            });
        }
    }

    return days;
});

const typeColorClass = (type) => {
    return {
        present: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        field_trip: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
        offline_day: 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
        excused_absence: 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
    }[type] || 'bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300';
};

const navigateMonth = (year, month) => {
    router.get(route('parent.attendance.index'), {
        student_id: selectedStudent.value,
        year,
        month,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const prevMonth = () => {
    let y = currentYear.value;
    let m = currentMonth.value - 1;
    if (m < 1) { m = 12; y--; }
    currentYear.value = y;
    currentMonth.value = m;
    navigateMonth(y, m);
};

const nextMonth = () => {
    let y = currentYear.value;
    let m = currentMonth.value + 1;
    if (m > 12) { m = 1; y++; }
    currentYear.value = y;
    currentMonth.value = m;
    navigateMonth(y, m);
};

const navigateToStudent = () => {
    router.get(route('parent.attendance.index'), {
        student_id: selectedStudent.value,
        year: currentYear.value,
        month: currentMonth.value,
    }, {
        preserveState: false,
    });
};

// Add/Edit Modal
const showModal = ref(false);
const editingAttendance = ref(null);
const selectedDate = ref('');

const modalForm = useForm({
    student_id: props.selectedStudentId,
    date: '',
    type: 'present',
    notes: '',
});

const deleteForm = useForm({});

const handleDayClick = (day) => {
    selectedDate.value = day.dateString;

    if (day.attendance) {
        showOverviewModal(day);
    } else {
        openAddModal(day);
    }
};

const openAddModal = (day) => {
    editingAttendance.value = null;
    modalForm.student_id = selectedStudent.value;
    modalForm.date = day.dateString;
    modalForm.type = 'present';
    modalForm.notes = '';
    showModal.value = true;
};

const openEditModal = (attendance, dateString) => {
    editingAttendance.value = attendance;
    selectedDate.value = dateString;
    modalForm.type = attendance.type;
    modalForm.notes = attendance.notes || '';
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingAttendance.value = null;
    modalForm.clearErrors();
};

const saveAttendance = () => {
    if (editingAttendance.value) {
        modalForm.put(route('parent.attendance.update', editingAttendance.value.id), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        modalForm.post(route('parent.attendance.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

const deleteAttendance = () => {
    if (editingAttendance.value) {
        deleteForm.delete(route('parent.attendance.destroy', editingAttendance.value.id), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

// Daily Overview Modal
const showOverview = ref(false);
const loadingOverview = ref(false);
const dailyOverview = ref(null);
const overviewAttendance = ref(null);

const showOverviewModal = (day) => {
    overviewAttendance.value = day.attendance;
    dailyOverview.value = null;
    loadingOverview.value = true;
    showOverview.value = true;

    axios.get(route('parent.attendance.daily-summary'), {
        params: {
            student_id: selectedStudent.value,
            date: day.dateString,
        }
    }).then((response) => {
        dailyOverview.value = response.data;
    }).finally(() => {
        loadingOverview.value = false;
    });
};

const closeOverview = () => {
    showOverview.value = false;
    dailyOverview.value = null;
    overviewAttendance.value = null;
};

const editFromOverview = () => {
    const attendance = overviewAttendance.value;
    const dateStr = selectedDate.value;
    closeOverview();
    openEditModal(attendance, dateStr);
};

const formatDuration = (seconds) => {
    if (!seconds || seconds === 0) {
        return '0m';
    }
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    if (hours > 0) {
        return `${hours}h ${minutes}m`;
    }
    return `${minutes}m`;
};
</script>
