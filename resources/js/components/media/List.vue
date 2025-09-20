<template>
    <div class="relative">
        <ul class="flex items-center justify-start gap-10 flex-wrap">
            <li v-for="(item, index) in medias" :key="index" class="no_close_selected">
                <ContextMenu :id="index">
                    <ContextMenuTrigger>
                        <div class="w-[200px] h-[250px] rounded-lg border-2 cursor-pointer transition-all duration-200
         bg-white dark:bg-gray-900 shadow-sm hover:shadow-lg overflow-hidden flex flex-col"
                            @dblclick="openFolder(item)" @click="addSelect(index, item)"
                            :class="isSelect(index)
                                ? 'border-sky-500 ring-2 ring-sky-300 dark:ring-sky-600'
                                : 'border-dashed border-gray-300 dark:border-gray-700 hover:border-sky-400 dark:hover:border-sky-500'">
                            <!-- Preview -->
                            <div
                                class="flex items-center justify-center w-full h-5/6 p-2 bg-gray-100 dark:bg-gray-800 relative group">
                                <img :src="item.avatar" alt=""
                                    class="rounded-md max-h-full max-w-full object-contain transition-transform duration-200 group-hover:scale-105" />
                                <!-- Icon check khi được select -->
                                <div v-if="isSelect(index)" class="absolute z-10 top-2 right-2">
                                    <div
                                        class=" w-6 h-6 bg-blue-500 text-black rounded-full flex items-center justify-center shadow">
                                        <Icon icon="material-symbols:check-rounded" class="text-lg" />
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="w-full h-1/6 flex items-center justify-center text-center px-2
           font-medium text-gray-700 dark:text-gray-200 text-sm truncate">
                                {{ item.name }}
                            </div>
                        </div>

                    </ContextMenuTrigger>
                    <ContextMenuContent class="w-64">
                        <template v-if="item.type == 'dir'">
                            <ContextMenuItem inset @click="open(item.path)">
                                Open
                            </ContextMenuItem>
                        </template>
                        <ContextMenuItem inset disabled>
                            Forward
                            <ContextMenuShortcut>⌘]</ContextMenuShortcut>
                        </ContextMenuItem>
                        <ContextMenuItem inset>
                            Reload
                            <ContextMenuShortcut>⌘R</ContextMenuShortcut>
                        </ContextMenuItem>
                        <ContextMenuSub>
                            <ContextMenuSubTrigger inset>
                                More Tools
                            </ContextMenuSubTrigger>
                            <ContextMenuSubContent class="w-48">
                                <ContextMenuItem>
                                    Save Page As...
                                    <ContextMenuShortcut>⇧⌘S</ContextMenuShortcut>
                                </ContextMenuItem>
                                <ContextMenuItem>Create Shortcut...</ContextMenuItem>
                                <ContextMenuItem>Name Window...</ContextMenuItem>
                                <ContextMenuSeparator />
                                <ContextMenuItem>Developer Tools</ContextMenuItem>
                            </ContextMenuSubContent>
                        </ContextMenuSub>
                        <ContextMenuSeparator />
                        <ContextMenuCheckboxItem :model-value="true">
                            Show Bookmarks Bar
                            <ContextMenuShortcut>⌘⇧B</ContextMenuShortcut>
                        </ContextMenuCheckboxItem>
                        <ContextMenuCheckboxItem>Show Full URLs</ContextMenuCheckboxItem>
                        <ContextMenuSeparator />
                        <ContextMenuRadioGroup model-value="pedro">
                            <ContextMenuLabel inset>
                                People
                            </ContextMenuLabel>
                            <ContextMenuSeparator />
                            <ContextMenuRadioItem value="pedro">
                                Pedro Duarte
                            </ContextMenuRadioItem>
                            <ContextMenuRadioItem value="colm">
                                Colm Tuite
                            </ContextMenuRadioItem>
                        </ContextMenuRadioGroup>
                    </ContextMenuContent>
                </ContextMenu>
            </li>
        </ul>
    </div>
</template>

<script lang="ts" setup>
import { Media } from '@/types/vnwa';
interface SelectedItem {
    key: string | number
    value: Media
}
import {
    ContextMenu,
    ContextMenuCheckboxItem,
    ContextMenuContent,
    ContextMenuItem,
    ContextMenuLabel,
    ContextMenuRadioGroup,
    ContextMenuRadioItem,
    ContextMenuSeparator,
    ContextMenuShortcut,
    ContextMenuSub,
    ContextMenuSubContent,
    ContextMenuSubTrigger,
    ContextMenuTrigger,
} from "@/components/ui/context-menu"
import { useMagicKeys } from '@vueuse/core'

import { onMounted, onUnmounted, ref, watch } from 'vue'
import { Icon } from '@iconify/vue';
const props = defineProps<{
    medias: Media[]
}>();
const emit = defineEmits(['choosePath', 'select'])
const open = (path: string) => {
    emit('choosePath', path);
    selectItems.value = []
}

const openFolder = (item: Media) => {
    if (item.type == 'dir') {
        open(item.path)
    }
}

const selectItems = ref<SelectedItem[]>([])
const { Ctrl, Meta } = useMagicKeys()

const addSelect = (key: string | number, item: Media) => {
    if (Ctrl.value || Meta.value) {
        // Giữ Ctrl/Meta -> push thêm nếu chưa có
        const exists = selectItems.value.find(s => s.key === key)
        if (!exists) {
            selectItems.value.push({ key, value: item })
        } else {
            // Nếu click lại item đã chọn -> bỏ chọn
            selectItems.value = selectItems.value.filter(s => s.key !== key)
        }
    } else {
        // Không giữ Ctrl/Meta -> reset và chọn duy nhất
        selectItems.value = [{ key, value: item }]
    }

}

const isSelect = (key: string | number) => {
    return !!selectItems.value.find(s => s.key === key)
}

const selectAll = () => {
    selectItems.value = []

    props.medias.forEach((item, index) => {
        selectItems.value.push({
            key: index, // hoặc item.path nếu muốn unique theo path
            value: item
        })
    })
}
const { Meta_A, Ctrl_A } = useMagicKeys({
    passive: false,
    onEventFired(e) {
        if (e.key === "a" && (e.metaKey || e.ctrlKey))
            e.preventDefault()
    },
})
watch([Meta_A, Ctrl_A], (v) => {
    if (v[0] || v[1])
        selectAll()
})
// Click ra ngoài thì clear selection
const handleClickOutside = (event: Event) => {
    const target = event.target as HTMLElement
    if (!target.closest(".no_close_selected")) {
        selectItems.value = [] // clear selection
    }
}
watch(selectItems, (select: SelectedItem[]) => {
    const items = select.map(s => s.value)
    emit('select', items)
}, { deep: true })
onMounted(() => {
    document.addEventListener("click", handleClickOutside)
})
onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside)
})

</script>

<style></style>