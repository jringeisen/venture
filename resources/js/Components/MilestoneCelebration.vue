<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity duration-300"
            leave-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click="emit('close')">
                <Transition
                    enter-active-class="transition-all duration-300"
                    leave-active-class="transition-all duration-300"
                    enter-from-class="scale-75 opacity-0"
                    leave-to-class="scale-75 opacity-0"
                >
                    <div v-if="show" class="bg-white dark:bg-primary-gray rounded-2xl p-8 max-w-md mx-4 text-center shadow-2xl" @click.stop>
                        <div class="text-6xl mb-4 animate-bounce">{{ emoji }}</div>
                        <h3 class="text-2xl font-bold text-beach-text dark:text-neutral-200 mb-2">{{ title }}</h3>
                        <p class="text-gray-600 dark:text-neutral-400 mb-6">{{ message }}</p>
                        <div class="flex justify-center space-x-2 mb-6">
                            <span v-for="i in 5" :key="i" class="w-3 h-3 rounded-full animate-pulse" :class="['bg-yellow-400', 'bg-pink-400', 'bg-blue-400', 'bg-green-400', 'bg-purple-400'][i-1]" :style="{ animationDelay: `${i * 100}ms` }"></span>
                        </div>
                        <button
                            @click="emit('close')"
                            class="px-6 py-2 bg-beach-teal text-white font-medium rounded-lg hover:bg-beach-teal-dark transition-colors"
                        >
                            Keep Learning!
                        </button>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    emoji: {
        type: String,
        default: '',
    },
    title: {
        type: String,
        default: '',
    },
    message: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['close']);
</script>
