<template>
    <div class="bg-white border border-slate-100 p-5 rounded-lg shadow-sm dark:bg-primary-gray dark:border-neutral-700">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-beach-teal text-white font-bold text-lg shrink-0">
                {{ student.name.charAt(0).toUpperCase() }}
            </div>
            <div class="min-w-0">
                <p class="font-semibold text-beach-text truncate dark:text-neutral-300">{{ student.name }}</p>
                <p class="text-xs text-beach-text-light dark:text-neutral-500">
                    <span v-if="student.grade">Grade {{ student.grade }}</span>
                    <span v-if="student.grade && student.age"> &middot; </span>
                    <span v-if="student.age">Age {{ student.age }}</span>
                </p>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="rounded-lg border border-gray-200 p-3 dark:border-neutral-600">
                <dt class="text-xs text-beach-text-light dark:text-neutral-500">Today's Time</dt>
                <dd class="mt-0.5 text-sm font-semibold text-beach-text dark:text-neutral-300">{{ student.todays_active_time }}</dd>
            </div>
            <div class="rounded-lg border border-gray-200 p-3 dark:border-neutral-600">
                <dt class="text-xs text-beach-text-light dark:text-neutral-500">Day Streak</dt>
                <dd class="mt-0.5 text-sm font-semibold text-beach-text dark:text-neutral-300">{{ student.current_streak }} day{{ student.current_streak !== 1 ? 's' : '' }}</dd>
            </div>
        </div>

        <div v-if="student.top_course" class="mt-4">
            <div class="flex items-center justify-between text-xs mb-1">
                <span class="text-beach-text-light truncate dark:text-neutral-500">{{ student.top_course.title }}</span>
                <span class="text-beach-text font-medium ml-2 shrink-0 dark:text-neutral-400">{{ student.top_course.progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-neutral-600">
                <div class="bg-beach-teal h-2 rounded-full transition-all" :style="{ width: student.top_course.progress + '%' }"></div>
            </div>
        </div>
        <div v-else class="mt-4">
            <p class="text-xs text-beach-text-light dark:text-neutral-500">No active courses</p>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <Link :href="route('parent.users.show', student.id)" class="text-sm font-medium text-beach-teal hover:underline py-1">
                View Progress
            </Link>
            <span v-if="student.last_active_at" class="text-xs text-beach-text-light dark:text-neutral-500">{{ student.last_active_at }}</span>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    student: Object,
});
</script>
