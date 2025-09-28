<template>
  <div class="flex items-center gap-2 px-2 py-1">
    <Tooltip text="Bold">
      <button
        type="button"
        @click="editor?.chain().focus().toggleBold().run()"
        :disabled="!editor?.can().chain().focus().toggleBold().run()"
        :class="editor?.isActive('bold') ? activeBtn : inactiveBtn"
      >
        <Icon icon="fa6-solid:bold" class="w-4 h-4" />
      </button>
    </Tooltip>
    <Tooltip text="Italic">
      <button
        type="button"
        @click="editor?.chain().focus().toggleItalic().run()"
        :disabled="!editor?.can().chain().focus().toggleItalic().run()"
        :class="editor?.isActive('italic') ? activeBtn : inactiveBtn"
      >
        <Icon icon="fa6-solid:italic" class="w-4 h-4" />
      </button>
    </Tooltip>
    <Tooltip text="Strike">
      <button
        type="button"
        @click="editor?.chain().focus().toggleStrike().run()"
        :disabled="!editor?.can().chain().focus().toggleStrike().run()"
        :class="editor?.isActive('strike') ? activeBtn : inactiveBtn"
      >
        <Icon icon="fa6-solid:strikethrough" class="w-4 h-4" />
      </button>
    </Tooltip>
  </div>
</template>

<script setup lang="ts">
import { Editor } from "@tiptap/vue-3";
import Tooltip from "../../Tooltip.vue";
import { Icon } from "@iconify/vue";

defineProps<{ editor: Editor | null }>();

const activeBtn =
  "px-2 py-1 rounded border border-purple-500 text-purple-600 bg-purple-100";
const inactiveBtn =
  "px-2 py-1 rounded border border-gray-300 text-gray-600 bg-white";
</script>
