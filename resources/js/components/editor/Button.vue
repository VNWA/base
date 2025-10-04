<template>
    <button type="button" class=" rounded flex items-center justify-center h-7  ps-1  relative  "
        :class="active ? 'dark:bg-purple-500/40 bg-purple-500/80 text-white' : 'bg-none  text-gray-500 '">
        <Icon :icon="icon" :class="`${iconClass || 'text-2xl font-bold'}`" />
        <Icon v-if="isChild" icon="material-symbols:keyboard-arrow-down-rounded" class="text-lg text-gray-500 " />

    </button>
</template>

<script lang="ts" setup>
import { Icon } from '@iconify/vue';

const props = defineProps<{
    icon: string;
    active?: boolean;
    disabled?: boolean;
    isChild?: boolean;
    iconClass?: string;
}>();
</script>

<style></style>