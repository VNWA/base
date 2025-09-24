<template>
  <div class="flex items-center gap-2 px-2 py-1">
    <button
      type="button"
      @click="editor?.chain().focus().toggleCode().run()"
      :disabled="!editor?.can().chain().focus().toggleCode().run()"
      :class="editor?.isActive('code') ? activeBtn : inactiveBtn"
    >
      <Icon icon="fa6-solid:code" class="w-4 h-4" />
    </button>
  </div>
</template>

<script setup lang="ts">
import { Editor } from "@tiptap/vue-3";
import { Icon } from "@iconify/vue";

defineProps<{ editor: Editor | null }>();

const activeBtn =
  "px-2 py-1 rounded border border-purple-500 text-purple-600 bg-purple-100";
const inactiveBtn =
  "px-2 py-1 rounded border border-gray-300 text-gray-600 bg-white";
</script>
