<template>
    <Card :title="title" :loading="loading" class="overflow-hidden">
      <div class="space-y-4 divide-y divide-gray-200 dark:divide-gray-700">

        <!-- Toolbar -->
        <div v-if="$slots.left_toolbar || $slots.toolbar" class="flex items-center justify-between pb-3">
          <div class="flex items-center gap-4">
            <slot name="left_toolbar"></slot>
          </div>
          <div class="flex items-center gap-4">
            <Button
              v-if="isFilter"
              icon="material-symbols:filter-alt-outline"
              :variant="isFormFilter ? 'solid' : 'outline'"
              label="Filter"
              color="gray"
              class="transition-all"
              @click="isFormFilter = !isFormFilter"
            />
            <slot name="toolbar"></slot>
          </div>
        </div>

        <!-- Filter Form -->
        <div
          v-if="isFilter && isFormFilter"
          class="px-4 pt-3 pb-5 space-y-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 shadow-sm"
        >
          <h3 class="font-semibold text-lg text-gray-700 dark:text-gray-200">Filter</h3>
          <div class="space-y-3">
            <slot name="form-filter" />
          </div>
        </div>

        <!-- Content -->
        <div class="pt-4">
          <slot />
        </div>

      </div>
    </Card>
  </template>

  <script lang="ts" setup>
  import { ref } from 'vue';
  import Card from './Card.vue';
  import Button from './Button.vue';

  const props = defineProps<{
    title?: string
    isFilter?: boolean
    loading?: boolean
  }>()

  const isFormFilter = ref(true);
  </script>

  <style scoped>
  /* Optional: thêm transition, hover cho panel và các slot */
  </style>
