<template>
    <div class="flex flex-col">
        <!-- Label -->
        <label v-if="label" class="mb-1 text-sm font-medium text-gray-700 capitalize dark:text-gray-300">
            {{ label }}
        </label>
        <div class="rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 transition-all duration-200 resize-none divide-y"
            :class="textareaClasses">



            <div v-if="editor" class="p-2 flex flex-wrap items-center gap-2 divide-x">

                <!-- Font Style -->
                <div class="flex items-center gap-2 px-2 py-1">
                    <Tooltip text="Font Bold">
                        <button type="button" @click="editor.chain().focus().toggleBold().run()"
                            :disabled="!editor.can().chain().focus().toggleBold().run()"
                            :class="editor.isActive('bold') ? activeBtn : inactiveBtn">
                            <Icon icon="fa6-solid:bold" class="w-4 h-4" />
                        </button>
                    </Tooltip>

                    <Tooltip text="Font Italic">
                        <button type="button" @click="editor.chain().focus().toggleItalic().run()"
                            :disabled="!editor.can().chain().focus().toggleItalic().run()"
                            :class="editor.isActive('italic') ? activeBtn : inactiveBtn">
                            <Icon icon="fa6-solid:italic" class="w-4 h-4" />
                        </button>
                    </Tooltip>

                    <Tooltip text="Font Strike">
                        <button type="button" @click="editor.chain().focus().toggleStrike().run()"
                            :disabled="!editor.can().chain().focus().toggleStrike().run()"
                            :class="editor.isActive('strike') ? activeBtn : inactiveBtn">
                            <Icon icon="fa6-solid:strikethrough" class="w-4 h-4" />
                        </button>
                    </Tooltip>
                </div>

                <!-- Code -->
                <div class="flex items-center gap-2 px-2 py-1">
                    <button type="button" @click="editor.chain().focus().toggleCode().run()"
                        :disabled="!editor.can().chain().focus().toggleCode().run()"
                        :class="editor.isActive('code') ? activeBtn : inactiveBtn">
                        <Icon icon="fa6-solid:code" class="w-4 h-4" />
                    </button>
                </div>

                <!-- Headings -->
                <div class="flex items-center gap-2 px-2 py-1">
                    <Tooltip text="H1">
                        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
                            :class="editor.isActive('heading', { level: 1 }) ? activeBtn : inactiveBtn">
                            <Icon icon="material-symbols:format-h1" class="w-4 h-4" />
                        </button>
                    </Tooltip>
                    <Tooltip text="H2">
                        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                            :class="editor.isActive('heading', { level: 2 }) ? activeBtn : inactiveBtn">
                            <Icon icon="material-symbols:format-h2" class="w-4 h-4" />
                        </button>
                    </Tooltip>
                    <Tooltip text="H3">
                        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                            :class="editor.isActive('heading', { level: 3 }) ? activeBtn : inactiveBtn">
                            <Icon icon="material-symbols:format-h3" class="w-4 h-4" />
                        </button>
                    </Tooltip>
                    <!-- Thêm H4-H6 nếu muốn -->
                </div>

                <!-- Text Align -->
                <div class="flex items-center gap-2 px-2 py-1">
                    <Tooltip text="Align Left">
                        <button type="button" @click="editor.chain().focus().setTextAlign('left').run()"
                            :class="editor.isActive({ textAlign: 'left' }) ? activeBtn : inactiveBtn">
                            <Icon icon="lucide-align-left" class="w-4 h-4" />
                        </button>
                    </Tooltip>
                    <Tooltip text="Align Center">
                        <button type="button" @click="editor.chain().focus().setTextAlign('center').run()"
                            :class="editor.isActive({ textAlign: 'center' }) ? activeBtn : inactiveBtn">
                            <Icon icon="lucide-align-center" class="w-4 h-4" />
                        </button>
                    </Tooltip>
                    <Tooltip text="Align Right">
                        <button type="button" @click="editor.chain().focus().setTextAlign('right').run()"
                            :class="editor.isActive({ textAlign: 'right' }) ? activeBtn : inactiveBtn">
                            <Icon icon="lucide-align-right" class="w-4 h-4" />
                        </button>
                    </Tooltip>
                </div>

                <!-- Color -->
                <div class="flex items-center gap-2 px-2 py-1">
                    <input type="color" class="w-6 h-6 p-0 border rounded"
                        @input="editor.chain().focus().setColor($event.target.value).run()"
                        :value="editor.getAttributes('textStyle').color" />

                    <button v-for="(c, i) in colors" :key="i" type="button" :style="{ backgroundColor: c.value }"
                        class="w-6 h-6 rounded border" @click="editor.chain().focus().setColor(c.value).run()" />

                    <Tooltip text="Remove Color">
                        <button type="button" @click="editor.chain().focus().unsetColor().run()"
                            class="w-6 h-6 rounded border border-red-500 text-red-500 flex items-center justify-center">
                            <Icon icon="material-symbols:invert-colors-off" class="w-4 h-4" />
                        </button>
                    </Tooltip>
                </div>

                <!-- Lists -->
                <div class="flex items-center gap-2 px-2 py-1">
                    <Tooltip text="Bullet List">
                        <button type="button" @click="editor.chain().focus().toggleBulletList().run()"
                            :class="editor.isActive('bulletList') ? activeBtn : inactiveBtn">
                            <Icon icon="heroicons:list-bullet-16-solid" class="w-4 h-4" />
                        </button>
                    </Tooltip>
                    <Tooltip text="Ordered List">
                        <button type="button" @click="editor.chain().focus().toggleOrderedList().run()"
                            :class="editor.isActive('orderedList') ? activeBtn : inactiveBtn">
                            <Icon icon="heroicons:numbered-list-16-solid" class="w-4 h-4" />
                        </button>
                    </Tooltip>
                </div>

                <!-- Links & Images -->
                <div class="flex items-center gap-2 px-2 py-1">
                    <Tooltip text="Add Link">
                        <button type="button" @click="addNewLink()"
                            class="px-2 py-1 rounded border bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
                            <Icon icon="material-symbols:add-link" class="w-4 h-4" />
                        </button>
                    </Tooltip>

                    <Tooltip text="Add Image">
                        <button type="button" @click="addImages()"
                            class="px-2 py-1 rounded border bg-purple-100 text-purple-600 hover:bg-purple-200 transition flex items-center justify-center gap-2">
                            <Icon icon="material-symbols:folder-managed" class="w-4 h-4 " />
                            VMedia
                        </button>
                    </Tooltip>
                </div>

            </div>


            <div class="px-3 py-4">
                <EditorContent class="editor" :editor="editor" />

            </div>
            <div>
            </div>
        </div>
        <p v-if="error" class="mt-1 text-xs text-red-600 dark:text-red-400">
            {{ error }}
        </p>
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
import { computed, onBeforeUnmount, onMounted, reactive, ref, unref, watch } from 'vue';
import { useModal } from 'vue-final-modal';
import ModalMedia from '../modal/ModalMedia.vue';
import ModalInsertLink from '../modal/ModalInsertLink.vue';
import { Icon } from '@iconify/vue';
import { storage } from '@/lib/utils';
const activeBtn = "px-2 py-1 rounded border border-purple-500 text-purple-600 bg-purple-100 hover:bg-purple-200 dark:border-purple-400 dark:text-purple-400 dark:bg-purple-900 dark:hover:bg-purple-800 transition"
const inactiveBtn = "px-2 py-1 rounded border border-gray-300 text-gray-600 bg-white hover:bg-gray-100 dark:border-gray-600 dark:text-gray-400 dark:bg-gray-900 dark:hover:bg-gray-800 transition"



const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: ''
    },
    error: {
        type: String,
        default: ''
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


const addImages = () => {

    const { open, close } = useModal({
        component: ModalMedia,
        attrs: {
            types: ['image'],
            onSuccess(items) {
                const html = items.map(el => `<img src="${storage(el.path)}"  alt="${el.name}" title="${el.name}" />`).join('<p></p>')
                editor.value.chain().focus().insertContent(html).run()
                close()
            },
            onClose() {
                close()
            },
        },
    })
    open()

}

onBeforeUnmount(() => {
    unref(editor).destroy();
});


const { open: addNewLink, close: closeModalLink } = useModal({
    component: ModalInsertLink,
    attrs: {
        onSuccess(item) {
            editor.value
                ?.chain()
                .focus()
                .extendMarkRange('link')
                .setLink({ href: item.url, target: item.target, class: 'vnwa-editor-link underline text-primary-500' })
                .run()
            closeModalLink()
        },
        onClose() {
            closeModalLink()
        },
    },
})



// Cleanup
onBeforeUnmount(() => {
    editor.value?.destroy()
})

const textareaClasses = computed(() => {
    const base = 'rounded-md shadow-sm w-full focus:outline-none focus:ring-2 focus:ring-offset-1 transition-all duration-200';
    const border = props.error
        ? 'border-red-500 dark:border-red-400 focus:ring-red-500 dark:focus:ring-red-400'
        : 'border border-gray-300 dark:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-400';
    const bgText = 'bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100';
    return `${base} ${border} ${bgText}`;
});

</script>