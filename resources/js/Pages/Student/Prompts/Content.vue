<template>
        <div>
            <!-- Connection Status Indicator -->
            <div v-if="connectionStatus !== 'connected' || isStreaming" class="mb-3 flex items-center text-sm">
                <div class="flex items-center" v-if="connectionStatus === 'connecting'">
                    <svg class="w-4 h-4 mr-2 text-beach-teal animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="dark:text-neutral-500">Connecting...</span>
                </div>
                <div class="flex items-center" v-else-if="isStreaming">
                    <div class="flex space-x-1 mr-2">
                        <div class="w-2 h-2 bg-beach-teal rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="w-2 h-2 bg-beach-teal rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="w-2 h-2 bg-beach-teal rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>
                    <span class="dark:text-neutral-500">Streaming content... ({{ wordCount }} words)</span>
                </div>
                <div class="flex items-center text-red-500" v-else-if="connectionStatus === 'error'">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Connection error -
                        <button @click="retry" class="underline hover:text-red-600">Retry</button>
                    </span>
                </div>
            </div>

            <!-- Content Display -->
            <div class="prose dark:prose-invert max-w-none" v-html="renderedContent"></div>

            <!-- Typing Cursor -->
            <span v-if="isStreaming" class="inline-block w-0.5 h-5 bg-neutral-400 animate-pulse ml-0.5"></span>
        </div>
</template>

<script setup>
import { onMounted, ref, onUnmounted, computed, watch } from 'vue';
import { marked } from 'marked';

const props = defineProps({
    question: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['loading', 'error']);

const message = ref('');
const connectionStatus = ref('disconnected'); // 'disconnected', 'connecting', 'connected', 'error'
const isStreaming = ref(false);
let eventSource = null;

// Compute word count for progress indicator
const wordCount = computed(() => {
    return message.value.trim().split(/\s+/).filter(word => word.length > 0).length;
});

const renderedContent = computed(() => {
    return marked.parse(message.value);
});

onMounted(() => {
  startStream();
});

const startStream = () => {
  if (eventSource) {
    eventSource.close();
  }

  connectionStatus.value = 'connecting';
  isStreaming.value = true;
  emit('loading', true);

  eventSource = new EventSource('/student/prompts/content', {
    withCredentials: true,
  });

  eventSource.onopen = () => {
    connectionStatus.value = 'connected';
  };

  eventSource.onmessage = (event) => {
    // Handle the [DONE] signal from the SDK
    if (event.data === '[DONE]') {
        eventSource.close();
        connectionStatus.value = 'disconnected';
        isStreaming.value = false;
        emit('loading', false);
        return;
    }

    try {
        const data = JSON.parse(event.data);

        // Handle text_delta events from the Laravel AI SDK
        if (data.type === 'text_delta' && data.delta) {
            message.value += data.delta;
        }

        // Handle stream_end event
        if (data.type === 'stream_end') {
            eventSource.close();
            connectionStatus.value = 'disconnected';
            isStreaming.value = false;
            emit('loading', false);
        }
    } catch (e) {
        // Skip unparseable events
    }
  };

  eventSource.onerror = (error) => {
    console.error('EventSource failed:', error);
    connectionStatus.value = 'error';
    isStreaming.value = false;
    eventSource.close();
    emit('error');
    emit('loading', false);
  };
};

const retry = () => {
  message.value = '';
  startStream();
};

// Watch for changes and emit loading state
watch(isStreaming, (newValue) => {
  emit('loading', newValue);
});

onUnmounted(() => {
  if (eventSource) {
    eventSource.close();
  }
});
</script>
