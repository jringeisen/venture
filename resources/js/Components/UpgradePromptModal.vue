<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6">
            <div class="text-center">
                <div class="mx-auto w-12 h-12 bg-beach-teal/10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-beach-teal" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-beach-text dark:text-neutral-200">Upgrade to Unlock</h3>
                <p class="mt-2 text-sm text-beach-text-light dark:text-neutral-400">
                    {{ featureMessage }}
                </p>
                <p class="mt-1 text-xs text-beach-text-light dark:text-neutral-500">
                    You're currently on the <strong>{{ currentPlan }}</strong> plan.
                </p>
            </div>
            <div class="mt-6 flex gap-3 justify-center">
                <a href="/#pricing" class="inline-flex items-center px-4 py-2 bg-beach-teal border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-beach-teal-dark transition-colors">
                    View Plans
                </a>
                <button @click="$emit('close')" class="inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-700 border border-gray-300 dark:border-neutral-900 rounded-md font-semibold text-xs text-gray-700 dark:text-neutral-400 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition-colors">
                    Maybe Later
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { computed } from 'vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    feature: {
        type: String,
        default: '',
    },
    currentPlan: {
        type: String,
        default: 'Starter',
    },
});

defineEmits(['close']);

const featureMessages = {
    ai_questions: 'You\'ve reached your daily AI question limit. Upgrade for more questions per day.',
    courses: 'You\'ve reached your active course limit. Upgrade to enroll in more courses.',
    compliance_reports: 'Compliance reports require a paid plan. Upgrade to generate reports.',
    pdf_download: 'PDF downloads require a paid plan. Upgrade to download compliance reports as PDFs.',
    certificates: 'Course certificates require the Family plan. Upgrade to access certificates.',
    students: 'You\'ve reached your student limit. Upgrade to add more students.',
};

const featureMessage = computed(() => featureMessages[props.feature] || 'This feature requires an upgraded plan.');
</script>
