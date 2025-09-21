<template>
    <div class="flex flex-col w-full">
      <!-- Label -->
      <label v-if="label" :for="id" class="mb-1 text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">
        {{ label }}
      </label>

      <!-- Select -->
      <select
        :id="id"
        :value="modelValue"
        @change="onChange"
        :class="selectClasses"
      >
        <option v-for="(item, index) in items" :key="index" :value="item.value">
          {{ item.label }}
        </option>
      </select>

      <!-- Error message -->
      <p v-if="error" class="mt-1 text-xs text-red-600 dark:text-red-400">
        {{ error }}
      </p>
    </div>
  </template>

  <script lang="ts" setup>
  import { ref, computed } from 'vue'
  import { useId } from 'reka-ui'

  type InputSize = 'sm' | 'md' | 'lg'

  interface Option {
    label: string
    value: string | number
  }

  const id = useId()

  const props = defineProps<{
    modelValue: string | number
    items: Option[]
    label?: string
    error?: string
    size?: InputSize
  }>()

  const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number): void
  }>()

  // Xử lý change event
  const onChange = (e: Event) => {
    const target = e.target as HTMLSelectElement | null
    if (!target) return
    emit('update:modelValue', target.value)
  }

  // Map size => padding + height + font-size
  const sizeClassesMap: Record<InputSize, string> = {
    sm: 'px-2 py-1 text-sm h-8',
    md: 'px-3 py-2 text-base h-10',
    lg: 'px-4 py-3 text-lg h-12',
  }

  // Lớp CSS chính
  const selectClasses = computed(() => {
    const sizeCls = sizeClassesMap[props.size || 'md']
    const borderCls = props.error
      ? 'border-red-500 dark:border-red-400 focus:ring-red-500 dark:focus:ring-red-400'
      : 'border border-gray-300 dark:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-400'
    const bgText = 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100'
    return `${sizeCls} ${borderCls} ${bgText} rounded-md shadow-sm w-full focus:outline-none focus:ring-2 focus:ring-offset-1 transition-all duration-200`
  })
  </script>
