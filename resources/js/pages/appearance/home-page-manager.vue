<script setup lang="ts">
import Card from '@/components/Card.vue';
import Banners from '@/components/drag/Banners.vue';
import Editor from '@/components/input/Editor.vue';
import InputFild from '@/components/input/InputFild.vue';
import InputImage from '@/components/input/InputImage.vue';
import TextareaField from '@/components/input/TextareaField.vue';
import MetaSeo from '@/components/MetaSeo.vue';
import VnwaPanel from '@/components/VnwaPanel.vue';
import Layout from '@/layouts/Layout.vue';
import { Banner, Meta } from '@/types/vnwa';
import { ref } from 'vue';

interface T {
    banners: Banner[];
    sections: {
        search: {
            primary_title: string;
            image: string;
            title: string;
            description: string;
        },
        quote: {
            primary_title: string;
            image: string;
            title: string;
            description: string;
        }
    },
    highlighted_brands: {
        label: string;
        brand_ids: number[];
    }
    short_about: string;
    meta: Meta;

}
const state = ref({
    banners: [],
    sections: {
        search: {
            primary_title: 'Find Your Product',
            image: '',
            title: 'Search for products',
            description: 'Use the search bar to find products quickly and easily.'
        },
        quote: {
            primary_title: 'What Our Customers Say',
            image: '',
            title: 'Customer Testimonials',
            description: 'Read what our satisfied customers have to say about us.'
        }
    },
    highlighted_brands: {
        label: 'Highlighted Brands',
        brand_ids: []
    },
    short_about: '<p>We are a leading e-commerce platform dedicated to providing the best products and services to our customers. Our mission is to make online shopping easy, convenient, and enjoyable for everyone.</p>',
    meta: {
        title: 'Home Page - Vnwa',
        desc: 'Welcome to Vnwa, your number one source for all things product. We\'re dedicated to giving you the very best of product, with a focus on dependability, customer service and uniqueness.',
        image: ''
    }


});

</script>
<template>

    <Layout title="Home Page Managaer">


        <VnwaPanel title="Home Page Manager">
            <div class="grid grid-cols-2 gap-4">
                <Card title="Banners" class="col-span-2">
                    <Banners v-model="state.banners" />
                </Card>
                <Card title="Left Section Find Product">
                    <div class="space-y-4">
                        <InputImage v-model="state.sections.search.image" label="Image" />
                        <InputFild v-model="state.sections.search.primary_title" label="Primary Title" />
                        <InputFild v-model="state.sections.search.title" label="Title" />
                        <TextareaField v-model="state.sections.search.description" label="Description" />
                    </div>
                </Card>
                <Card title="Right Section Customer Quote">
                    <div class="space-y-4">
                        <InputImage v-model="state.sections.quote.image" label="Image" />
                        <InputFild v-model="state.sections.quote.primary_title" label="Primary Title" />
                        <InputFild v-model="state.sections.quote.title" label="Title" />
                        <TextareaField v-model="state.sections.quote.description" label="Description" />
                    </div>
                </Card>

                <Card title="Short About" class="col-span-2">
                    <Editor v-model="state.short_about" />
                </Card>

                <MetaSeo v-model="state.meta" class="col-span-2" />
            </div>
        </VnwaPanel>

    </Layout>
</template>
