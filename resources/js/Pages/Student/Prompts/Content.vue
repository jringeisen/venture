<template>
        <div>
            <!-- Skeleton State -->
            <div v-if="!hasContent && connectionStatus !== 'error'" role="status">
                <div class="flex items-center mb-3">
                    <svg aria-hidden="true" class="w-5 h-5 mr-2 text-gray-200 animate-spin dark:text-neutral-400 fill-beach-teal" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="text-sm dark:text-neutral-500">Generating content...</span>
                </div>
                <div class="animate-pulse space-y-3">
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-3/4"></div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-full"></div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-5/6"></div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-full"></div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-2/3"></div>
                    <div class="mt-6"></div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-full"></div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-4/5"></div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-full"></div>
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-primary-gray w-3/4"></div>
                </div>
                <span class="sr-only">Loading content...</span>
            </div>

            <!-- Error State -->
            <div v-else-if="connectionStatus === 'error'" class="text-red-500 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm">Failed to generate content -
                    <button @click="retry" class="underline hover:text-red-600">Retry</button>
                </span>
            </div>

            <!-- Content Display -->
            <template v-else>
                <div class="prose dark:prose-invert max-w-none" v-html="renderedContent"></div>

                <!-- Typing Cursor -->
                <span v-if="isStreaming" class="inline-block w-0.5 h-5 bg-neutral-400 animate-pulse ml-0.5"></span>
            </template>
        </div>
</template>

<script setup>
import { onMounted, ref, onUnmounted, computed } from 'vue';
import { marked } from 'marked';

const props = defineProps({
    question: {
        type: String,
        required: true,
    },
});

const message = ref('');
const connectionStatus = ref('disconnected'); // 'disconnected', 'connecting', 'connected', 'error'
const isStreaming = ref(false);
let eventSource = null;

// Compute word count for progress indicator
const wordCount = computed(() => {
    return message.value.trim().split(/\s+/).filter(word => word.length > 0).length;
});

const hasContent = computed(() => {
    return message.value.trim().length > 0;
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
        }
    } catch (e) {
        // Skip unparseable events
    }
  };

  eventSource.onerror = (error) => {
    connectionStatus.value = 'error';
    isStreaming.value = false;
    eventSource.close();
  };
};

const retry = () => {
  message.value = '';
  startStream();
};

onUnmounted(() => {
  if (eventSource) {
    eventSource.close();
  }
});
</script>
