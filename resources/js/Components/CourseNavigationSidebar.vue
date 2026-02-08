<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sticky top-24">
        <h3 class="text-lg font-semibold text-beach-text dark:text-white mb-4">Course Content</h3>
        <div class="space-y-2">
            <div v-for="prompt in coursePrompts" :key="prompt.id">
                <!-- Week Header (collapsible) -->
                <button
                    @click="toggleWeek(prompt.week_number)"
                    :class="[
                        'w-full text-left p-3 rounded-lg transition-colors flex items-center justify-between',
                        prompt.week_number === weekNumber
                            ? 'bg-teal-50 dark:bg-teal-900/30 text-teal-900 dark:text-teal-100'
                            : canAccessWeek(prompt.week_number)
                                ? 'hover:bg-gray-100 dark:hover:bg-gray-700 text-beach-text dark:text-white'
                                : 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                    ]"
                >
                    <div class="flex items-center">
                        <span :class="[
                            'inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-medium mr-3 shrink-0',
                            prompt.week_number === weekNumber
                                ? 'bg-beach-teal text-white'
                                : isWeekFullyCompleted(prompt.week_number)
                                    ? 'bg-green-500 text-white'
                                    : canAccessWeek(prompt.week_number)
                                        ? 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300'
                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-400'
                        ]">
                            <svg v-if="isWeekFullyCompleted(prompt.week_number)" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span v-else>{{ prompt.week_number }}</span>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium truncate">Week {{ prompt.week_number }}</p>
                            <p class="text-xs text-beach-text-light dark:text-gray-400 truncate">{{ prompt.title }}</p>
                        </div>
                    </div>
                    <svg v-if="canAccessWeek(prompt.week_number)" :class="['w-4 h-4 transition-transform', expandedWeeks.includes(prompt.week_number) ? 'rotate-180' : '']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    <svg v-else class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </button>

                <!-- Days List (expandable) -->
                <div v-if="expandedWeeks.includes(prompt.week_number) && prompt.days?.length" class="ml-6 mt-1 space-y-1">
                    <button
                        v-for="day in prompt.days"
                        :key="day.id"
                        @click="navigateToDay(prompt.week_number, day.day_number)"
                        :disabled="!canAccessDay(prompt.week_number, day.day_number)"
                        :class="[
                            'w-full text-left py-2 px-3 rounded-md text-sm transition-colors',
                            prompt.week_number === weekNumber && day.day_number === dayNumber
                                ? 'bg-teal-100 dark:bg-teal-800 text-teal-900 dark:text-teal-100'
                                : canAccessDay(prompt.week_number, day.day_number)
                                    ? 'hover:bg-gray-100 dark:hover:bg-gray-700 text-beach-text-light dark:text-gray-300'
                                    : 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                        ]"
                    >
                        <div class="flex items-center">
                            <span :class="[
                                'w-4 h-4 rounded-full mr-2 flex items-center justify-center text-[10px]',
                                isDayCompleted(prompt.week_number, day.day_number)
                                    ? 'bg-green-500 text-white'
                                    : prompt.week_number === weekNumber && day.day_number === dayNumber
                                        ? 'bg-beach-teal text-white'
                                        : 'bg-gray-200 dark:bg-gray-600 text-beach-text-light dark:text-gray-400'
                            ]">
                                <svg v-if="isDayCompleted(prompt.week_number, day.day_number)" class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span v-else>{{ day.day_number }}</span>
                            </span>
                            <span class="truncate">{{ day.title }}</span>
                        </div>
                    </button>
                </div>

                <!-- Fallback: No days, show week-level navigation -->
                <div v-else-if="expandedWeeks.includes(prompt.week_number) && !prompt.days?.length" class="ml-6 mt-1">
                    <button
                        @click="navigateToDay(prompt.week_number, 1)"
                        :disabled="!canAccessWeek(prompt.week_number)"
                        :class="[
                            'w-full text-left py-2 px-3 rounded-md text-sm transition-colors',
                            prompt.week_number === weekNumber
                                ? 'bg-teal-100 dark:bg-teal-800 text-teal-900 dark:text-teal-100'
                                : canAccessWeek(prompt.week_number)
                                    ? 'hover:bg-gray-100 dark:hover:bg-gray-700 text-beach-text-light dark:text-gray-300'
                                    : 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                        ]"
                    >
                        View Week Content
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    coursePrompts: {
        type: Array,
        required: true,
    },
    weekNumber: {
        type: Number,
        required: true,
    },
    dayNumber: {
        type: Number,
        required: true,
    },
    userProgress: {
        type: Object,
        required: true,
    },
    courseId: {
        type: Number,
        required: true,
    },
});

// Sidebar state
const expandedWeeks = ref([props.weekNumber]);

// Auto-expand the current week when weekNumber prop changes
watch(() => props.weekNumber, (newWeek) => {
    if (!expandedWeeks.value.includes(newWeek)) {
        expandedWeeks.value.push(newWeek);
    }
});

// Methods
const toggleWeek = (weekNumber) => {
    if (!canAccessWeek(weekNumber)) {
        return;
    }

    const index = expandedWeeks.value.indexOf(weekNumber);
    if (index > -1) {
        expandedWeeks.value.splice(index, 1);
    } else {
        expandedWeeks.value.push(weekNumber);
    }
};

const canAccessWeek = (week) => {
    return week <= (props.userProgress?.current_week || 1);
};

const canAccessDay = (week, day) => {
    if (week < props.userProgress?.current_week) {
        return true;
    }
    if (week === props.userProgress?.current_week) {
        return day <= (props.userProgress?.current_day || 1);
    }
    return false;
};

const isDayCompleted = (week, day) => {
    if (week < props.userProgress?.current_week) {
        return true;
    }
    if (week === props.userProgress?.current_week) {
        return day < props.userProgress?.current_day;
    }
    return false;
};

const isWeekFullyCompleted = (week) => {
    return week < props.userProgress?.current_week;
};

const navigateToDay = (week, day) => {
    if (canAccessDay(week, day)) {
        router.visit(`/student/courses/${props.courseId}/learn/${week}/${day}`);
    }
};
</script>
