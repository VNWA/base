<script setup lang="ts">
import { ItemConfirm } from '@/types/vnwa';
import LayoutModal from './LayoutModal.vue';
import Button from '../Button.vue';
import { Icon } from '@iconify/vue';
import { ref } from 'vue';
import axios from 'axios';
import { toast } from '@/lib/utils';



const props = defineProps<{
    title: string;
    url: string;
    items: ItemConfirm[];
}>();


const emit = defineEmits(['success', 'close']);
const loading = ref(false);
const submit = async () => {
    loading.value = true;
    await axios.post(props.url, props.items).then(res => {
        toast(res.data.message, 'success');
        emit('success')
    }).catch(err => {
        toast(err.data.message, 'error');

    }).finally(() => {
        loading.value = false
    })
}

</script>

<template>
    <LayoutModal :title="title">
        <ul class="w-full divide-y divide-gray-200 dark:divide-gray-700
         rounded-lg border border-gray-300 dark:border-gray-700
         bg-white dark:bg-gray-800 shadow-sm">
            <li v-for="(item, index) in items" :key="index" class="px-4 py-2 text-sm font-medium
           text-gray-700 dark:text-gray-200
           hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-start gap-2">
                <Icon icon="material-symbols:check-box-outline" class="text-xl" /> <span>{{ item.label }}</span>
            </li>
        </ul>
        <template #footer>
            <Button :loading="loading" size="sm" color="gray" label="Cancel" @click="emit('close')" />
            <Button :loading="loading" size="sm" label="Acpect" icon="material-symbols:check" @click="submit()" />
        </template>
    </LayoutModal>
</template>
