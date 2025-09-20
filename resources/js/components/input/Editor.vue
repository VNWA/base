<template>
    <div>
        <div class="border-2 border-sky-700 dark:border-sky-900 rounded-md divide-y">

            <div v-if="editor" class="p-2  flex  items-center justify-start flex-wrap   divide-x ">
                <div class="flex items-center justify-center gap-2 px-2 py-1 ">
                    <Tooltip text="Font Bold">
                        <Button type="button" variant="outline" @click="editor.chain().focus().toggleBold().run()"
                            :disabled="!editor.can().chain().focus().toggleBold().run()"
                            :color="editor.isActive('bold') ? 'purple' : 'gray'" icon="fa6-solid:bold" size="xs" />
                    </Tooltip>
                    <Tooltip text="Font Italic">
                        <Button type="button" variant="outline" @click="editor.chain().focus().toggleItalic().run()"
                            :disabled="!editor.can().chain().focus().toggleItalic().run()" color="neutral"
                            :color="editor.isActive('italic') ? 'purple' : 'gray'" icon="fa6-solid:italic" size="xs" />
                    </Tooltip>
                    <Tooltip text="Font Strike">
                        <Button type="button" variant="outline" @click="editor.chain().focus().toggleStrike().run()"
                            :disabled="!editor.can().chain().focus().toggleStrike().run()"
                            :color="editor.isActive('strike') ? 'purple' : 'gray'" icon="fa6-solid:strikethrough"
                            size="xs" />
                    </Tooltip>


                </div>
                <div class="flex items-center justify-center gap-2 px-2 py-1 ">
                    <Button type="button" variant="outline" @click="editor.chain().focus().toggleCode().run()"
                        :disabled="!editor.can().chain().focus().toggleCode().run()" color="neutral"
                        :class="{ 'text-purple-500': editor.isActive('code') }" icon="fa6-solid:code" size="xs" />
                </div>

                <div class="flex items-center justify-center gap-2 px-2 py-1">
                    <div class="flex items-center gap-2">
                        <Tooltip text="H1 - Tiêu đề lớn nhất">
                            <Button type="button" icon="material-symbols:format-h1" size="xs" variant="outline"
                                :color="editor.isActive('heading', { level: 1 }) ? 'purple' : 'gray'"
                                @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" />
                        </Tooltip>
                        <Tooltip text="H2 - Mục lớn">
                            <Button type="button" icon="material-symbols:format-h2" size="xs" variant="outline"
                                :color="editor.isActive('heading', { level: 2 }) ? 'purple' : 'gray'"
                                @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" />
                        </Tooltip>
                        <Tooltip text="H3 - Mục phụ">
                            <Button type="button" icon="material-symbols:format-h3" size="xs" variant="outline"
                                :color="editor.isActive('heading', { level: 3 }) ? 'purple' : 'gray'"
                                @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" />
                        </Tooltip>
                        <Tooltip text="H4 - Mục nhỏ hơn">
                            <Button type="button" icon="material-symbols:format-h4" size="xs" variant="outline"
                                :color="editor.isActive('heading', { level: 4 }) ? 'purple' : 'gray'"
                                @click="editor.chain().focus().toggleHeading({ level: 4 }).run()" />
                        </Tooltip>
                        <Tooltip text="H5 - Mục nhỏ hơn nữa">
                            <Button type="button" icon="material-symbols:format-h5" size="xs" variant="outline"
                                :color="editor.isActive('heading', { level: 5 }) ? 'purple' : 'gray'"
                                @click="editor.chain().focus().toggleHeading({ level: 5 }).run()" />
                        </Tooltip>
                        <Tooltip text="H6 - Mục nhỏ nhất">
                            <Button type="button" icon="material-symbols:format-h6" size="xs" variant="outline"
                                :color="editor.isActive('heading', { level: 6 }) ? 'purple' : 'gray'"
                                @click="editor.chain().focus().toggleHeading({ level: 6 }).run()" />
                        </Tooltip>
                    </div>

                </div>
                <div class="flex items-center justify-center gap-2 px-2 py-1">
                    <Tooltip text="Căn trái văn bản">
                        <Button type="button" icon="i-lucide-align-left" variant="outline" size="xs"
                            :color="editor.isActive({ textAlign: 'left' }) ? 'purple' : 'gray'"
                            @click="editor.chain().focus().setTextAlign('left').run()" />
                    </Tooltip>

                    <Tooltip text="Căn giữa văn bản">
                        <Button type="button" icon="i-lucide-align-center" variant="outline" size="xs"
                            :color="editor.isActive({ textAlign: 'center' }) ? 'purple' : 'gray'"
                            @click="editor.chain().focus().setTextAlign('center').run()" />
                    </Tooltip>

                    <Tooltip text="Căn phải văn bản">
                        <Button type="button" icon="i-lucide-align-right" variant="outline" size="xs"
                            :color="editor.isActive({ textAlign: 'right' }) ? 'purple' : 'gray'"
                            @click="editor.chain().focus().setTextAlign('right').run()" />
                    </Tooltip>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-2 px-2 py-1">
                    <div>
                        <input class='w-5' type="color"
                            @input="editor.chain().focus().setColor($event.target.value).run()"
                            :value="editor.getAttributes('textStyle').color">
                    </div>

                    <button type="button" v-for="(item, index) in colors" :key="index" variant="solid"
                        :color="editor.isActive('textStyle', { color: item.value }) ? 'vnwa' : 'secondary'"
                        @click="editor.chain().focus().setColor(item.value).run()">
                        <div class="w-3 h-3 rounded " :style="{ backgroundColor: item.value }" />
                    </button>


                    <Tooltip text="Xóa màu ">
                        <Button type="button" icon="material-symbols:invert-colors-off"
                            @click="editor.chain().focus().unsetColor().run()" color="error" size="md" />

                    </Tooltip>


                </div>

                <div class="flex items-center justify-center gap-2 px-2 py-1">
                    <Tooltip text="Toggle bullet list" placement="top">
                        <Button type="button" icon="i-heroicons-list-bullet"
                            :color="editor.isActive('bulletList') ? 'purple' : 'gray'" variant="outline" size="xs"
                            @click="editor.chain().focus().toggleBulletList().run()" />
                    </Tooltip>

                    <Tooltip text="Toggle ordered list" placement="top">
                        <Button type="button" icon="heroicons:numbered-list-16-solid"
                            :color="editor.isActive('orderedList') ? 'purple' : 'gray'" variant="outline" size="xs"
                            @click="editor.chain().focus().toggleOrderedList().run()" />
                    </Tooltip>
                </div>
                <div class="flex items-center justify-center gap-2 px-2 py-1">
                    <Tooltip text="Add New Link">
                        <Button type="button" @click="() => isModalInsertLink = !isModalInsertLink" color="info" icon="heroicons:link"
                            size="xs" />
                    </Tooltip>
                </div>
                <div class="flex items-center justify-center gap-2 px-2 py-1 ">

                    <Tooltip text="Add Image From URL">
                        <Button type="button" @click="() => isModalInsertImage = !isModalInsertImage" color="vnwa"
                            icon="fluent:image-add-20-regular" size="xs" />
                    </Tooltip>

                    <Tooltip text="Add Image From VMedia Manager">
                        <Button type="button" @click="addImages(editor)" color="vnwa" icon="material-symbols:folder-managed"
                            label="VMedia" size="xs" />
                    </Tooltip>
                </div>



            </div>
            <div class="px-3 py-4">
                <EditorContent class="editor" :editor="editor" />

            </div>
            <div>
            </div>
        </div>

    </div>
</template>


<script setup>
import '../../../css/editor.scss'
import Image from '@tiptap/extension-image'
import ImageResize from 'tiptap-extension-resize-image';
import TextAlign from '@tiptap/extension-text-align';
import { Color } from '@tiptap/extension-color'
import { TextStyle } from '@tiptap/extension-text-style'
import BulletList from '@tiptap/extension-bullet-list'
import Link from '@tiptap/extension-link'
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Tooltip from '../Tooltip.vue';
import Button  from '../Button.vue';
import { onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue';
const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
})

const emit = defineEmits(['update:modelValue'])

const editor = ref(null)
const colors = [
    { label: 'Purple', value: '#958DF1' },
    { label: 'Red', value: '#F98181' },
    { label: 'Orange', value: '#FBBC88' },
    { label: 'Yellow', value: '#FAF594' },
    { label: 'Blue', value: '#70CFF8' },
    { label: 'Teal', value: '#94FADB' },
    { label: 'Green', value: '#B9F18D' },
]
onMounted(() => {
    editor.value = new Editor({
        extensions: [StarterKit,
            Link.configure({
                HTMLAttributes: {
                    rel: 'noopener noreferrer',
                    title: 'Vnwa Link',
                },
            }),
            TextAlign.configure({
                types: ['heading', 'paragraph'],
            }),
            Color,
            TextStyle,
            ImageResize
        ],
        content: props.modelValue,
        onUpdate: () => {
            emit('update:modelValue', editor.value.getHTML())
        },
    })
})

// Watch changes from parent
watch(
    () => props.modelValue,
    (value) => {
        if (!editor.value) return
        const isSame = editor.value.getHTML() === value
        if (!isSame) {
            editor.value.commands.setContent(value, false)
        }
    }
)


const addImages = (vnwaEditor) => {
    // const overlay = useOverlay()
    // const modal = overlay.create(VnwaMediaModalPanel)
    // modal.open({
    //     isSelect: true,
    //     onSuccess(data) {
    //         data.forEach(element => {
    //             vnwaEditor.commands.setImage({
    //                 src: $storage(element.path),
    //                 alt: 'VnwaMedia image',
    //                 title: element.name,
    //             })
    //             modal.close()
    //         });
    //     }
    // })
}

onBeforeUnmount(() => {
    unref(editor).destroy();
});
const imageFormState = reactive({
    source: '',
    alt: '',
    title: '',
})
const linkState = reactive({
    url: '',
    target: '_blank'
});
const isModalInsertLink = ref(false)
const isModalInsertImage = ref(false)
const insertImageFromUrl = () => {
    // console.log(imageFormState)
    if (!imageFormState.source && !imageFormState.alt) return
    editor.value?.chain().focus().setImage({ src: imageFormState.source, alt: imageFormState.alt, title: imageFormState.title }).run()

    imageFormState.alt = ''
    imageFormState.source = ''
    imageFormState.title = ''
    isModalInsertImage.value = false
}

const insertLink = () => {
    if (!linkState.url) return
    editor.value
        ?.chain()
        .focus()
        .extendMarkRange('link')
        .setLink({ href: linkState.url, target: linkState.target, class: 'vnwa-editor-link underline text-primary-500' })
        .run()
    linkState.url = ''
    isModalInsertLink.value = false
}


// Cleanup
onBeforeUnmount(() => {
    editor.value?.destroy()
})
</script>