<script setup lang="ts">
import { Media } from '@/types/vnwa';
import Panel from '../media/Panel.vue';
import LayoutModal from './LayoutModal.vue';
type TypeFetch = 'image' | 'video' | 'document'

const props = defineProps<{
    types?: TypeFetch[]
}>()
const emit = defineEmits(['success', 'close']);
const success = (items: Media[]) => {
    emit('success', items);
}

</script>

<template>
    <LayoutModal title="VMedia">
        <Panel :types="types" :isChoose="true" @choose="success" @close="emit('close')" />
    </LayoutModal>
</template>
