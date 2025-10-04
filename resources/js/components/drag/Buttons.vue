<script setup lang="ts">
import { Button as buttonType } from '@/types/vnwa';
import { computed, ref } from 'vue';
import draggableComponent from 'vuedraggable';
import Button from '../Button.vue';
import Tooltip from '../Tooltip.vue';
import SelectFild from '../input/SelectFild.vue';
import InputFild from '../input/InputFild.vue';


const props = defineProps<{
    modelValue: buttonType[];
    label?: string;
}>()

const emit = defineEmits(["update:modelValue"]);

const value = computed({
    get: () => props.modelValue,
    set: (newValue) => emit("update:modelValue", newValue),
});
const expandedIndex = ref<number | null>(null);

const toggleExpand = (index: number) => {
  expandedIndex.value = expandedIndex.value === index ? null : index;
};
const addItem = () => {
    const index = value.value.length + 1;

    value.value.push(
        {
            label: 'Button ' +index,
            to: '/',
            style: 'button1'

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
const styles = [
    { label: 'Gradient Button', value: 'button1' },
    { label: 'Ghost Button', value: 'button2' },
    { label: 'Neon Shadow', value: 'button3' },
    { label: 'Flat Red', value: 'button4' },
    { label: 'Flat Green', value: 'button5' },
    { label: 'Flat Orange', value: 'button6' },
    { label: 'Flat Blue', value: 'button7' },
    { label: 'Outline Blue', value: 'button8' },
    { label: 'Outline Green', value: 'button9' },
    { label: 'White Button', value: 'button10' },
    { label: 'Black Button', value: 'button11' },
    { label: 'Gray Button', value: 'button12' },
];
</script>

<template>
    <div class="space-y-4 border border-gray-300 dark:border-gray-300/50 p-4 rounded-md">
        <div class="flex items-center justify-between">
            {{ label || 'Buttons' }}
            <Button @click="addItem()" size="sm" color="indigo" icon="heroicons:plus" />
        </div>
        <draggableComponent v-model="value" handle=".handle" item-key="index" class="space-y-3">
            <template #item="{ element, index }">
                <div :class="[
                    'rounded-lg shadow bg-white dark:bg-gray-800 p-4',
                    !element.image || !element.name ? 'border border-red-400' : 'border border-gray-300 dark:border-gray-600',
                ]">
                    <div class="flex items-center gap-2 justify-between">
                        <div class="flex items-center justify-start gap-2">
                            <Button icon="material-symbols:drag-handle" type="button" color="blue" size="xs"
                                class="handle cursor-grab" />
                        </div>
                        <div class="vnwa_button">
                            <button type="button" @click="toggleExpand(index)" :class="`text-xs ${element.style}`">{{ element.label
                                }}</button>
                        </div>
                        <div class="flex items-center justify-end gap-6">
                            <Tooltip text="Coppy Item">
                                <Button type="button" color="gray" icon="material-symbols:content-copy-outline"
                                    size="xs" variant="solid" class="rounded-none" @click="copyItem(index)" />
                            </Tooltip>
                            <Tooltip text="Delete">
                                <Button type="button" color="red" icon="material-symbols:delete" size="xs"
                                    variant="outline" class="rounded-full" @click="removeItem(index)" />
                            </Tooltip>
                        </div>
                    </div>

                    <div class="mt-3 space-y-4" v-if="expandedIndex == index">
                        <div class="grid grid-cols-3 gap-4">
                            <SelectFild v-model="element.style" :items="styles" size="sm" label="style"  />
                            <InputFild v-model="element.label"  size="sm" label="Lable"  />
                            <InputFild v-model="element.to"  size="sm" label="URL"  />


                        </div>
                    </div>
                </div>
            </template>
        </draggableComponent>
    </div>
</template>