<template>
    <div class="flex flex-col w-full">
        <!-- Label -->
        <label v-if="label" class="mb-1 text-sm font-medium text-gray-700 capitalize dark:text-gray-300">
            {{ label }}
        </label>

        <!-- Select -->
        <Select v-model="modelValue" class="w-full">
            <SelectTrigger :class="triggerClasses">
                <SelectValue placeholder="Select data" />
            </SelectTrigger>

            <SelectContent>
                <SelectGroup>
                    <SelectItem v-for="(item, index) in items" :key="index" :value="item.value">
                        {{ item.label }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>

        <!-- Error message -->
        <p v-if="error" class="mt-1 text-xs text-red-600 dark:text-red-400">
            {{ error }}
        </p>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, ref, watch, computed } from 'vue';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import axios from 'axios';

interface T {
    label: string;
    value: number;
}

const props = defineProps<{
    modelValue: number;
    label?: string;
    data_url: string;
    error?: string;
    size?: 'sm' | 'md' | 'lg';
}>()
const emit = defineEmits<{
    (e: 'update:modelValue', value: number | string): void
}>()

const internalValue = ref(props.modelValue);

watch(() => props.modelValue, (val) => {
    internalValue.value = val;
});

watch(internalValue, (val) => {
    emit('update:modelValue', val);
});




const loading = ref(true);
const items = ref<T[]>([]);

const load = async () => {
    loading.value = true;
    try {
        const res = await axios.get(props.data_url);
        items.value = res.data;
    } catch (err) {
        console.log(err);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    load();
})

// CSS giống input field
const triggerClasses = computed(() => {
    const sizeMap = {
        sm: 'px-2 py-1 text-sm h-8',
        md: 'px-3 py-2 text-base h-10',
        lg: 'px-4 py-3 text-lg h-12',
    };
    const sizeCls = sizeMap[props.size || 'md'];

    const border = props.error
        ? 'border-red-500 dark:border-red-400 focus:ring-red-500 dark:focus:ring-red-400'
        : 'border border-gray-300 dark:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-400';

    const bgText = 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100';

    return `${sizeCls} ${border} ${bgText} rounded-md shadow-sm w-full focus:outline-none focus:ring-2 focus:ring-offset-1 transition-all duration-200`;
});
</script>
