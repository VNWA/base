<template>
    <Layout title="Static Page">
        <VnwaPanel title="Static Page" :loading="isLoading">
            <template #toolbar>
                <Button aria-label="Refresh" color="purple" label="Refresh" icon="material-symbols:rotate-left"
                    @click="refresh()" />
                <Button @click="openCreate()" aria-label="Create" color="blue" label="Create"
                    icon="material-symbols:add-2" />
            </template>

            <DataTable v-model:server-options="serverOptions" :server-items-length="serverItemsLength"
                :loading="isLoading" :headers="headers" :items="items" buttons-pagination show-index must-sort>
                <!-- Avatar -->
                <template #item-image="{ image, name }">
                    <img :src="storage(image)" :alt="name" class="w-12 h-12 rounded-full object-cover" />
                </template>
                <template #item-description="{ description }">
                    <p class="line-clamp-2 max-w-md">{{ description }}</p>
                </template>

                <!-- Operation -->
                <template #item-operation="{ id, name }">
                    <div class="flex gap-2 justify-start">
                        <Button size="sm" @click="openEdit(id)" icon="material-symbols:edit" color="yellow"
                            label="Edit" />
                        <Button size="sm" icon="material-symbols:delete-forever-outline-rounded" color="red"
                            variant="outline" label="Remove" @click="deleteItem(id, name)" />
                    </div>
                </template>
            </DataTable>
        </VnwaPanel>
    </Layout>
</template>

<script lang="ts" setup>
import Card from '@/components/Card.vue';
import type { Header, Item, ServerOptions } from 'vue3-easy-data-table'
import { onMounted, ref, watch } from 'vue';
import { ProductBrand } from '@/types/vnwa';
import axios from 'axios';
import { useModal } from 'vue-final-modal';
import ModalConfirm from '@/components/modal/ModalConfirm.vue';
import ModalBrandCreateOrEdit from '@/components/modal/ModalBrandCreateOrEdit.vue';
import { storage } from '@/lib/utils';
import Button from '@/components/Button.vue';
import Layout from '@/layouts/Layout.vue';
import VnwaPanel from '@/components/VnwaPanel.vue';
import ModalStaticPageCreateOrEdit from '@/components/modal/ModalStaticPageCreateOrEdit.vue';

interface T {
    data: ProductBrand[]
    total: number
}


const headers: Header[] = [
    { text: 'Image', value: 'image' },
    { text: 'Name', value: 'name' },
    { text: 'Created At', value: 'created_at' },
    { text: 'Action', value: 'operation' }
]
const isLoading = ref(false)
const items = ref<Item[]>([]);
const serverItemsLength = ref(0);
const serverOptions = ref<ServerOptions>({
    page: 1,
    rowsPerPage: 10,
    sortBy: 'created_at',
    sortType: 'desc',
});


const refresh = async () => {
    isLoading.value = true
    const { page, rowsPerPage, sortBy, sortType } = serverOptions.value;

    await axios.post(route('vnwa.static-page.list'), {
        page: page,
        rowsPerPage: rowsPerPage,
        sortBy: sortBy,
        sortType: sortType,
    }).then(res => {
        items.value = res.data?.data
        serverItemsLength.value = res.data?.total;
    })
    isLoading.value = false

}


const deleteItem = (id: number, name: string) => {
    const data = [
        {
            label: name,
            value: id
        }
    ]
    const { open, close } = useModal(
        {
            component: ModalConfirm,
            attrs: {
                title: 'Remove Static Page',
                items: data,
                url: route('vnwa.static-page.delete'),
                onSuccess() {
                    close()
                    refresh()
                },
                onClose() {
                    close()
                },
            }
        }
    )
    open();
}
const openCreate = () => {
    const { open, close } = useModal(
        {
            component: ModalStaticPageCreateOrEdit,
            attrs: {
                onSuccess() {
                    close()
                    refresh()
                },
                onClose() {
                    close()
                },
            }
        }
    )
    open();
}
const openEdit = (id: number) => {
    const { open, close } = useModal(
        {
            component: ModalStaticPageCreateOrEdit,
            attrs: {
                id: id,
                onSuccess() {
                    close()
                    refresh()
                },
                onClose() {
                    close()
                },
            }
        }
    )
    open();
}

watch(serverOptions, () => {
    refresh()
})

onMounted(() => {
    refresh();
})
</script>

<style></style>