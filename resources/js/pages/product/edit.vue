<template>
    <Layout title="Create Product">

        <form @submit.prevent="submit">
            <Card title="Create Product" :loading="loading">
                <template #header>
                    <Button type="submit" :loading="loading" label="Save" icon="material-symbols:save" color="indigo" />
                </template>
                <div class="space-y-4">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-7 space-y-4">
                            <Card>
                                <div class="space-y-4">
                                    <InputFild v-model="form.name" label="name" @change="nameChange"
                                        :error="form.errors.name" />
                                    <InputFild v-model="form.slug" label="slug" :error="form.errors.slug" />

                                    <div class="grid grid-cols-2 gap-4">
                                        <InputFild v-model="form.price" label="price" type="number"
                                            :error="form.errors.price" />
                                        <InputFild v-model="form.price_old" label="Price Old" type="number"
                                            :error="form.errors.price_old" />
                                        <InputFild v-model="form.sku" label="SKU"
                                            description="If you do not enter, it will automatically generate"
                                            :error="form.errors.sku" />
                                        <InputFild v-model="form.stock" label="Stock" type="number"
                                            :error="form.errors.stock" />
                                        <OptionData v-model="form.brand_id" label="Brand" :error="form.errors.brand_id"
                                            :data_url="route('vnwa.product.brand.mini-option')" />
                                        <OptionMutipleData v-model="form.category_ids" label="Categories"
                                            :error="form.errors.category_ids"
                                            :data_url="route('vnwa.product.category.mini-option')" />
                                    </div>




                                </div>
                            </Card>
                            <Card>
                                <div class="space-y-4">
                                    <TextareaField v-model="form.append.delivery_and_returns"
                                        label="Delyvery and Return" :error="form.errors.append" />
                                    <Editor v-model="form.description" label="description"
                                        :error="form.errors.description" />
                                    <Editor v-model="form.append.specification" label="Specification"
                                        :error="form.errors.append" />
                                </div>
                            </Card>
                            <MetaSeo v-model="form.meta" />
                        </div>
                        <div class="col-span-5">
                            <Card>
                                <div class="space-y-4">

                                    <InputImage v-model="form.url_avatar" label="avatar"
                                        :error="form.errors.url_avatar" />


                                    <InputImages v-model="form.images" label="Images" :error="form.errors.images"
                                        :grid="3" />
                                </div>

                            </Card>
                        </div>
                    </div>

                </div>


            </Card>
        </form>

    </Layout>
</template>

<script lang="ts" setup>

import Layout from '@/layouts/Layout.vue';
import { generateSlug, toast } from '@/lib/utils';
import { Product } from '@/types/vnwa';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import Button from '@/components/Button.vue';
import Card from '@/components/Card.vue';
import Editor from '@/components/input/Editor.vue';
import InputFild from '@/components/input/InputFild.vue';
import InputImage from '@/components/input/InputImage.vue';
import MetaSeo from '@/components/MetaSeo.vue';
import TextareaField from '@/components/input/TextareaField.vue';
import InputImages from '@/components/input/InputImages.vue';
import OptionMutipleData from '@/components/input/OptionMutipleData.vue';
import OptionData from '@/components/input/OptionData.vue';
const loading = ref(false);
const props = defineProps<{
    product: Product
}>();
const form = useForm<Product>({
    brand_id: null,
    category_ids: [],
    url_avatar: '',
    images: [],
    sku: '',
    stock: 0,
    origin: '',
    name: '',
    slug: '',
    description: '',
    append: {
        delivery_and_returns: '',
        specification: ''
    },
    price: 0,
    price_old: 0,
    meta: {
        title: '',
        desc: '',
        image: ''
    }
});
if (props.product) {
    console.log(props.product);
    form.url_avatar = props.product.url_avatar;
    form.images = props.product.images || [];
    form.sku = props.product.sku;
    form.stock = props.product.stock;
    form.origin = props.product.origin || '';
    form.name = props.product.name;
    form.slug = props.product.slug;
    form.description = props.product.description || '';
    form.append.delivery_and_returns = props.product.append.delivery_and_returns || '';
    form.append.specification = props.product.append.specification || '';
    form.price = props.product.price;
    form.price_old = props.product.price_old || 0;
    form.meta.title = props.product.meta?.title || '';
    form.meta.desc = props.product.meta?.desc || '';
    form.meta.image = props.product.meta?.image || '';
    form.brand_id = props.product.brand_id || null;
    form.category_ids = props.product.category_ids || [];
}

const nameChange = () => {
    form.slug = generateSlug(form.name);
}
const submit = async () => {
    loading.value = true;
    await axios.post(route('vnwa.product.update', props.product.id), form).then(res => {
        toast(res.data.message, 'success')
        router.reload();
    }).catch(err => {
        form.errors = err.response?.data?.errors || {};
        toast(err.response?.data?.message || 'Có lỗi xảy ra', 'error');
    }).finally(() => {
        loading.value = false;

    })



};
</script>

<style></style>
