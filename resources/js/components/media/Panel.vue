<template>
    <div
        class="w-[1000px] h-[600px] bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-300 dark:border-gray-700 flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                Media Manager
            </h2>
            <div class="flex items-center gap-2 no_close_selected">
                <Remove :items="selectItems" :loading="isLoading" @success="fetchData()" />
                <Button size="sm" :loading="isLoading" icon="material-symbols:forward-media" @click="fetchData()" />
                <UploadFiles :loading="isLoading" :dir_path="path" @success="fetchData()" />
            </div>
        </div>

        <!-- Breadcrumb -->
        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
            <Breadcrumb :data="breadcrumbs" :loading="isLoading" @choosePath="choosePath" />
        </div>

        <!-- Content có scroll riêng -->
        <div class="flex-1 overflow-y-auto p-4">
            <List :medias="medias" @choosePath="choosePath" @select="select" />
        </div>
        <div v-if="isChoose" class="flex items-center justify-end border-t border-gray-200 gap-4 p-4">
            <Button @click="emit('close')" color="gray" variant="outline" label="Cancle" />
            <Button color="purple" label="Choose" icon="material-symbols:check-circle-outline"
                @click="successChoose()" />
        </div>
    </div>
</template>


<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue';
import Breadcrumb from './Breadcrumb.vue';
import axios from 'axios';
import Button from '../Button.vue';
import { Media } from '@/types/vnwa';
import List from './List.vue';
import UploadFiles from './UploadFiles.vue';
import Remove from './Remove.vue';
import { toast } from '@/lib/utils';
type TypeFetch = 'image' | 'video' | 'document'
const props = defineProps<{
    isChoose?: boolean
    types?: TypeFetch[]
}>()
const emit = defineEmits(['choose', 'close'])
const medias = ref<Media[]>([]);
const selectItems = ref<Media[]>([]);
const isLoading = ref(false)
const path = ref('');
const segments = computed(() => {
    return path.value.split('/').filter(p => p !== '');
});
const breadcrumbs = computed(() => {
    return segments.value.map((seg, index) => ({
        label: seg,
        value: '/' + segments.value.slice(0, index + 1).join('/')
    }));
});

const fetchData = async () => {
    isLoading.value = true
    await axios.get<Media[]>(route('vnwa.media.load-data'), {
        params: {
            path: path.value,
            types: props.types || []
        }
    }).then(res => {
        medias.value = res.data
    }).catch(err => {
        console.log(err)
    }).finally(() => {
        isLoading.value = false

    })
}

const choosePath = (val: string) => {
    path.value = val;
    fetchData()
}
onMounted(() => {
    fetchData()
})

const select = (items: Media[]) => {
    selectItems.value = items
}
const successChoose = () => {
    const onlyFiles = selectItems.value
        .filter(s => s.type === 'file')
        .map(s => s)

    if (onlyFiles.length === 0) {
        toast("Vui lòng chọn ít nhất 1 file!");
        return;
    }
    emit('choose', onlyFiles)
}
</script>

<style></style>