<template>
    <div class="flex items-center gap-2 px-2 py-1">
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <button
            class="px-2 py-1 border rounded flex items-center gap-1
                   bg-white dark:bg-gray-900
                   text-gray-800 dark:text-gray-200
                   border-gray-200 dark:border-gray-700
                   hover:bg-gray-100 dark:hover:bg-gray-800
                   transition-colors"
          >
            <Icon icon="mdi:application-cog" class="w-4 h-4" />
            <span>UI Block</span>
          </button>
        </DropdownMenuTrigger>

        <DropdownMenuContent
          class="w-48 mt-2 rounded shadow-md
                 bg-white dark:bg-gray-900
                 text-gray-800 dark:text-gray-200
                 border border-gray-100 dark:border-gray-800"
        >
          <DropdownMenuItem
            v-for="block in blocks"
            :key="block.name"
            @click="selectBlock(block)"
            type="button"
            class="px-3 py-2 text-sm cursor-pointer
                   hover:bg-gray-100 dark:hover:bg-gray-800
                   transition-colors"
          >
            {{ block.label }}
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>

      <button
      type="button"
        @click="addGrid()"
        class="px-2 py-1 border rounded flex items-center gap-1
               bg-white dark:bg-gray-900
               text-gray-800 dark:text-gray-200
               border-gray-200 dark:border-gray-700
               hover:bg-gray-100 dark:hover:bg-gray-800
               transition-colors"
      >
        <Icon icon="mdi-plus" class="w-4 h-4" />
        <span>Grid</span>
      </button>
    </div>
  </template>

<script setup lang="ts">
import { Editor } from "@tiptap/vue-3";
import { ref } from "vue";
import { Icon } from "@iconify/vue";
import DropdownMenuTrigger from "@/components/ui/dropdown-menu/DropdownMenuTrigger.vue";
import DropdownMenu from "@/components/ui/dropdown-menu/DropdownMenu.vue";
import DropdownMenuContent from "@/components/ui/dropdown-menu/DropdownMenuContent.vue";
import DropdownMenuItem from "@/components/ui/dropdown-menu/DropdownMenuItem.vue";
import { useModal } from "vue-final-modal";
import ModalSettingUIBlock from "@/components/modal/ModalSettingUIBlock.vue";
interface T {
    name: string,
    label: string,
    settings?: {
        type: 'number' | 'text' | 'textarea' | 'image',
        label: string,
        name: string,
        required?: boolean,
    }[],
}
const props = defineProps<{ editor: Editor }>();
const blocks: T[] = [
    {
        name: "contact",
        label: "Contact Form",
    },
    {
        name: "info-card",
        label: "Info Card",
        settings: [
            { type: "text", label: "title", name: "title" },
            { type: "textarea", label: "Description", name: "desc" },
            { type: "text", label: "Link Title", name: "link_title" },
            { type: "text", label: "Link Value", name: "link_value" },

        ],
    },
];


function selectBlock(block: T) {
    if (block.settings) {
        const { open, close } = useModal({
            component: ModalSettingUIBlock,
            attrs: {
                label: block.label,
                settings: block.settings || [],
                onSave(data: Record<string, any>) {
                    props.editor
                        .chain()
                        .focus()
                        .command(({ commands }) => {
                            return commands.insertUIBlock(block.name, data);
                        })
                        .run();
                    close();
                },
                onClose() {
                    close();
                },
            },
        });
        open();
    } else {
        props.editor
            .chain()
            .focus()
            .command(({ commands }) => {
                return commands.insertUIBlock(block.name);
            })
            .run();
    }


}
function addGrid() {
    const desktopInput = prompt("Nhập số cột cho desktop (1-12):", "2")
  if (desktopInput === null) return // người dùng bấm Cancel

  const desktopCols = parseInt(desktopInput, 10)
  if (isNaN(desktopCols) || desktopCols < 1 || desktopCols > 12) {
    alert("Desktop cols phải trong khoảng 1-12")
    return
  }

  // nhập số cột mobile
  const mobileInput = prompt("Nhập số cột cho mobile (1-12):", "1")
  if (mobileInput === null) return // người dùng bấm Cancel

  const mobileCols = parseInt(mobileInput, 10)
  if (isNaN(mobileCols) || mobileCols < 1 || mobileCols > 12) {
    alert("Mobile cols phải trong khoảng 1-12")
    return
  }

  // gọi lệnh editor
  props.editor.commands.setGrid(desktopCols, mobileCols)

}



</script>
