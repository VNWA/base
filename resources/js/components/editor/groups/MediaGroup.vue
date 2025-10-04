<template>
    <div class="flex items-center gap-2 px-2 py-1">
      <Tooltip text="Add Link">
        <button
          type="button"
          @click="openLinkModal"
          class="px-2 py-1 rounded border flex items-center gap-1
                 hover:bg-gray-100 dark:hover:bg-gray-800
                 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300"
        >
          <Icon icon="material-symbols:add-link" class="w-4 h-4" />
          URL
        </button>
      </Tooltip>

      <Tooltip text="Add Image">
        <button
          type="button"
          @click="openImageModal"
          class="px-2 py-1 rounded border flex items-center gap-2
                 hover:bg-gray-100 dark:hover:bg-gray-800
                 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300"
        >
          <Icon icon="material-symbols:folder-managed" class="w-4 h-4" />
          VMedia
        </button>
      </Tooltip>
    </div>
  </template>



<script setup lang="ts">
import { Editor } from "@tiptap/vue-3";
import { Icon } from "@iconify/vue";
import Tooltip from "../../Tooltip.vue";
import { useModal } from "vue-final-modal";
import ModalMedia from "../../modal/ModalMedia.vue";
import ModalInsertLink from "../../modal/ModalInsertLink.vue";
import { storage } from "@/lib/utils";
import { Media } from "@/types/vnwa";

const props = defineProps<{ editor: Editor | null }>();

// Image modal
const openImageModal = () => {
  const { open, close } = useModal({
    component: ModalMedia,
    attrs: {
      types: ["image"],
      onSuccess(items: Media[]) {
        const html = items
          .map(
            (el) =>
              `<img src="${storage(el.path)}" alt="${el.name}" title="${el.name}" />`
          )
          .join("<p></p>");
        props.editor?.chain().focus().insertContent(html).run();
        close();
      },
      onClose() {
        close();
      },
    },
  });
  open();
};

// Link modal
const { open: openLinkModal, close: closeLink } = useModal({
  component: ModalInsertLink,
  attrs: {
    onSuccess(item: { url: string; target: string }) {
      props.editor
        ?.chain()
        .focus()
        .extendMarkRange("link")
        .setLink({
          href: item.url,
          target: item.target,
          class: "vnwa-editor-link underline text-primary-500",
        })
        .run();
      closeLink();
    },
    onClose() {
      closeLink();
    },
  },
});
</script>
