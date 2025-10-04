<template>
    <div class="flex flex-col">
        <!-- Label -->
        <label v-if="label" class="mb-1 text-sm font-medium text-gray-700 capitalize dark:text-gray-300">
            {{ label }}
        </label>

        <div
            class="rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 transition-all duration-200 resize-none divide-y border border-gray-300 dark:border-gray-600 dark:focus:ring-offset-gray-800">
            <ToolBar v-if="editor" :editor="editor" />

            <div class="">
                <EditorContent class="editor" :editor="editor" />
            </div>
        </div>

        <p v-if="error" class="mt-1 text-xs text-red-600 dark:text-red-400">
            {{ error }}
        </p>
    </div>
</template>

<script setup>
import "../../../css/editor.scss";
import Image from "@tiptap/extension-image";
import ImageResize from "tiptap-extension-resize-image";
import TextAlign from "@tiptap/extension-text-align";
import { Color } from "@tiptap/extension-color";
import { FontSize, TextStyle } from "@tiptap/extension-text-style";
import BulletList from "@tiptap/extension-bullet-list";
import Link from "@tiptap/extension-link";
import { useEditor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import Tooltip from "../Tooltip.vue";
import { computed, watch } from "vue";
import { Icon } from "@iconify/vue";
import { TableKit } from "@tiptap/extension-table";
import ToolBar from "../editor/ToolBar.vue";
import { Grid, Col } from "@/utils/editor/grid";
import { UIBlock } from "@/utils/editor/UIBlock";


const props = defineProps({
    modelValue: { type: String, default: "" },
    label: { type: String, default: "" },
    error: { type: String, default: "" },
});

const emit = defineEmits(["update:modelValue"]);

const editor = useEditor({
    extensions: [
        StarterKit,
        Link.configure({
            HTMLAttributes: {
                rel: "noopener noreferrer",
                title: "Vnwa Link",
            },
        }),
        TextAlign.configure({
            types: ["heading", "paragraph"],
        }),
        Color,
        TextStyle,
        FontSize,
        TableKit.configure({
            table: { resizable: true },
        }),
        Grid,
        Col,
        UIBlock,
        ImageResize,
        Image.configure({
            inline: false,
            allowBase64: true,
            HTMLAttributes: { class: "rounded-lg" },
        }),
    ],
    editorProps: {
        attributes: {
            class:
                "prose prose-sm sm:prose-base lg:prose-lg xl:prose-2xl m-5 focus:outline-none",
        },
    },
    content: props.modelValue,
    onUpdate({ editor }) {
        emit("update:modelValue", editor.getHTML());
    },
});





// Watch modelValue từ ngoài
watch(
    () => props.modelValue,
    (value) => {
        if (!editor.value) return;
        const isSame = editor.value.getHTML() === value;
        if (!isSame) {
            editor.value.commands.setContent(value, false);
        }
    }
);
</script>


<style lang="scss">

</style>