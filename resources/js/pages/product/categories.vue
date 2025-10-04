<template>
    <Layout title="Product Categories">
        <div class="p-5">
            <div class="grid grid-cols-12 gap-4">
                <div class="lg:col-span-4 col-span-12">
                    <Card title="Categories" :loading="isLoading">
                        <template #header>
                            <Button label="Create" icon="radix-icons:plus" @click="showFormCreate()" color="gray"
                                variant="outline" />
                        </template>
                        <Draggable class="mtl-tree capitalize" v-model="treeData" treeLine :defaultOpen="false"
                            @update:modelValue="onTreeChange">
                            <template #default="{ node, stat }">
                                <div class=" mx-3 flex cursor-pointer items-center gap-4 "
                                    :class="{ 'font-bold': form.id === node.id }">

                                    <div @click="showFormEdit(node.id)"> {{ node.name }} ({{ node.children.length
                                    }})</div>
                                    <button v-if="form.id === node.id" @click="showFormDelete(node.id, node.name)"
                                        class="bg-red-500 hover:bg-red-600 px-2 py-1 text-white rounded-md">
                                        <Icon icon="radix-icons:trash" />
                                    </button>
                                </div>
                                <OpenIcon v-if="stat.children.length" :open="stat.open" class="float-end "
                                    @click.native="stat.open = !stat.open" />
                            </template>
                        </Draggable>
                    </Card>
                </div>
                <div class="col-span-8 ">
                    <Card :title="form.id ? `Edit ${form.name}` : 'Form Create'" :loading="isLoading">
                        <template #header>
                            <Button @click="submit" :loading="isLoading" label="Save" icon="material-symbols:save"
                                color="indigo" />
                        </template>
                        <form>
                            <div class="space-y-4">
                                <InputFild v-model="form.name" label="name" @change="nameChange"
                                    :error="form.errors.name" />
                                <InputFild v-model="form.slug" label="slug" :error="form.errors.slug" />
                                <InputImage v-model="form.url_avatar" :error="form.errors.url_avatar" />
                                <TextareaField v-model="form.description" label="description"
                                    :error="form.errors.description" />
                                <Editor v-model="form.content" label="content" :error="form.errors.content" />

                                <MetaSeo v-model="form.meta" />
                            </div>

                        </form>

                    </Card>

                </div>
            </div>


        </div>
    </Layout>

</template>

<script lang="ts" setup>
import Button from '@/components/Button.vue';
import Card from '@/components/Card.vue';
import Editor from '@/components/input/Editor.vue';
import InputFild from '@/components/input/InputFild.vue';
import InputImage from '@/components/input/InputImage.vue';
import TextareaField from '@/components/input/TextareaField.vue';
import MetaSeo from '@/components/MetaSeo.vue';
import ModalConfirm from '@/components/modal/ModalConfirm.vue';
import Layout from '@/layouts/Layout.vue';
import { generateSlug, toast } from '@/lib/utils';
import { ProductCategory } from '@/types/vnwa';
import { Draggable, OpenIcon } from '@he-tree/vue';
import { Icon } from '@iconify/vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';
import { useModal } from 'vue-final-modal';
import { fa } from 'zod/locales';


const treeData = ref([]);
const isLoading = ref(false)
const form = useForm<ProductCategory>({
    id: null,
    parent_id: null,
    url_avatar: '',
    name: '',
    slug: '',
    description: '',
    content: '',
    meta: {
        title: '',
        desc: '',
        image: ''
    }
});
const reset = () => {
    form.reset();


}
const loadTreeData = async () => {
    isLoading.value = true;
    await axios.get(route('vnwa.product.category.tree.load'))
        .then(res => {
            treeData.value = res.data
            console.log(treeData)
        }).catch(er => {
            console.log(er)

        }).finally(() => {
            isLoading.value = false;

        })

}




const onTreeChange = async (newData: any) => {
    treeData.value = newData;

    await axios.post(route('vnwa.product.category.tree.update'), { treeData: newData })
        .then(response => {
            toast(response.data.message, 'success')
        })
        .catch(error => {
            toast(error.message, 'error')
        });
};



const showFormCreate = () => {
    reset();
}
const showFormEdit = (id: number) => {

    isLoading.value = true
    form.id = id;


    axios.get(route('vnwa.product.category.detail', id))
        .then(response => {
            form.parent_id = response.data.parent_id;
            form.url_avatar = response.data.url_avatar;
            form.name = response.data.name;
            form.slug = response.data.slug;
            form.description = response.data.description;
            form.content = response.data.content;
            form.meta.title = response.data.meta_title || '';
            form.meta.desc = response.data.meta_desc || '';
            form.meta.image = response.data.meta_image || '';
        })
        .catch(error => {
            console.error('Error fetching category:', error);
        }).finally(() => {
            isLoading.value = false;

        });


};


const nameChange = () => {
    form.slug = generateSlug(form.name);
}

const submit = async () => {
    isLoading.value = true;
    const url = form.id
        ? route('vnwa.product.category.update', form.id)
        : route('vnwa.product.category.store');

    try {
        const response = await axios.post(url, form);

        toast(response.data.message, 'success');
        await loadTreeData();
        form.reset();
        form.id = response.data.id;
        await showFormEdit(response.data.id); // chú ý: id nằm trong data nhé
    } catch (err: any) {
        form.errors = err.response?.data?.errors || {};
        toast(err.response?.data?.message || 'Có lỗi xảy ra', 'error');
    } finally {
        isLoading.value = false;
    }
};

const showFormDelete = (id: number, name: string) => {
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
                title: 'Remove Product Category',
                items: data,
                url: route('vnwa.product.category.delete'),
                onSuccess(items) {
                    close()
                    reset()
                    loadTreeData()
                },
                onClose() {
                    close()
                },
            }
        }
    )
    open();
}

onMounted(() => {
    loadTreeData();
})

</script>

<style>
/* ================= Tree Lines ================= */
.tree-node--with-tree-line {
    position: relative;
}

.tree-vline,
.tree-hline {
    position: absolute;
    background-color: #bbb;
    transition: background-color 0.3s;
}

.tree-vline {
    width: 1px;
    top: 0;
    bottom: 0;
}

.tree-hline {
    height: 1px;
    top: 50%;
    width: 12px;
}

/* RTL Support */
.he-tree--rtl {
    direction: rtl;
}

/* ================= Drag placeholder ================= */
.he-tree-drag-placeholder {
    height: 24px;
    width: 100%;
    border-radius: 4px;
    border: 2px dashed #00d9ff;
    background: #ddf2f9;
    box-sizing: border-box;
    transition: all 0.2s ease-in-out;
}

/* ================= Open/Close icon ================= */
.he-tree__open-icon {
    cursor: pointer;
    user-select: none;
    display: inline-block;
    transition: transform 0.2s ease-in-out;
}

.he-tree__open-icon.open {
    transform: rotate(90deg);
}

.he-tree__open-icon svg {
    width: 1em;
    height: 1em;
    transition: fill 0.2s;
}

/* ================= Tree Node ================= */
.tree-node-inner {
    cursor: grab;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    font-weight: 500;
    height: 44px;
    margin: 6px 0;
    padding: 0 12px 0 20px;
    border-radius: 6px;
    box-sizing: border-box;
    text-decoration: none;
    background: #f5f5f5;
    /* Light mode background */
    border: 1px solid #cbd5e1;
    color: #1f2937;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease-in-out;
}

.tree-node-inner:hover {
    background: #e2e8f0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.tree-line {
    background-color: #bbb;
}

/* ================= Dark Mode (.dark class) ================= */
.dark .tree-node-inner {
    background: #1f2937;
    border: 1px solid #374151;
    color: #f3f4f6;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
}

.dark .tree-node-inner:hover {
    background: #374151;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.6);
}

.dark .tree-line {
    background-color: #555;
}

.dark .he-tree-drag-placeholder {
    background: #334155;
    border-color: #60a5fa;
}

.dark .he-tree__open-icon svg {
    fill: #f3f4f6;
}
</style>
