<template>
  <div class="border border-gray-300 dark:border-gray-600 rounded-md p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <label
        v-if="label"
        class="mb-1 text-sm font-medium text-gray-700 capitalize dark:text-gray-300"
      >
        {{ label || "Images" }}
      </label>
      <Button
        @click="open()"
        type="button"
        size="xs"
        icon="material-symbols:add"
      />
    </div>

    <!-- Draggable grid -->
    <draggableComponent
      v-model="images"
      :options="{ animation: 200, group: 'images' }"
      :class="`grid gap-4 ${gridClass}`"
        itemKey="index"
    >
      <template #item="{ element: item, index }">
        <div class="relative cursor-move group" :key="index">
          <!-- Khung ảnh -->
          <div
            class="w-full aspect-square flex items-center justify-center border rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-800"
          >
            <img
              :src="storage(item)"
              alt="Uploaded image"
              class="max-w-full max-h-full object-contain transition-transform duration-300 ease-in-out group-hover:scale-105"
            />
          </div>

          <!-- Nút xóa (hover mới hiện) -->
          <div
            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transform translate-y-[-10px] group-hover:translate-y-0 transition-all duration-300 ease-out"
          >
            <Tooltip text="Delete">
              <Button
                type="button"
                color="red"
                icon="material-symbols:delete-outline"
                size="xs"
                variant="solid"
                class="rounded-full shadow-md"
                @click="removeImage(index)"
              />
            </Tooltip>
          </div>
        </div>
      </template>
    </draggableComponent>
  </div>
</template>

<script lang="ts" setup>
import { computed, ref } from "vue";
import { useModal } from "vue-final-modal";
import ModalMedia from "../modal/ModalMedia.vue";
import { storage } from "@/lib/utils";
import Button from "../Button.vue";
import Tooltip from "../Tooltip.vue";
import draggableComponent from "vuedraggable";

const props = defineProps<{
  modelValue?: string[];
  error?: string;
  label?: string;
  grid: 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8;
}>();


const emit = defineEmits(["update:modelValue"]);

const images = computed({
  get() {
    return props.modelValue ?? []; // luôn trả về []
  },
  set(value) {
    emit("update:modelValue", value);
  },
});


const gridClass = `grid-cols-${props.grid}`;

const { open, close } = useModal({
  component: ModalMedia,
  attrs: {
    types: ["image"],
    onSuccess(items) {

              let newImages = items.map((img: any) => img.path);

         images.value = [...(images.value ?? []), ...newImages];
      close();
    },
    onClose() {
      close();
    },
  },
});

const removeImage = (index: number) => {
  images.value = images.value?.filter((_, i) => i !== index) ?? [];
};
</script>
