<template>
    <div v-if="editor" class="p-2 flex flex-wrap items-center gap-2 divide-x">
        <HeadingGroup :editor="editor" />
        <ListGroup :editor="editor" />
        <AlignGroup :editor="editor" />
        <FontStyleGroup :editor="editor" />
        <CodeGroup :editor="editor" />
        <ColorGroup :editor="editor" />
        <UIBlockGroup :editor="editor" />

        <MediaGroup :editor="editor" />
    </div>
</template>

<script setup lang="ts">
import { Editor } from "@tiptap/vue-3";
import FontStyleGroup from "./groups/FontStyleGroup.vue";
import CodeGroup from "./groups/CodeGroup.vue";
import HeadingGroup from "./groups/HeadingGroup.vue";
import AlignGroup from "./groups/AlignGroup.vue";
import ColorGroup from "./groups/ColorGroup.vue";
import ListGroup from "./groups/ListGroup.vue";
import MediaGroup from "./groups/MediaGroup.vue";
import UIBlockGroup from "./groups/UIBlockGroup.vue";

defineProps<{
    editor: Editor | null;
}>();
</script>
