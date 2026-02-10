<template>
    <textarea
        class="border-gray-300 focus:border-beach-teal focus:ring-beach-teal rounded-md shadow-sm dark:focus:border-beach-teal dark:focus:ring-beach-teal dark:border-neutral-700 dark:bg-primary-gray dark:text-neutral-200 dark:placeholder:text-neutral-400"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    ></textarea>
</template>

<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: [String, Number],
        required: true,
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>
