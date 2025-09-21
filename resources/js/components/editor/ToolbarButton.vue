<!-- ToolbarButton.vue -->
<template>
    <button type="button" :class="btnClass" :disabled="disabled" @click="onClick">
        <Icon :icon="icon" class="w-4 h-4" />
    </button>
</template>

<script setup>
import { Icon } from '@iconify/vue'
import { computed } from 'vue'

const props = defineProps({
    icon: { type: String, required: true },
    active: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    onClick: { type: Function, required: true }
})

const activeBtn =
    'px-2 py-1 rounded border border-purple-500 text-purple-600 bg-purple-100 hover:bg-purple-200 dark:border-purple-400 dark:text-purple-400 dark:bg-purple-900 dark:hover:bg-purple-800 transition'
const inactiveBtn =
    'px-2 py-1 rounded border border-gray-300 text-gray-600 bg-white hover:bg-gray-100 dark:border-gray-600 dark:text-gray-400 dark:bg-gray-900 dark:hover:bg-gray-800 transition'

const btnClass = computed(() => (props.active ? activeBtn : inactiveBtn))
</script>
