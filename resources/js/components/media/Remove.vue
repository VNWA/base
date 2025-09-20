<template>
    <div>
        <Button size="sm" color="red" :disabled="selectItems.length <= 0" :loading="loading"
            icon="material-symbols:delete-forever-outline" @click="remove" />

    </div>
</template>

<script lang="ts" setup>
import { ItemConfirm, Media } from '@/types/vnwa';
import { useModal } from 'vue-final-modal';
import ModalConfirm from '../modal/ModalConfirm.vue';
import { computed } from 'vue';
import Button from '../Button.vue';

const props = defineProps<{
    items: Media[]
    loading?: boolean
}>()
const emit = defineEmits(['success'])

const selectItems = computed<ItemConfirm[]>(() =>
    props.items.map((item) => ({
        label: item.name,
        value: item.path,
    }))
)

const { open: remove, close } = useModal({
    component: ModalConfirm,
    attrs: {
        title: 'Remove Media',
        url: route('vnwa.media.delete'),
        get items() {
            return selectItems.value; // dùng getter để luôn reactive
        },
        onSuccess() {
            close()
            emit('success')
        },
        onClose() {
            close()
        },
    },
})
</script>


<style></style>