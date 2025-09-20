<template>
    <div class="flex flex-col">
        <!-- Label -->
        <label class="mb-1 text-sm font-medium text-gray-700 capitalize dark:text-gray-300">
            {{ label ? label : 'Image' }}
        </label>

        <!-- Wrapper -->
        <div class="w-40 h-48 border rounded-lg overflow-hidden" :class="[
            error
                ? 'border-red-500 dark:border-red-400'
                : 'border-gray-300 dark:border-gray-600',
            'bg-white dark:bg-gray-800'
        ]">
            <!-- Image button -->
            <button type="button" @click="open" class="w-40 h-40 flex items-center justify-center overflow-hidden
                 bg-gray-50 dark:bg-gray-700
                 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <img v-if="modelValue" :src="storage(modelValue)" class="object-cover w-full h-full" />
                <Icon v-else icon="radix-icons:image" class="text-gray-400 dark:text-gray-300 text-4xl" />
            </button>

            <!-- Input -->
            <input class="h-8 w-full px-2 border-t focus:outline-none focus:ring-0 text-xs
                 dark:bg-gray-800 dark:text-gray-200" :class="[
                    error
                        ? 'border-red-500 dark:border-red-400'
                        : 'border-gray-300 dark:border-gray-600'
                ]" ref="input" :value="modelValue" placeholder="URL Image"
                @input="emit('update:modelValue', ($event.target as HTMLInputElement)?.value)" />
        </div>

        <!-- Error text -->
        <p v-if="error" class="mt-1 text-xs text-red-500 dark:text-red-400">
            {{ error }}
        </p>
    </div>
</template>

<script lang="ts" setup>
import { Icon } from '@iconify/vue'
import { ref } from 'vue'
import { useModal } from 'vue-final-modal'
import ModalMedia from '../modal/ModalMedia.vue'
import { storage } from '@/lib/utils'

const props = defineProps<{
    modelValue?: string;
    error?: string
    label?: string
}>()

const emit = defineEmits(['update:modelValue'])
const input = ref<HTMLInputElement | null>(null)

const { open, close } = useModal({
    component: ModalMedia,
    attrs: {
        types: ['image'],
        onSuccess(items) {
            close()
            emit('update:modelValue', items[0].path)
        },
        onClose() {
            close()
        },
    },
})
</script>
