<template>
        <div>
            <p class="whitespace-pre-line">{{ message }}</p>
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
