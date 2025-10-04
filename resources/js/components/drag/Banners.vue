<script setup lang="ts">
import { Banner } from '@/types/vnwa';
import { computed, ref } from 'vue';
import InputImage from '../input/InputImage.vue';
import InputFild from '../input/InputFild.vue';
import draggableComponent from 'vuedraggable';
import Button from '../Button.vue';
import Tooltip from '../Tooltip.vue';
import TextareaField from '../input/TextareaField.vue';
import Buttons from './Buttons.vue';


const props = defineProps<{
    modelValue: Banner[];
}>()

const emit = defineEmits(["update:modelValue"]);

const value = computed({
    get: () => props.modelValue,
    set: (newValue) => emit("update:modelValue", newValue),
});


const addItem = () => {
    value.value.push(
        {
            image: '',
            title: '',
            description: '',
            buttons: [],
        }
    );
};

const removeItem = (index: number) => {
    value.value.splice(index, 1);
};
const copyItem = (index: number) => {
    const copiedItem = { ...value.value[index] };
    value.value.splice(index + 2, 0, copiedItem);
};
</script>

<template>
    <div class="space-y-4 border border-gray-300 dark:border-gray-300/50 p-4 rounded-md">
        <div class="flex items-center justify-end">
            <Button @click="addItem()" label="Add" color="indigo" icon="heroicons:plus" />
        </div>
        <draggableComponent v-model="value" handle=".handle" item-key="index" class="space-y-3">
            <template #item="{ element, index }">
                <div :class="[
                    'rounded-lg shadow bg-white dark:bg-gray-800 p-4',
                    !element.image || !element.name ? 'border border-red-400' : 'border border-gray-300 dark:border-gray-600',
                ]">
                    <div class="flex items-center gap-2 justify-between">
                        <div class="flex items-center justify-start gap-2">
                            <Button icon="material-symbols:drag-handle" type="button" color="blue" size="sm"
                                class="handle cursor-grab" />
                        </div>
                        <div class="flex items-center justify-end gap-6">
                            <Tooltip text="Coppy Item">
                                <Button type="button" color="gray" icon="material-symbols:content-copy-outline"
                                    size="sm" variant="solid" class="rounded-none" @click="copyItem(index)" />
                            </Tooltip>
                            <Tooltip text="Delete">
                                <Button type="button" color="red" icon="material-symbols:delete" size="sm"
                                    variant="outline" class="rounded-full" @click="removeItem(index)" />
                            </Tooltip>
                        </div>
                    </div>

                    <div class="mt-3 space-y-4">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-2">
                                <InputImage v-model="element.image" label="Image" />
                            </div>
                            <div class="col-span-6 space-y-4">
                                <InputFild v-model="element.title" label="Title" />
                                <TextareaField v-model="element.description"  label="Description" />
                            </div>
                            <div class="col-span-4">
                                <Buttons v-model="element.buttons" />
                            </div>

                        </div>
                    </div>
                </div>
            </template>
        </draggableComponent>
    </div>
</template>