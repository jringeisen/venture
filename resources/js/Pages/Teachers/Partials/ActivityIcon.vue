<template>
    <div
        class="flex items-center justify-center w-8 h-8 rounded-full shrink-0"
        :class="bgClass"
    >
        <component :is="iconComponent" class="w-4 h-4 text-white" />
    </div>
</template>

<script setup>
import {
    AcademicCapIcon,
    CheckCircleIcon,
    ChatBubbleLeftEllipsisIcon,
} from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const props = defineProps({
    type: String,
});

const iconMap = {
    enrollment: AcademicCapIcon,
    completion: CheckCircleIcon,
    questions: ChatBubbleLeftEllipsisIcon,
};

const bgMap = {
    enrollment: 'bg-beach-teal',
    completion: 'bg-green-500',
    questions: 'bg-blue-500',
};

const iconComponent = computed(() => iconMap[props.type] || AcademicCapIcon);
const bgClass = computed(() => bgMap[props.type] || 'bg-beach-teal');
</script>
