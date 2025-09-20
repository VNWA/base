<template>
    <LayoutModal :title="title">


        <Card :loading="loading">
            <form>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <InputFild v-model="form.name" label="name" @change="nameChange" :error="form.errors.name" />
                        <InputFild v-model="form.slug" label="slug" :error="form.errors.slug" />
                    </div>
                    <InputImage v-model="form.url_avatar" :error="form.errors.url_avatar" />
                    <div class="col-span-2 space-y-4">

                        <TextareaField v-model="form.description" label="description"
                            :error="form.errors.description" />

                        <MetaSeo v-model="form.meta" />
                    </div>
                </div>

            </form>
        </Card>

        <template #footer>
            <Button :loading="loading" size="sm" color="gray" variant="outline" label="Cancel" @click="emit('close')" />
            <Button :loading="loading" size="sm" color='purple' label="Save" icon="material-symbols:save-outline-sharp"
                @click="submit()" />
        </template>
    </LayoutModal>
</template>
<script lang="ts" setup>
import { onMounted, ref } from 'vue';
import Button from '../Button.vue';
import { useForm } from '@inertiajs/vue3';
import { ProductBrand } from '@/types/vnwa';
import { generateSlug, toast } from '@/lib/utils';
import axios from 'axios';
import LayoutModal from './LayoutModal.vue';
import Card from '../Card.vue';
import MetaSeo from '../MetaSeo.vue';
import InputFild from '../input/InputFild.vue';
import InputImage from '../input/InputImage.vue';
import TextareaField from '../input/TextareaField.vue';
const props = defineProps<{
    title: string
    id?: number
}>()
const emit = defineEmits(['success', 'close']);
const loading = ref(false)
const form = useForm<ProductBrand>({
    id: null,
    url_avatar: '',
    name: '',
    slug: '',
    description: '',
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
    const url = form.id
        ? route('vnwa.product.brand.update', form.id)
        : route('vnwa.product.brand.store');

    await axios.post(url, form).then(res => {
        toast(res.data.message, 'success');
        emit('success')
    }).catch(err => {
        form.errors = err.response?.data?.errors || {};
        toast(err.response?.data?.message || 'Có lỗi xảy ra', 'error');
    }).finally(() => {
        loading.value = false
    })
};


const load = async (id: number) => {
    loading.value = true;
    await axios.get(route('vnwa.product.brand.detail', id), form).then(res => {
        form.id = id
        form.url_avatar = res.data.url_avatar;
        form.name = res.data.name;
        form.slug = res.data.slug;
        form.description = res.data.description;
        form.meta.title = res.data.meta_title || '';
        form.meta.desc = res.data.meta_desc || '';
        form.meta.image = res.data.meta_image || '';
    }).catch(err => {
        toast(err.response?.data?.message || 'Có lỗi xảy ra', 'error');
    }).finally(() => {
        loading.value = false
    })
}

onMounted(() => {
    form.reset();
    if (props.id) {
        load(props.id)
    }

})

</script>