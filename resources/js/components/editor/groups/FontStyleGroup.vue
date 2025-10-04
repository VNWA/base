<template>
  <div class="flex items-center gap-2 px-2 py-1">
    <Tooltip text="Bold">
        <Button :active="editor?.isActive('bold')"   @click="editor?.chain().focus().toggleBold().run()" iconClass="text-lg mx-2"  icon="fa6-solid:bold" />
    </Tooltip>
    <Tooltip text="Italic">
        <Button :active="editor?.isActive('italic')"   @click="editor?.chain().focus().toggleItalic().run()" iconClass="text-lg mx-2"  icon="fa6-solid:italic" />
    </Tooltip>
    <Tooltip text="Strike">

        <Button :active="editor?.isActive('strike')"   @click="editor?.chain().focus().toggleStrike().run()" iconClass="text-lg mx-2"  icon="fa6-solid:strikethrough" />
    </Tooltip>
  </div>
</template>

<script setup lang="ts">
import { Editor } from "@tiptap/vue-3";
import Tooltip from "../../Tooltip.vue";
import Button from "../Button.vue";

defineProps<{ editor: Editor | null }>();


</script>
