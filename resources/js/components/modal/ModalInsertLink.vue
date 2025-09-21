<script setup lang="ts">
import LayoutModal from './LayoutModal.vue';
import Button from '../Button.vue';
import InputFild from '../input/InputFild.vue';
import SelectFild from '../input/SelectFild.vue';
import { reactive } from 'vue';

interface T {
    url: string;
    target: string | '_blank' | '_self' | '_parent' | '_top';
}



const emit = defineEmits(['success', 'close']);

const state = reactive<T>({
    url: '',
    target: '_blank',
});

// Các option cho select target
const items = [
    { label: '_blank', value: '_blank' },
    { label: '_self', value: '_self' },
    { label: '_parent', value: '_parent' },
    { label: '_top', value: '_top' },
];

const submit = () => {
    if (!state.url) {
        alert('URL không được để trống'); // Hoặc dùng toast
        return;
    }
    emit('success', state);
};
</script>

<template>
    <LayoutModal title="Insert URL">
        <div class="space-y-3">
            <InputFild label="URL" v-model="state.url" placeholder="https://example.com" />
            <SelectFild label="Target" v-model="state.target" :items="items" />
        </div>

        <template #footer>
            <div class="flex justify-end gap-2">
                <Button size="sm" color="gray" label="Cancel" @click="emit('close')" />
                <Button size="sm" label="Accept" icon="material-symbols:check" @click="submit()" />
            </div>
        </template>
    </LayoutModal>
</template>
