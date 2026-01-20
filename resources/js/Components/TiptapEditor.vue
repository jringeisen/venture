<template>
    <div class="tiptap-editor">
        <div v-if="editor" class="border border-gray-300 dark:border-neutral-600 rounded-md overflow-hidden">
            <!-- Toolbar -->
            <div class="flex flex-wrap gap-1 p-2 border-b border-gray-300 dark:border-neutral-600 bg-gray-50 dark:bg-neutral-700">
                <button
                    type="button"
                    @click="editor.chain().focus().toggleBold().run()"
                    :class="{ 'bg-gray-200 dark:bg-neutral-600': editor.isActive('bold') }"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600"
                    title="Bold"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    @click="editor.chain().focus().toggleItalic().run()"
                    :class="{ 'bg-gray-200 dark:bg-neutral-600': editor.isActive('italic') }"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600"
                    title="Italic"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4h4m-2 0v16m-4 0h8"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    @click="editor.chain().focus().toggleStrike().run()"
                    :class="{ 'bg-gray-200 dark:bg-neutral-600': editor.isActive('strike') }"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600"
                    title="Strikethrough"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4H9a3 3 0 0 0 0 6h6a3 3 0 0 1 0 6H8m0-6h8"></path>
                    </svg>
                </button>

                <div class="w-px h-6 bg-gray-300 dark:bg-neutral-500 mx-1 self-center"></div>

                <button
                    type="button"
                    @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
                    :class="{ 'bg-gray-200 dark:bg-neutral-600': editor.isActive('heading', { level: 1 }) }"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600 text-sm font-bold"
                    title="Heading 1"
                >
                    H1
                </button>

                <button
                    type="button"
                    @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                    :class="{ 'bg-gray-200 dark:bg-neutral-600': editor.isActive('heading', { level: 2 }) }"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600 text-sm font-bold"
                    title="Heading 2"
                >
                    H2
                </button>

                <button
                    type="button"
                    @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                    :class="{ 'bg-gray-200 dark:bg-neutral-600': editor.isActive('heading', { level: 3 }) }"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600 text-sm font-bold"
                    title="Heading 3"
                >
                    H3
                </button>

                <div class="w-px h-6 bg-gray-300 dark:bg-neutral-500 mx-1 self-center"></div>

                <button
                    type="button"
                    @click="editor.chain().focus().toggleBulletList().run()"
                    :class="{ 'bg-gray-200 dark:bg-neutral-600': editor.isActive('bulletList') }"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600"
                    title="Bullet List"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    @click="editor.chain().focus().toggleOrderedList().run()"
                    :class="{ 'bg-gray-200 dark:bg-neutral-600': editor.isActive('orderedList') }"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600"
                    title="Numbered List"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6h13M7 12h13M7 18h13M3 6h.01M3 12h.01M3 18h.01"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    @click="editor.chain().focus().toggleBlockquote().run()"
                    :class="{ 'bg-gray-200 dark:bg-neutral-600': editor.isActive('blockquote') }"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600"
                    title="Quote"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    @click="editor.chain().focus().toggleCodeBlock().run()"
                    :class="{ 'bg-gray-200 dark:bg-neutral-600': editor.isActive('codeBlock') }"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600"
                    title="Code Block"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </button>

                <div class="w-px h-6 bg-gray-300 dark:bg-neutral-500 mx-1 self-center"></div>

                <button
                    type="button"
                    @click="setLink"
                    :class="{ 'bg-gray-200 dark:bg-neutral-600': editor.isActive('link') }"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600"
                    title="Link"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    @click="addImage"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600"
                    title="Image"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </button>

                <div class="w-px h-6 bg-gray-300 dark:bg-neutral-500 mx-1 self-center"></div>

                <button
                    type="button"
                    @click="editor.chain().focus().undo().run()"
                    :disabled="!editor.can().undo()"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600 disabled:opacity-50"
                    title="Undo"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    @click="editor.chain().focus().redo().run()"
                    :disabled="!editor.can().redo()"
                    class="p-2 rounded hover:bg-gray-200 dark:hover:bg-neutral-600 disabled:opacity-50"
                    title="Redo"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"></path>
                    </svg>
                </button>
            </div>

            <!-- Editor Content -->
            <editor-content :editor="editor" class="prose dark:prose-invert max-w-none p-4 min-h-[200px] bg-white dark:bg-neutral-800" />
        </div>
    </div>
</template>

<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Placeholder from '@tiptap/extension-placeholder';
import { watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'Start writing...',
    },
});

const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit,
        Link.configure({
            openOnClick: false,
            HTMLAttributes: {
                class: 'text-indigo-600 hover:text-indigo-500 underline',
            },
        }),
        Image.configure({
            HTMLAttributes: {
                class: 'max-w-full h-auto rounded-lg',
            },
        }),
        Placeholder.configure({
            placeholder: props.placeholder,
        }),
    ],
    editorProps: {
        attributes: {
            class: 'focus:outline-none min-h-[200px]',
        },
    },
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
});

watch(() => props.modelValue, (newValue) => {
    if (editor.value && newValue !== editor.value.getHTML()) {
        editor.value.commands.setContent(newValue, false);
    }
});

const setLink = () => {
    const previousUrl = editor.value.getAttributes('link').href;
    const url = window.prompt('URL', previousUrl);

    if (url === null) {
        return;
    }

    if (url === '') {
        editor.value.chain().focus().extendMarkRange('link').unsetLink().run();
        return;
    }

    editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
};

const addImage = () => {
    const url = window.prompt('Image URL');

    if (url) {
        editor.value.chain().focus().setImage({ src: url }).run();
    }
};
</script>

<style>
.tiptap-editor .ProseMirror p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    float: left;
    color: #adb5bd;
    pointer-events: none;
    height: 0;
}

.tiptap-editor .ProseMirror:focus {
    outline: none;
}

.tiptap-editor .ProseMirror h1 {
    font-size: 1.875rem;
    font-weight: 700;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

.tiptap-editor .ProseMirror h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

.tiptap-editor .ProseMirror h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

.tiptap-editor .ProseMirror p {
    margin-bottom: 0.75rem;
}

.tiptap-editor .ProseMirror ul,
.tiptap-editor .ProseMirror ol {
    padding-left: 1.5rem;
    margin-bottom: 0.75rem;
}

.tiptap-editor .ProseMirror ul {
    list-style-type: disc;
}

.tiptap-editor .ProseMirror ol {
    list-style-type: decimal;
}

.tiptap-editor .ProseMirror blockquote {
    border-left: 3px solid #e5e7eb;
    padding-left: 1rem;
    margin-left: 0;
    margin-right: 0;
    font-style: italic;
}

.tiptap-editor .ProseMirror pre {
    background-color: #1f2937;
    color: #e5e7eb;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
}

.tiptap-editor .ProseMirror code {
    background-color: #f3f4f6;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

.tiptap-editor .ProseMirror pre code {
    background-color: transparent;
    padding: 0;
}
</style>
