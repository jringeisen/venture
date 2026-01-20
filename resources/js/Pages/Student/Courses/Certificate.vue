<template>
    <Head :title="`Certificate - ${course.title}`" />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Actions Bar -->
            <div class="mb-6 flex items-center justify-between">
                <button
                    @click="router.visit(`/student/courses/${course.id}`)"
                    class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Course
                </button>
                <div class="flex space-x-3">
                    <button
                        @click="printCertificate"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print Certificate
                    </button>
                    <button
                        @click="shareCertificate"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Share
                    </button>
                </div>
            </div>

            <!-- Certificate -->
            <div ref="certificateRef" class="bg-white rounded-lg shadow-2xl overflow-hidden print:shadow-none">
                <!-- Certificate Border -->
                <div class="p-2 bg-gradient-to-r from-blue-600 via-purple-600 to-blue-600">
                    <div class="bg-white p-8 sm:p-12">
                        <!-- Header -->
                        <div class="text-center mb-8">
                            <div class="flex justify-center mb-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                            </div>
                            <h1 class="text-3xl sm:text-4xl font-serif font-bold text-gray-900 mb-2">Certificate of Completion</h1>
                            <p class="text-gray-500 text-sm uppercase tracking-widest">This certifies that</p>
                        </div>

                        <!-- Recipient Name -->
                        <div class="text-center mb-8">
                            <h2 class="text-4xl sm:text-5xl font-serif font-bold text-blue-600 mb-2">{{ user.name }}</h2>
                            <div class="w-64 h-0.5 bg-gradient-to-r from-transparent via-gray-300 to-transparent mx-auto"></div>
                        </div>

                        <!-- Course Details -->
                        <div class="text-center mb-8">
                            <p class="text-gray-600 text-lg mb-2">has successfully completed the course</p>
                            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">{{ course.title }}</h3>
                            <p class="text-gray-500 max-w-lg mx-auto">{{ course.description }}</p>
                        </div>

                        <!-- Achievement Stats -->
                        <div class="grid grid-cols-3 gap-4 max-w-md mx-auto mb-8">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ course.length_in_weeks }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide">Weeks</div>
                            </div>
                            <div class="text-center border-x border-gray-200">
                                <div class="text-2xl font-bold text-gray-900">{{ averageScore }}%</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide">Avg. Score</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ timeSpent }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide">Time Spent</div>
                            </div>
                        </div>

                        <!-- Dates and Signature -->
                        <div class="flex justify-between items-end pt-8 border-t border-gray-200">
                            <div class="text-left">
                                <p class="text-sm text-gray-500 mb-1">Date of Completion</p>
                                <p class="text-lg font-semibold text-gray-900">{{ completedAt }}</p>
                            </div>
                            <div class="text-center">
                                <div class="w-40 border-b-2 border-gray-300 mb-2"></div>
                                <p class="text-sm text-gray-500">Authorized Signature</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500 mb-1">Certificate ID</p>
                                <p class="text-sm font-mono text-gray-700">{{ certificateId }}</p>
                            </div>
                        </div>

                        <!-- Decorative Elements -->
                        <div class="absolute top-4 left-4 w-24 h-24 opacity-5">
                            <svg viewBox="0 0 100 100" fill="currentColor" class="text-blue-600">
                                <path d="M50 0L61.8 38.2L100 50L61.8 61.8L50 100L38.2 61.8L0 50L38.2 38.2L50 0Z"/>
                            </svg>
                        </div>
                        <div class="absolute bottom-4 right-4 w-24 h-24 opacity-5">
                            <svg viewBox="0 0 100 100" fill="currentColor" class="text-purple-600">
                                <path d="M50 0L61.8 38.2L100 50L61.8 61.8L50 100L38.2 61.8L0 50L38.2 38.2L50 0Z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Certificate Info -->
            <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                <p>This certificate was issued on {{ completedAt }} to recognize the successful completion of the course.</p>
                <p class="mt-1">Verify this certificate using ID: <span class="font-mono">{{ certificateId }}</span></p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    course: Object,
    user: Object,
    completedAt: String,
    startedAt: String,
    averageScore: Number,
    timeSpent: String,
    certificateId: String,
});

const certificateRef = ref(null);

const printCertificate = () => {
    window.print();
};

const shareCertificate = async () => {
    const shareData = {
        title: `Certificate of Completion - ${props.course.title}`,
        text: `I just completed "${props.course.title}"! Check out my certificate.`,
        url: window.location.href,
    };

    if (navigator.share && navigator.canShare(shareData)) {
        try {
            await navigator.share(shareData);
        } catch (err) {
            // User cancelled or error occurred
            copyToClipboard();
        }
    } else {
        copyToClipboard();
    }
};

const copyToClipboard = () => {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Certificate link copied to clipboard!');
    });
};
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #app {
        visibility: visible;
    }
    .print\:shadow-none {
        box-shadow: none !important;
    }
}
</style>
