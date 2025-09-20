<template>
    <Layout title="Create Product">
        <Card title="Create Product" :loading="loading">
            <template #header>
                <Button @click="submit" :loading="loading" label="Save" icon="material-symbols:save" color="indigo" />
            </template>
            <form>
                <div class="space-y-4">

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-3">
                            <InputImage v-model="form.url_avatar" :error="form.errors.url_avatar" />
                        </div>
                        <div class="col-span-9 space-y-4">
                            <InputFild v-model="form.name" label="name" @change="nameChange"
                                :error="form.errors.name" />
                            <InputFild v-model="form.slug" label="slug" :error="form.errors.slug" />
                        </div>
                    </div>
                    <TextareaField v-model="form.description" label="description" :error="form.errors.description" />
                    <div>
                        <Editor v-model="form.description" />
                    </div>

                    <MetaSeo v-model="form.meta" />
                </div>

            </form>

        </Card>
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
import Layout from '@/layouts/Layout.vue';
import { generateSlug, toast } from '@/lib/utils';
import { Product } from '@/types/vnwa';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
const loading = ref(false);
const form = useForm<Product>({
    url_avatar: '',
    images: [],
    sku: '',
    stock: 0,
    origin: '',
    name: '',
    slug: '',
    description: '',
    append: {
        delivery_and_returns: [],
        specification: ''
    },
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