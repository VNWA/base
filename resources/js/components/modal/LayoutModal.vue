<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { VueFinalModal } from 'vue-final-modal'

const props = withDefaults(
    defineProps<{
        size?: 'base' | 'full'
        title?: string
        description?: string
    }>(),
    { size: 'base' }
)

import { useVfm } from 'vue-final-modal'

const vfm = useVfm()
const modalId = Symbol('modalId')

function close() {
    vfm.close(modalId)
}
</script>

<template>
    <VueFinalModal class="flex justify-center items-center no_close_selected" :modal-id="modalId">
        <div v-if="$slots.content">
            <slot name="content" />
        </div>
        <div v-else :class="[
            'flex flex-col bg-white dark:bg-gray-900 border dark:border-gray-700 shadow-lg divide-y divide-gray-200 dark:divide-gray-700',
            props.size === 'base'
                ? 'w-[110vh] mx-4  rounded-lg'
                : 'w-screen h-screen m-0 '
        ]">
            <!-- Header with Close -->
            <div class="flex items-start justify-between  border px-4 py-3">
                <div class="flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ props.title || 'VNWA Modal' }}
                    </h3>
                    <p v-if="props.description" class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                        {{ props.description }}
                    </p>
                </div>
                <button @click="close"
                    class=" w-8 h-8 text-white hover:bg-gray-500/50 hover:text-gray-500  flex items-center justify-center rounded-md">
                    <Icon icon="material-symbols:close-rounded" class="text-4xl " />
                </button>
            </div>

            <!-- Main slot -->
            <div class="flex-1 overflow-y-auto py-4 px-6 max-h-[70vh]">
                <slot />
            </div>

            <!-- Footer slot -->
            <div v-if="$slots.footer" class="flex justify-end gap-2 p-4">
                <slot name="footer" />
            </div>
        </div>
    </VueFinalModal>
</template>
