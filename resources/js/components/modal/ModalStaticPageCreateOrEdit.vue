<template>
    <LayoutModal :title="title">


        <Card :loading="loading">
            <form>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <InputFild v-model="form.name" label="name" @change="nameChange" :error="form.errors.name" />
                        <InputFild v-model="form.slug" label="slug" :error="form.errors.slug" />
                    </div>
                    <InputImage v-model="form.image" :error="form.errors.image" />
                    <div class="col-span-2 space-y-4">

                        <Editor v-model="form.content" label="Content" :error="form.errors.content" />

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
import { computed, onMounted, ref } from 'vue';
import Button from '../Button.vue';
import { useForm } from '@inertiajs/vue3';
import { generateSlug, toast } from '@/lib/utils';
import axios from 'axios';
import LayoutModal from './LayoutModal.vue';
import Card from '../Card.vue';
import MetaSeo from '../MetaSeo.vue';
import InputFild from '../input/InputFild.vue';
import InputImage from '../input/InputImage.vue';
import { StaticPage } from '@/types/vnwa';
import Editor from '../input/Editor.vue';
const props = defineProps<{
    id?: number
}>()
const title = computed(() => props.id ? 'Edit Static Page' : 'Create Static Page')
const emit = defineEmits(['success', 'close']);
const loading = ref(false)
const form = useForm<StaticPage>({
    id: null,
    image: '',
    name: '',
    slug: '',
    content: '',
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
        ? route('vnwa.static-page.update', form.id)
        : route('vnwa.static-page.store');

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
    await axios.get(route('vnwa.static-page.detail', id), form).then(res => {
        form.id = id
        form.name = res.data.name
        form.slug = res.data.slug
        form.content = res.data.content
        form.image = res.data.image
        form.meta.title = res.data.meta_title
        form.meta.desc = res.data.meta_desc
        form.meta.image = res.data.meta_image


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