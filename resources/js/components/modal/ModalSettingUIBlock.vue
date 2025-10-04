<template>
    <LayoutModal :title="label">
        <div class="p-4">
            <ul>
                <li v-for="item in settings" :key="item.name" class="mb-3">
                    <InputFild v-if="item.type === 'number' || item.type === 'text'" v-model="formData[item.name]"
                        :type="item.type" :label="item.label" :name="item.name" :required="true" />
                    <TextareaField v-else-if="item.type === 'textarea'" v-model="formData[item.name]"
                        :label="item.label" :name="item.name" :required="true" :rows="4" />
                    <InputImage v-else-if="item.type === 'image'" v-model="formData[item.name]" />
                </li>
            </ul>
        </div>
        <template #footer>
            <Button size="sm" color="gray" label="Cancel" @click="emit('close')" />
            <Button size="sm" label="Save" icon="material-symbols:check" @click="submit" />
        </template>
    </LayoutModal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import InputFild from '../input/InputFild.vue';
import LayoutModal from './LayoutModal.vue';
import TextareaField from '../input/TextareaField.vue';
import Button from '../Button.vue';
import InputImage from '../input/InputImage.vue';

interface SettingItem {
    type: 'number' | 'text' | 'textarea' | 'image';

    label: string;
    name: string;
    required?: boolean;
}

const props = defineProps<{
    label: string;
    settings: SettingItem[];
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'save', data: Record<string, any>): void;
}>();

const formData = ref<Record<string, any>>({});

// init formData dựa trên settings
props.settings.forEach(item => formData.value[item.name] = '');
function submit() {
    for (const item of props.settings) {
        if ( !formData.value[item.name]) {
            alert(`Vui lòng nhập ${item.label}`);
            return; // dừng lại, không emit
        }
    }

    emit('save', formData.value);
}
</script>
