<template>
    <div class="flex flex-col">
        <!-- Label -->
        <label v-if="label" class="mb-1 text-sm font-medium text-gray-700 capitalize dark:text-gray-300">
            {{ label }}
        </label>

        <!-- Textarea -->
        <textarea ref="textarea" :value="modelValue"
            @input="$emit('update:modelValue', ($event.target as HTMLTextAreaElement)?.value)" :class="textareaClasses"
            :placeholder="placeholder"
            class="rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 transition-all duration-200 resize-none"
            rows="4"></textarea>

        <!-- Error message -->
        <p v-if="error" class="mt-1 text-xs text-red-600 dark:text-red-400">
            {{ error }}
        </p>
    </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted } from 'vue';

type TextareaSize = 'sm' | 'md' | 'lg';

const props = defineProps<{
    modelValue?: string | null;
    label?: string;
    error?: string;
    autofocus?: boolean;
    placeholder?: string;
    size?: TextareaSize;
}>()

defineEmits(['update:modelValue']);

const textarea = ref<HTMLTextAreaElement | null>(null);

onMounted(() => {
    if (props.autofocus && textarea.value) {
        textarea.value.focus();
    }
});

defineExpose({
    focus: () => textarea.value?.focus()
});

// Size classes
const sizeClasses: Record<TextareaSize, string> = {
    sm: 'px-2 py-1 text-sm',
    md: 'px-3 py-2 text-base',
    lg: 'px-4 py-3 text-lg',
};

// Base classes with dark mode
const textareaClasses = computed(() => {
    const base = 'rounded-md shadow-sm w-full focus:outline-none focus:ring-2 focus:ring-offset-1 transition-all duration-200';
    const size = sizeClasses[props.size || 'md'];
    const border = props.error
        ? 'border-red-500 dark:border-red-400 focus:ring-red-500 dark:focus:ring-red-400'
        : 'border border-gray-300 dark:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-400';
    const bgText = 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100';
    return `${base} ${size} ${border} ${bgText}`;
});
</script>
