<template>
    <Layout title="Products">
        <VnwaPanel title="Products" :loading="loading" :isFilter="true">
            <template #form-filter>
                <div class="grid grid-cols-3 gap-4">
                    <OptionMutipleData v-model="filter.categories" :data_url="route('vnwa.product.category.mini-option')"
                        label="Filter Categories" />
                    <OptionMutipleData v-model="filter.brands" :data_url="route('vnwa.product.brand.mini-option')"
                        label="Filter Brands" />
                    <InputFild v-model="filter.name" label="Serach By Name" placeholder="Search" />
                </div>
            </template>
            <template #toolbar>
                <Button aria-label="Refresh" color="purple" label="Refresh" icon="material-symbols:rotate-left"
                    @click="refresh()" />
                <VnwaLink :to="route('vnwa.product.create')">
                    <Button aria-label="Create" color="blue" label="Create" icon="material-symbols:add-2" />
                </VnwaLink>
            </template>

            <DataTable v-model:server-options="serverOptions" :server-items-length="serverItemsLength"
                :loading="loading" :headers="headers" :items="items" buttons-pagination show-index must-sort>
                <!-- Avatar -->
                <template #item-avatar="{ url_avatar, name }">
                    <img :src="storage(url_avatar)" :alt="name" class="w-12 h-12 rounded-full object-cover" />
                </template>
                <template #item-description="{ description }">
                    <p class="line-clamp-2 max-w-md">{{ description }}</p>
                </template>

                <!-- Operation -->
                <template #item-operation="{ id, name }">
                    <div class="flex gap-2 justify-start">
                        <VnwaLink :to="route('vnwa.product.edit', id)">

                            <Button size="sm" icon="material-symbols:edit" color="yellow" label="Edit" />
                        </VnwaLink>
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
import { computed, onMounted, ref, watch } from 'vue';
import { Product } from '@/types/vnwa';
import axios from 'axios';
import { useModal } from 'vue-final-modal';
import ModalConfirm from '@/components/modal/ModalConfirm.vue';
import { storage } from '@/lib/utils';
import Button from '@/components/Button.vue';
import Layout from '@/layouts/Layout.vue';
import VnwaPanel from '@/components/VnwaPanel.vue';
import VnwaLink from '@/components/VnwaLink.vue';
import OptionMutipleData from '@/Components/input/OptionMutipleData.vue';
import InputFild from '@/components/input/InputFild.vue';


interface Filter {
    name: string
    brands: number[]
    categories: number[]
}



const headers: Header[] = [
    { text: 'Name', value: 'name' },
    { text: 'Description', value: 'description' },
    { text: 'Action', value: 'operation' }
]
const loading = ref(false)
const items = ref<Item[]>([]);
const serverItemsLength = ref(0);
const filter = ref<Filter>({
    name: '',
    categories: [],
    brands: [],
});

const serverOptions = ref<ServerOptions>({
    page: 1,
    rowsPerPage: 10,
    sortBy: 'created_at',
    sortType: 'desc',
});
const options = computed(() => {
    const { page, rowsPerPage, sortBy, sortType } = serverOptions.value;
    const { name, categories, brands } = filter.value;
    let data = {
        page: page,
        rowsPerPage: rowsPerPage,
        sortBy: sortBy,
        sortType: sortType,
        name: name,
        categories: categories,
        brands: brands,
    }

    return data;
});

const refresh = async () => {
    loading.value = true
    await axios.post(route('vnwa.product.list'), options.value).then(res => {
        items.value = res.data?.data
        serverItemsLength.value = res.data?.total;
    })
    loading.value = false

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
                title: 'Remove Product ',
                items: data,
                url: route('vnwa.product.delete'),
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


watch(options, () => {
    refresh()
})

onMounted(() => {
    refresh();
})
</script>

<style></style>
