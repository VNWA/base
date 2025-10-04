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
import { ref, computed, watchEffect } from "vue";
import Button from "../Button.vue";
import type { Level } from '@tiptap/extension-heading'

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
  const active = headings.find((h) =>
    props.editor?.isActive("heading", { level: h.value })
  );
  return (
    active || { label: "P", icon: "mdi:format-paragraph", value: "paragraph" }
  );
});

const isHeadingActive = computed(() =>
  props.editor
    ? headings.some((h) => props.editor?.isActive("heading", { level: h.value }))
    : false
);

function setHeading(level: Level | null) {
  if (!props.editor) return;
  const chain = props.editor.chain().focus();
  if (level) {
    chain.toggleHeading({ level }).run();
  } else {
    chain.setParagraph().run();
  }
}

// 🔑 Đồng bộ state editor → toolbar
watchEffect(() => {
  if (!props.editor) return;
  let matched = false;
  for (let item of headings) {
    if (props.editor.isActive("heading", { level: item.value })) {
      selectedHeading.value = String(item.value);
      matched = true;
      break;
    }
  }
  if (!matched) {
    selectedHeading.value = "paragraph";
  }
});
</script>

<template>
  <div class="flex items-center gap-2 px-2 py-1">
    <DropdownMenu>
      <DropdownMenuTrigger as-child>
        <Button
          :active="isHeadingActive"
          :isChild="true"
          :icon="currentHeading.icon"
        >
          {{ currentHeading.label }}
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent class="w-56">
        <DropdownMenuLabel>Heading</DropdownMenuLabel>
        <DropdownMenuSeparator />
        <DropdownMenuRadioGroup
          v-model="selectedHeading"
          @update:modelValue="
            (val) => setHeading(val === 'paragraph' ? null : Number(val))
          "
        >
          <DropdownMenuRadioItem
            v-for="item in headings"
            :key="item.value"
            :value="String(item.value)"
          >
            <Icon :icon="item.icon" class="w-4 h-4 mr-2" />
            {{ item.label }}
          </DropdownMenuRadioItem>
          <DropdownMenuRadioItem value="paragraph">
            <Icon icon="mdi:format-paragraph" class="w-4 h-4 mr-2" />
            Paragraph
          </DropdownMenuRadioItem>
        </DropdownMenuRadioGroup>
      </DropdownMenuContent>
    </DropdownMenu>
  </div>
</template>
