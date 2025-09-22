<template>
    <Layout title="Create Product">

        <form @submit.prevent="submit">
            <Card title="Create Product" :loading="loading">
                <template #header>
                    <Button type="button" :loading="loading" label="Save" icon="material-symbols:save" color="indigo" />
                </template>
                <div class="space-y-4">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-7">
                            <Card >
                                <div class="space-y-4">
                                <InputFild v-model="form.name" label="name" @change="nameChange"
                                    :error="form.errors.name" />
                                <InputFild v-model="form.slug" label="slug" :error="form.errors.slug" />

                                <div class="grid grid-cols-2 gap-4">
                                    <InputFild v-model="form.price" label="price" type="number"
                                        :error="form.errors.price" />
                                    <InputFild v-model="form.price_old" label="Price Old" type="number"
                                        :error="form.errors.price_old" />
                                </div>

                                <TextareaField v-model="form.append.delivery_and_returns" label="Delyvery and Return"
                                    :error="form.errors.append" />
                                <Editor v-model="form.description" label="description"
                                    :error="form.errors.description" />
                                <Editor v-model="form.append.specification" label="Specification"
                                    :error="form.errors.append" />

                                <MetaSeo v-model="form.meta" />
                                </div>
                            </Card>
                        </div>
                        <div class="col-span-5">
                            <Card>
                                <div class="space-y-4">

                                    <InputImage v-model="form.url_avatar" label="avatar" :error="form.errors.url_avatar" />
                                    <InputImages v-model="form.images" label="Images" :error="form.errors.images" :grid="3" />
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
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
import Button from '@/components/Button.vue';
import Card from '@/components/Card.vue';
import Editor from '@/components/input/Editor.vue';
import InputFild from '@/components/input/InputFild.vue';
import InputImage from '@/components/input/InputImage.vue';
import MetaSeo from '@/components/MetaSeo.vue';
import TextareaField from '@/components/input/TextareaField.vue';
import InputImages from '@/Components/input/InputImages.vue';
const loading = ref(false);
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

const nameChange = () => {
    form.slug = generateSlug(form.name);
}
const submit = async () => {
    loading.value = true;
    await axios.post(route('vnwa.product.create'), form).then(res => {
        toast(res.data.message, 'success')

    }).catch(err => {
        form.errors = err.response?.data?.errors || {};
        toast(err.response?.data?.message || 'Có lỗi xảy ra', 'error');
    }).finally(() => {
        loading.value = false;

    })



};
</script>

<style></style>
