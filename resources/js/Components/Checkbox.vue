<template>
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="rounded dark:bg-primary-gray border-gray-300 dark:border-primary-dark-gray text-primary-gray shadow-sm focus:ring-primary-gray dark:focus:ring-primary-dark-gray dark:focus:ring-offset-gray-800"
    />
</template>

<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>
