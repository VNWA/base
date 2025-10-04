<template>
    <div class="flex items-center gap-2 px-2 py-1">
        <Tooltip text="Code">
            <Button :active="editor?.isActive('code')" @click="editor?.chain().focus().toggleCode().run()"
                iconClass="text-lg mx-2" icon="fa6-solid:code" />
        </Tooltip>

    </div>
</template>

<script setup lang="ts">
import { Editor } from "@tiptap/vue-3";
import { Icon } from "@iconify/vue";
import Button from "../Button.vue";
import Tooltip from "@/components/Tooltip.vue";

defineProps<{ editor: Editor | null }>();

</script>
