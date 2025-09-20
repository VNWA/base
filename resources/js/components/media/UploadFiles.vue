<template>
    <div>
        <Button size="sm" color="green" :loading="loading" icon="material-symbols:upload-rounded"
            @click="inputRef?.click()" />
        <input ref="inputRef" type="file" class="hidden" multiple @change="onSelect" />
    </div>
</template>

<script lang="ts" setup>
import { ref, useTemplateRef } from "vue";
import Button from "../Button.vue";
import axios from "axios";
import { toast } from "@/lib/utils";

const props = defineProps<{
    dir_path: string;
}>();
const emit = defineEmits(['success']);
const inputRef = useTemplateRef("inputRef");
const loading = ref(false);

// config
const MAX_FILES = 10; // tối đa số file
const MAX_SIZE_MB = 10; // dung lượng tối đa 1 file (MB)

const onSelect = async (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (!target.files) return;

    const files = Array.from(target.files);
    target.value = ""; // reset input

    // Kiểm tra số lượng file
    if (files.length > MAX_FILES) {
        toast(`Bạn chỉ được upload tối đa ${MAX_FILES} file`, "error");
        return;
    }

    // Kiểm tra dung lượng từng file
    for (const file of files) {
        if (file.size > MAX_SIZE_MB * 1024 * 1024) {
            toast(
                `File "${file.name}" vượt quá dung lượng ${MAX_SIZE_MB}MB`,
                "error"
            );
            return;
        }
    }

    loading.value = true;

    try {
        const formData = new FormData();
        files.forEach((file) => {
            formData.append("files[]", file); // backend nhận dạng mảng
        });
        formData.append("dir_path", props.dir_path);

        const res = await axios.post(route("vnwa.media.upload"), formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
            params: {
                dir_path: props.dir_path
            }
        });
        emit('success');
        toast(res.data.message, "success");
    } catch (err: any) {
        toast(err.response?.data?.message || "Upload thất bại", "error");
    } finally {
        loading.value = false;
    }
};
</script>
