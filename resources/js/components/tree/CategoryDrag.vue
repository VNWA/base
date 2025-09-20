<template>
    <div>
        <!-- Header -->
        <div
            class="flex items-center justify-between px-3 py-2 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 w-full mb-4">
            <div>
                <h3 class="text-xl font-semibold text-vnwa dark:text-white capitalize">
                    {{ subData.name }}
                </h3>
            </div>
            <div class="flex items-center justify-end gap-2">
                <button
                    class="flex items-center gap-1 rounded-xl border px-3 py-1 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-800"
                    @click="showCreate">
                    <span class="material-symbols-outlined">add</span>
                    Create
                </button>
            </div>
        </div>

        <!-- Tree -->
        <Draggable v-model="tree" class="mtl-tree capitalize" treeLine :defaultOpen="true" :animation="200"
            ghost-class="drag-ghost" drag-class="dragging" @drop="onDrop">
            <template #default="{ node, stat }">
                <div class="group cursor-pointer relative mx-3 my-1 flex items-center justify-between gap-2 rounded-xl border border-transparent bg-white p-3 shadow-sm transition-all dark:bg-gray-900"
                    :class="{
                        'border-vnwa bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 font-semibold shadow-md':
                            idFocus === node.id,
                        'hover:border-gray-300 dark:hover:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800':
                            idFocus !== node.id
                    }">
                    <!-- Name -->
                    <div class="flex w-full items-center gap-2" @click="focusItem(node.id)">
                        <span class="truncate text-black/80 dark:text-white/80">
                            {{ node.name }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            ({{ node.children.length }})
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <button v-if="idFocus === node.id"
                            class="rounded-lg border border-red-500 text-red-500 px-2 py-1 text-xs hover:bg-red-500 hover:text-white"
                            @click="remove(node.id, node.name)">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                        <OpenIcon v-if="stat.children.length" :open="stat.open"
                            class="text-gray-400 hover:text-gray-600 text-4xl dark:text-gray-500 dark:hover:text-gray-300"
                            @click="stat.open = !stat.open" />
                    </div>
                </div>
            </template>
        </Draggable>
    </div>
</template>

<script setup lang="ts">
import { toast } from '@/lib/utils'
import { Draggable, OpenIcon } from '@he-tree/vue'
import '@he-tree/vue/style/default.css'
import axios from 'axios'
import { reactive, ref, watch } from 'vue'

interface TreeNode {
    id: number
    name: string
    children: TreeNode[]
}

const props = defineProps<{
    treeData: TreeNode[]
    type: string
}>()

const emit = defineEmits(['focusItem', 'reload', 'showCreate'])

const subData = reactive({
    name: '',
    url_delete: '',
    url_update: ''
})

switch (props.type) {
    case 'blog_category':
        subData.name = 'Blog Category'
        subData.url_delete = '/vnwa/blog/category/delete'
        subData.url_update = '/vnwa/blog/category/update-tree-data'
        break
    case 'product_category':
        subData.name = 'Product Category'
        subData.url_delete = '/vnwa/product/category/delete'
        subData.url_update = '/vnwa/product/category/update-tree-data'
        break
    default:
        break
}

const idFocus = ref<number | null>(null)
const tree = ref<TreeNode[]>([...props.treeData])

watch(
    () => props.treeData,
    (val) => {
        tree.value = [...val]
    }
)

const showCreate = () => {
    emit('showCreate')
}

const onDrop = () => {
    updateTreeData()
}

const focusItem = (id: number) => {
    idFocus.value = id
    emit('focusItem', id)
}

const remove = async (id: number, name: string) => {
    try {
        await axios.post(subData.url_delete, { id })
        emit('reload')
        toast(`Đã xóa ${name}`, 'success')
    } catch (error) {
        toast('Xóa thất bại!', 'error')
    }
}

const updateTreeData = async () => {
    try {
        const res = await axios.post(subData.url_update, {
            treeData: tree.value
        })
        emit('reload')
        toast(res.data.message || 'Cập nhật thành công', 'success')
    } catch (error) {
        toast('Cập nhật thất bại!', 'error')
    }
}
</script>
