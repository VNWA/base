<template>
    <button
      :disabled="loading || disabled"
      :class="buttonClasses"
      class="flex items-center justify-center gap-2 font-medium rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2"
    >
      <template v-if="icon && !loading">
        <Icon :icon="icon" :class="iconClasses" />
      </template>
      <Loader2 v-if="loading" class="animate-spin" :class="iconClasses" />
      <span v-if="label" >{{ label }}</span>
    </button>
  </template>

  <script lang="ts" setup>
  import { computed } from 'vue';
  import { Loader2 } from 'lucide-vue-next';
  import { Icon } from '@iconify/vue';

  type ButtonVariant =
    | 'link'
    | 'solid'
    | 'outline'
    | 'soft'
    | 'subtle'
    | 'ghost';

  type ButtonColor =
    | 'blue'
    | 'red'
    | 'green'
    | 'purple'
    | 'yellow'
    | 'white'
    | 'gray'
    | 'pink'
    | 'indigo';

  type ButtonSize = 'xs' | 'sm' | 'md' | 'lg' | 'xl';

  const props = defineProps<{
    icon?: string;
    label?: string;
    loading?: boolean;
    disabled?: boolean;
    variant?: ButtonVariant;
    color?: ButtonColor;
    size?: ButtonSize;
  }>();

  const variant = props.variant || 'solid';
  const color = props.color || 'blue';
  const size = props.size || 'md';

  // Sizes
  const sizes: Record<ButtonSize, string> = {
    xs: 'px-2 py-1 text-xs',
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-4 py-2 text-base',
    lg: 'px-5 py-3 text-lg',
    xl: 'px-6 py-4 text-xl',
  };

  // Solid
  const solidColors: Record<ButtonColor, string> = {
    blue: 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 dark:text-gray-900',
    red: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-600 dark:text-gray-900',
    green: 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600 dark:text-gray-900',
    purple: 'bg-purple-600 text-white hover:bg-purple-700 focus:ring-purple-500 dark:bg-purple-500 dark:hover:bg-purple-600 dark:text-gray-900',
    yellow: 'bg-yellow-400 text-black hover:bg-yellow-500 focus:ring-yellow-300 dark:bg-yellow-500  dark:hover:bg-yellow-600 dark:text-gray-900',
    white: 'bg-white text-black hover:bg-gray-100 focus:ring-gray-300 dark:bg-gray-700  dark:hover:bg-gray-600 dark:text-gray-900',
    gray: 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600 dark:text-gray-900',
    pink: 'bg-pink-500 text-white hover:bg-pink-600 focus:ring-pink-400 dark:bg-pink-400 dark:hover:bg-pink-500 dark:text-gray-900',
    indigo: 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:text-gray-900',
  };

  // Outline
  const outlineColors: Record<ButtonColor, string> = {
    blue: 'text-blue-600 border border-blue-600 hover:bg-blue-50 focus:ring-blue-500 dark:text-blue-400 dark:border-blue-400 dark:hover:bg-blue-900',
    red: 'text-red-600 border border-red-600 hover:bg-red-50 focus:ring-red-500 dark:text-red-400 dark:border-red-400 dark:hover:bg-red-900',
    green: 'text-green-600 border border-green-600 hover:bg-green-50 focus:ring-green-500 dark:text-green-400 dark:border-green-400 dark:hover:bg-green-900',
    purple: 'text-purple-600 border border-purple-600 hover:bg-purple-50 focus:ring-purple-500 dark:text-purple-400 dark:border-purple-400 dark:hover:bg-purple-900',
    yellow: 'text-yellow-400 border border-yellow-400 hover:bg-yellow-50 focus:ring-yellow-300 dark:text-yellow-300 dark:border-yellow-300 dark:hover:bg-yellow-900',
    gray: 'text-gray-600 border border-gray-600 hover:bg-gray-50 focus:ring-gray-500 dark:text-gray-400 dark:border-gray-400 dark:hover:bg-gray-800',
    pink: 'text-pink-500 border border-pink-500 hover:bg-pink-50 focus:ring-pink-400 dark:text-pink-400 dark:border-pink-400 dark:hover:bg-pink-900',
    indigo: 'text-indigo-600 border border-indigo-600 hover:bg-indigo-50 focus:ring-indigo-500 dark:text-indigo-400 dark:border-indigo-400 dark:hover:bg-indigo-900',
    white: 'text-white border border-white hover:bg-gray-50 focus:ring-gray-300 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-700',
  };

  // Soft
  const softColors: Record<ButtonColor, string> = {
    blue: 'bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800',
    red: 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800',
    green: 'bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800',
    purple: 'bg-purple-100 text-purple-700 hover:bg-purple-200 dark:bg-purple-900 dark:text-purple-300 dark:hover:bg-purple-800',
    yellow: 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800',
    gray: 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800',
    pink: 'bg-pink-100 text-pink-700 hover:bg-pink-200 dark:bg-pink-900 dark:text-pink-300 dark:hover:bg-pink-800',
    indigo: 'bg-indigo-100 text-indigo-700 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800',
    white: 'bg-white text-gray-800 hover:bg-gray-50 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600',
  };

  // Subtle
  const subtleColors: Record<ButtonColor, string> = {
    blue: 'text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300',
    red: 'text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300',
    green: 'text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300',
    purple: 'text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300',
    yellow: 'text-yellow-400 hover:text-yellow-500 dark:text-yellow-300 dark:hover:text-yellow-400',
    gray: 'text-gray-600 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300',
    pink: 'text-pink-500 hover:text-pink-600 dark:text-pink-400 dark:hover:text-pink-300',
    indigo: 'text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300',
    white: 'text-white hover:text-gray-100 dark:text-gray-200 dark:hover:text-white',
  };

  // Ghost
  const ghostColors: Record<ButtonColor, string> = {
    blue: 'bg-transparent text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900',
    red: 'bg-transparent text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900',
    green: 'bg-transparent text-green-600 hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-900',
    purple: 'bg-transparent text-purple-600 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-purple-900',
    yellow: 'bg-transparent text-yellow-400 hover:bg-yellow-50 dark:text-yellow-300 dark:hover:bg-yellow-900',
    gray: 'bg-transparent text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800',
    pink: 'bg-transparent text-pink-500 hover:bg-pink-50 dark:text-pink-400 dark:hover:bg-pink-900',
    indigo: 'bg-transparent text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900',
    white: 'bg-transparent text-white hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700',
  };

  // Link
  const linkColors: Record<ButtonColor, string> = {
    blue: 'text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300',
    red: 'text-red-600 underline hover:text-red-800 dark:text-red-400 dark:hover:text-red-300',
    green: 'text-green-600 underline hover:text-green-800 dark:text-green-400 dark:hover:text-green-300',
    purple: 'text-purple-600 underline hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300',
    yellow: 'text-yellow-400 underline hover:text-yellow-500 dark:text-yellow-300 dark:hover:text-yellow-400',
    gray: 'text-gray-600 underline hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300',
    pink: 'text-pink-500 underline hover:text-pink-700 dark:text-pink-400 dark:hover:text-pink-300',
    indigo: 'text-indigo-600 underline hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300',
    white: 'text-white underline hover:text-gray-100 dark:text-gray-200 dark:hover:text-white',
  };

  // Computed Classes
  const buttonClasses = computed(() => {
    let base = '';
    switch (variant) {
      case 'solid':
        base = solidColors[color];
        break;
      case 'outline':
        base = outlineColors[color];
        break;
      case 'soft':
        base = softColors[color];
        break;
      case 'subtle':
        base = subtleColors[color];
        break;
      case 'ghost':
        base = ghostColors[color];
        break;
      case 'link':
        base = linkColors[color];
        break;
    }
    return `${base} ${sizes[size]} ${props.disabled ? 'opacity-50 cursor-not-allowed' : ''}`;
  });

  // Icon sizes
  const iconClasses = computed(() => {
    const map = { xs: 'w-3 h-3', sm: 'w-4 h-4', md: 'w-5 h-5', lg: 'w-6 h-6', xl: 'w-7 h-7' };
    return map[size];
  });
  </script>
