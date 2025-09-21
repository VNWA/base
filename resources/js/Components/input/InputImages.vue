<template>
  <div>

  </div>
</template>

<script lang="ts" setup>
import { Icon } from '@iconify/vue'
import { ref } from 'vue'
import { useModal } from 'vue-final-modal'
import ModalMedia from '../modal/ModalMedia.vue'
import { storage } from '@/lib/utils'

const props = defineProps<{
    modelValue?: string[];
    error?: string
    label?: string
    grid:1|2|3|4|5|6|7|8
}>()

const emit = defineEmits(['update:modelValue'])

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

<style>

</style>
