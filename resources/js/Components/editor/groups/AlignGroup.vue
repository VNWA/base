<template>
  <div class="flex items-center gap-2 px-2 py-1">
    <DropdownMenu>
      <DropdownMenuTrigger as-child>
        <Button :class="isAlignActive ? activeBtn : inactiveBtn">
          <Icon :icon="currentAlign.icon" />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent class="w-40">
        <DropdownMenuLabel>Text Align</DropdownMenuLabel>
        <DropdownMenuSeparator />
        <DropdownMenuRadioGroup v-model="selectedAlign">
          <DropdownMenuRadioItem
            v-for="item in aligns"
            :key="item.value"
            :value="item.value"
            @click="setAlign(item.value)"
          >
            <Icon :icon="item.icon" class="w-4 h-4 mr-2" />
            {{ item.label }}
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

const props = defineProps<{ editor: Editor | null }>();

const aligns = [
  { label: "Left", icon: "lucide-align-left", value: "left" },
  { label: "Center", icon: "lucide-align-center", value: "center" },
  { label: "Right", icon: "lucide-align-right", value: "right" },
  { label: "Justify", icon: "lucide-align-justify", value: "justify" },
];

const selectedAlign = ref("left");

const currentAlign = computed(() => {
  for (let item of aligns) {
    if (props.editor?.isActive({ textAlign: item.value })) {
      return item;
    }
  }
  return aligns[0];
});

const isAlignActive = computed(() =>
  props.editor ? aligns.some((a) => props.editor?.isActive({ textAlign: a.value })) : false
);

function setAlign(value: string) {
  props.editor?.chain().focus().setTextAlign(value).run();
  selectedAlign.value = value;
}

const activeBtn = "px-2 py-1 rounded border border-purple-500 text-purple-600 bg-purple-100";
const inactiveBtn = "px-2 py-1 rounded border border-gray-300 text-gray-600 bg-white";
</script>
