<template>
    <div class="flex flex-col">

        <label :for="id" v-if="label" class="mb-1 text-sm font-medium text-gray-700 capitalize dark:text-gray-300">
            {{ label }}
        </label>

        <!-- Input -->
        <input :id="id" ref="input" :value="modelValue" :type="type || 'text'"
            @input="$emit('update:modelValue', ($event.target as HTMLInputElement)?.value)" :class="inputClasses"
            :placeholder="placeholder"
            class="rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 transition-all duration-200" />

        <!-- Error message -->
        <p v-if="error" class="mt-1 text-xs text-red-600 dark:text-red-400">
            {{ error }}
        </p>
    </div>
</template>

<script lang="ts" setup>
import { useId } from 'reka-ui';
import { ref, computed, onMounted } from 'vue';
const id = useId()
type InputSize = 'sm' | 'md' | 'lg';

const props = defineProps<{
    modelValue: number | string | 'null';
    label?: string;
    error?: string;
    autofocus?: boolean;
    placeholder?: string;
    size?: InputSize;
    type?: 'number' | 'text' | 'email'
}>()

defineEmits(['update:modelValue']);

const input = ref<HTMLInputElement | null>(null);

onMounted(() => {
    if (props.autofocus && input.value) {
        input.value.focus();
    }
});

defineExpose({
    focus: () => input.value?.focus()
});

// Size classes
const sizeClasses: Record<InputSize, string> = {
    sm: 'px-2 py-1 text-sm',
    md: 'px-3 py-2 text-base',
    lg: 'px-4 py-3 text-lg',
};

// Base classes with dark mode
const inputClasses = computed(() => {
    const base = 'rounded-md shadow-sm w-full focus:outline-none focus:ring-2 focus:ring-offset-1 transition-all duration-200';
    const size = sizeClasses[props.size || 'md'];
    const border = props.error
        ? 'border-red-500 dark:border-red-400 focus:ring-red-500 dark:focus:ring-red-400'
        : 'border border-gray-300 dark:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-400';
    const bgText = 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100';
    return `${base} ${size} ${border} ${bgText}`;
});
</script>
