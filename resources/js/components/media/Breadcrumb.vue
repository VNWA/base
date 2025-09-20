<template>
    <div class="relative w-full media-breadcrumb p-3 border rounded-lg
             bg-white dark:bg-gray-900 shadow-sm">
        <!-- Overlay loading -->
        <div v-show="loading" class="absolute inset-0 flex items-center justify-start
               bg-black/60 backdrop-blur-sm z-10">
            <div class="ms-[100px] relative">
                <div class="loader"></div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <ul class="flex items-center flex-wrap gap-1 text-sm">
            <!-- Root -->
            <li>
                <button class="flex items-center gap-1 px-2 py-1 rounded-md font-medium
                   bg-gray-100 text-gray-800 hover:bg-gray-200
                   dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700
                   transition-colors" @click="choosePath('')">
                    <Icon icon="material-symbols:folder" class="text-lg text-yellow-500" />
                    Root
                </button>
            </li>

            <!-- Items -->
            <li v-for="(item, index) in data" :key="index" class="flex items-center gap-1">
                <span class="text-gray-500 dark:text-gray-400">/</span>
                <button class="px-2 py-1 rounded-md font-medium transition-colors
         hover:bg-gray-200 dark:hover:bg-gray-700" :class="index === data.length - 1
            ? 'bg-sky-500 text-white hover:bg-sky-600 dark:bg-sky-600 dark:hover:bg-sky-500'
            : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200'
            " @click="choosePath(item.value)">
                    {{ item.label }}
                </button>
            </li>
        </ul>
    </div>
</template>

<script lang="ts" setup>
import { Icon } from "@iconify/vue";

interface T {
    label: string;
    value: string;
}
const props = defineProps<{
    data: T[];
    loading?: boolean;
}>();
const emit = defineEmits(["choosePath"]);

const choosePath = (path: string) => {
    emit("choosePath", path);
};
</script>

<style scoped>
.media-breadcrumb .loader {
    width: 15px;
    aspect-ratio: 1;
    border-radius: 50%;
    clip-path: inset(-45px);
    box-shadow: -60px 15px, -60px 15px, -60px 15px;
    transform: translateY(-15px);
    animation: l19 1s infinite linear;
}

@keyframes l19 {
    16.67% {
        box-shadow: -60px 15px, -60px 15px, 19px 15px;
    }

    33.33% {
        box-shadow: -60px 15px, 0px 15px, 19px 15px;
    }

    40%,
    60% {
        box-shadow: -19px 15px, 0px 15px, 19px 15px;
    }

    66.67% {
        box-shadow: -19px 15px, 0px 15px, 60px 15px;
    }

    83.33% {
        box-shadow: -19px 15px, 60px 15px, 60px 15px;
    }

    100% {
        box-shadow: 60px 15px, 60px 15px, 60px 15px;
    }
}
</style>
