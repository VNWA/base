<template>
    <div class="flex items-center gap-2 px-2 py-1">
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <button :active="editor?.getAttributes('textStyle').color" :isChild="true">
                    <Icon icon="material-symbols:format-color-text" class="size-6 text-gray-500"
                        :style="editor?.getAttributes('textStyle')" />
                </button>
            </DropdownMenuTrigger>

            <DropdownMenuContent class="w-48 p-2">
                <DropdownMenuLabel>Text Color</DropdownMenuLabel>
                <DropdownMenuSeparator />

                <!-- Color Picker -->
                <div class="flex items-center gap-2 mb-2">
                    <input type="color" class="w-8 h-8 p-0 border rounded cursor-pointer" @input="onColorInput($event)"
                        :value="editor?.getAttributes('textStyle').color" />
                </div>

                <!-- Preset Colors -->
                <div class="grid grid-cols-6 gap-2 mb-2">
                    <button v-for="(c, i) in colors" :key="i" type="button" :style="{ backgroundColor: c.value }"
                        class="w-6 h-6 rounded border hover:scale-110 transition"
                        @click="editor?.chain().focus().setColor(c.value).run()" />
                </div>

                <!-- Remove Color -->
                <DropdownMenuSeparator />
                <DropdownMenuItem as-child>
                    <button type="button" class="flex items-center gap-2 w-full text-red-500"
                        @click="editor?.chain().focus().unsetColor().run()">
                        <Icon icon="material-symbols:invert-colors-off" class="w-4 h-4" />
                        Remove Color
                    </button>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>

<script setup lang="ts">
import { Editor } from "@tiptap/vue-3";
import { Icon } from "@iconify/vue";
import DropdownMenu from "../../ui/dropdown-menu/DropdownMenu.vue";
import DropdownMenuTrigger from "../../ui/dropdown-menu/DropdownMenuTrigger.vue";
import DropdownMenuContent from "../../ui/dropdown-menu/DropdownMenuContent.vue";
import DropdownMenuLabel from "../../ui/dropdown-menu/DropdownMenuLabel.vue";
import DropdownMenuSeparator from "../../ui/dropdown-menu/DropdownMenuSeparator.vue";
import DropdownMenuItem from "../../ui/dropdown-menu/DropdownMenuItem.vue";
import { computed } from "vue";

const props = defineProps<{ editor: Editor | null }>();
const colors = [
    { label: "Purple", value: "#958DF1" },
    { label: "Red", value: "#F98181" },
    { label: "Orange", value: "#FBBC88" },
    { label: "Yellow", value: "#FAF594" },
    { label: "Blue", value: "#70CFF8" },
    { label: "Teal", value: "#94FADB" },
    { label: "Green", value: "#B9F18D" },
];

function onColorInput(event: Event) {
    const target = event.target as HTMLInputElement;
    props.editor?.chain().focus().setColor(target.value).run();
}

const activeBtn =
    "px-2 py-1 rounded border border-purple-500 text-purple-600 bg-purple-100 hover:bg-purple-200 dark:border-purple-400 dark:text-purple-400 dark:bg-purple-900 dark:hover:bg-purple-800 transition";
const inactiveBtn =
    "px-2 py-1 rounded border border-gray-300 text-gray-600 bg-white hover:bg-gray-100 dark:border-gray-600 dark:text-gray-400 dark:bg-gray-900 dark:hover:bg-gray-800 transition";
</script>
