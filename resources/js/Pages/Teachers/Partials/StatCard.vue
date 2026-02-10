<template>
    <div class="bg-white border border-slate-100 p-3 space-y-2 overflow-hidden shadow-sm sm:rounded-lg dark:bg-primary-gray dark:border-neutral-700 dark:shadow-neutral-900/50">
        <div class="flex items-center space-x-4">
            <div class="bg-beach-teal h-12 w-12 rounded-lg flex items-center justify-center shrink-0">
                <component :is="iconComponent" class="w-6 h-6 text-white" />
            </div>
            <div class="min-w-0">
                <p class="font-bold dark:text-neutral-200 truncate" :class="smallValue ? 'text-lg' : 'text-2xl'">{{ value }}</p>
                <div class="text-xs text-beach-text-light truncate dark:text-neutral-400">{{ title }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    ClockIcon,
    ChatBubbleLeftEllipsisIcon,
    BookOpenIcon,
    CreditCardIcon,
} from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const props = defineProps({
    title: String,
    value: [String, Number],
    icon: String,
    smallValue: {
        type: Boolean,
        default: false,
    },
});

const iconMap = {
    clock: ClockIcon,
    chat: ChatBubbleLeftEllipsisIcon,
    book: BookOpenIcon,
    'credit-card': CreditCardIcon,
};

const iconComponent = computed(() => iconMap[props.icon] || ClockIcon);
</script>
