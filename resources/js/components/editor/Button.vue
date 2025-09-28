<template>
    <button type="button" class=" rounded flex items-center justify-center h-7   relative  "
        :class="active ? 'bg-gray-500/20 text-white' : 'bg-none  text-gray-500 '">
        <Icon :icon="icon" class="text-2xl font-bold" />
        <Icon v-if="isChild" icon="material-symbols:keyboard-arrow-down-rounded"
            class="text-lg text-gray-500 " />

    </button>
</template>

<script lang="ts" setup>
import { Icon } from '@iconify/vue';

const props = defineProps<{
    icon: string;
    active?: boolean;
    disabled?: boolean;
    isChild?: boolean;
}>();
</script>

<style></style>