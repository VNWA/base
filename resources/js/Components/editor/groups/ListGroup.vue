<template>
  <div class="flex items-center gap-2 px-2 py-1">
    <DropdownMenu>
      <DropdownMenuTrigger as-child>
        <Button :class="isListActive ? activeBtn : inactiveBtn">
          <Icon :icon="currentList.icon" />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent class="w-40">
        <DropdownMenuLabel>Lists</DropdownMenuLabel>
        <DropdownMenuSeparator />
        <DropdownMenuRadioGroup v-model="selectedList">
          <DropdownMenuRadioItem
            v-for="item in lists"
            :key="item.value"
            :value="item.value"
            @click="setList(item.value)"
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

const lists = [
  { label: "Bullet", icon: "heroicons:list-bullet-16-solid", value: "bulletList" },
  { label: "Ordered", icon: "heroicons:numbered-list-16-solid", value: "orderedList" },
];

const selectedList = ref("bulletList");

const currentList = computed(() => {
  for (let item of lists) {
    if (props.editor?.isActive(item.value)) {
      return item;
    }
  }
  return lists[0];
});

const isListActive = computed(() =>
  props.editor ? lists.some((l) => props.editor?.isActive(l.value)) : false
);

function setList(type: string) {
  if (type === "bulletList") {
    props.editor?.chain().focus().toggleBulletList().run();
  } else if (type === "orderedList") {
    props.editor?.chain().focus().toggleOrderedList().run();
  }
  selectedList.value = type;
}

const activeBtn = "px-2 py-1 rounded border border-purple-500 text-purple-600 bg-purple-100";
const inactiveBtn = "px-2 py-1 rounded border border-gray-300 text-gray-600 bg-white";
</script>
