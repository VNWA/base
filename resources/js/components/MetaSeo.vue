<template>
    <div class="space-y-4 p-4 border  rounded-lg bg-white dark:bg-gray-900">
        <div class="" id="seo_wrap" v-show="localMeta.title && localMeta.desc">

            <div class="seo-preview">


                <div class="existed-seo-meta">

                    <h4 class="page-title-seo text-truncate">
                        {{ localMeta.title }}
                    </h4>

                    <div class="page-url-seo">
                        <p>{{ url }} </p>
                    </div>

                    <div>
                        <span style="color: #70757a;">{{ formattedDate }}
                            - </span>
                        <span class="page-description-seo">
                            {{ localMeta.desc }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-4">

            <div class="col-span-3">
                <InputImage v-model="localMeta.image" label="Meta Image" />
            </div>
            <div class="space-y-4 col-span-9">
                <InputFild v-model="localMeta.title" label="Meta Title" placeholder="Enter meta title" />
                <TextareaField v-model="localMeta.desc" label="Meta Description" placeholder="Enter meta desc" />
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import { Meta } from '@/types/vnwa';
import { reactive, watch, toRefs, computed } from 'vue';
import InputFild from './input/InputFild.vue';
import { config } from 'zod';
import { generateSlug } from '@/lib/utils';
import TextareaField from './input/TextareaField.vue';
import InputImage from './input/InputImage.vue';



// v-model form.meta
const props = defineProps<{
    modelValue: Meta
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: Meta): void
}>();

// local reactive để tránh mutate trực tiếp props
const localMeta = reactive<Meta>({
    title: props.modelValue.title || '',
    desc: props.modelValue.desc || '',
    image: props.modelValue.image || ''
});

// watch localMeta để emit lên parent
watch(
    () => ({ ...localMeta }),
    (val) => {
        emit('update:modelValue', val);
    },
    { deep: true }
);
const appurl = import.meta.env.VITE_APP_URL;
const url = computed(() => {
    if (localMeta.title) {
        const slug = generateSlug(localMeta.title);
        return `${appurl}/${slug}`
    }
    return '';
})
const today = new Date();

const formattedDate = today.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
</script>

<style scoped>
input,
textarea {
    transition: all 0.2s;
}

input:focus,
textarea:focus {
    border-color: #3b82f6;
    outline: none;
}
</style>
