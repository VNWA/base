<template>
    <div class="flex items-center gap-2 px-2 py-1">
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Button :active="isHeadingActive" :isChild="true" :icon="currentHeading.icon" />
            </DropdownMenuTrigger>
            <DropdownMenuContent class="w-56">
                <DropdownMenuLabel>Heading</DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuRadioGroup v-model="selectedHeading">
                    <DropdownMenuRadioItem v-for="item in headings" :key="item.value" :value="String(item.value)"
                        @click="setHeading(item.value)">
                        <Icon :icon="item.icon" class="w-4 h-4 mr-2" />
                        {{ item.label }}
                    </DropdownMenuRadioItem>
                    <DropdownMenuRadioItem value="paragraph" @click="setHeading(null)">
                        <Icon icon="rmaterial-symbols:h-mobiledata" class="w-4 h-4 mr-2" />
                        Heading
                    </DropdownMenuRadioItem>
                </DropdownMenuRadioGroup>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>

<script setup lang="ts">
import { Editor } from "@tiptap/vue-3";
import { Icon } from "@iconify/vue";
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
} from "../../ui/dropdown-menu";
import { ref, computed } from "vue";
import Button from "../Button.vue";

const props = defineProps<{ editor: Editor | null }>();

const headings = [
    { label: "H1", icon: "material-symbols:format-h1", value: 1 },
    { label: "H2", icon: "material-symbols:format-h2", value: 2 },
    { label: "H3", icon: "material-symbols:format-h3", value: 3 },
    { label: "H4", icon: "material-symbols:format-h4", value: 4 },
    { label: "H5", icon: "material-symbols:format-h5", value: 5 },
    { label: "H6", icon: "material-symbols:format-h6", value: 6 },
];

const selectedHeading = ref("paragraph");

const currentHeading = computed(() => {
    for (let item of headings) {
        if (props.editor?.isActive("heading", { level: item.value })) {
            return item;
        }
    }
    return { label: "P", icon: "material-symbols:h-mobiledata", value: "paragraph" };
});

const isHeadingActive = computed(() =>
    props.editor ? headings.some((h) => props.editor?.isActive("heading", { level: h.value })) : false
);

function setHeading(level: number | null) {
    if (!props.editor) return;
    if (level) {
        props.editor.chain().focus().toggleHeading({ level: level as any }).run();
        selectedHeading.value = String(level);
    } else {
        props.editor.chain().focus().setParagraph().run();
        selectedHeading.value = "paragraph";
    }
}


</script>
