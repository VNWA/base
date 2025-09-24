<script setup lang="ts">
import Editor from '@/components/input/Editor.vue';
import Layout from '@/layouts/Layout.vue';
import { ref } from 'vue';

const content = ref('');
</script>
<template>

    <Layout title="Dashboard">

        <Editor v-model="content"  />

    </Layout>
</template>
