<template>
        <div>
            <p class="whitespace-pre-line dark:text-neutral-400">{{ message }}</p>
        </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { ref, onUnmounted } from 'vue';

const props = defineProps({
    question: {
        type: String,
        required: true,
    },
});

const message = ref('');
let eventSource = null;

onMounted(() => {
  startStream();
});

const startStream = () => {
  if (eventSource) {
    eventSource.close();
  }

  eventSource = new EventSource('/student/prompts/content', {
    withCredentials: true,
  });

  eventSource.onmessage = (event) => {
    const data = JSON.parse(event.data);

    if (data.finish_reason === 'stop') {
        eventSource.close();
        return;
    }

    message.value += data.delta.content;
  };

  eventSource.onerror = (error) => {
    console.error('EventSource failed:', error);
    eventSource.close();
  };
};

onUnmounted(() => {
  if (eventSource) {
    eventSource.close();
  }
});
</script>

<style scoped>
.typing-cursor::after {
    content: '|';
    animation: blink 1s infinite;
    color: #3b82f6;
    font-weight: bold;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0; }
}

.prose {
    line-height: 1.7;
}

.prose p {
    margin-bottom: 1rem;
}

.prose ul, .prose ol {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

.prose strong {
    font-weight: 600;
    color: #1f2937;
}

.dark .prose strong {
    color: #f9fafb;
}

.prose em {
    font-style: italic;
    color: #374151;
}

.dark .prose em {
    color: #d1d5db;
}
</style>
