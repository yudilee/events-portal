<template>
  <div class="rounded-2xl border border-slate-700/80 light:border-slate-300 bg-slate-950/60 light:bg-white overflow-hidden focus-within:border-teal-400 transition-colors shadow-sm">
    <!-- Toolbar -->
    <div v-if="editor" class="flex flex-wrap items-center gap-1 p-2 bg-slate-900/90 light:bg-slate-100 border-b border-slate-800/80 light:border-slate-300 text-slate-300 light:text-slate-700">
      <!-- Headings -->
      <button
        type="button"
        @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
        :class="[
          'px-2.5 py-1 rounded-lg text-xs font-bold transition-colors',
          editor.isActive('heading', { level: 2 }) ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Heading 2"
      >
        H2
      </button>

      <button
        type="button"
        @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
        :class="[
          'px-2.5 py-1 rounded-lg text-xs font-bold transition-colors',
          editor.isActive('heading', { level: 3 }) ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Heading 3"
      >
        H3
      </button>

      <div class="w-px h-5 bg-slate-800 light:bg-slate-300 mx-1"></div>

      <!-- Basic Formatting -->
      <button
        type="button"
        @click="editor.chain().focus().toggleBold().run()"
        :class="[
          'p-1.5 rounded-lg text-xs transition-colors',
          editor.isActive('bold') ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Bold"
      >
        <Bold class="w-4 h-4" />
      </button>

      <button
        type="button"
        @click="editor.chain().focus().toggleItalic().run()"
        :class="[
          'p-1.5 rounded-lg text-xs transition-colors',
          editor.isActive('italic') ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Italic"
      >
        <Italic class="w-4 h-4" />
      </button>

      <button
        type="button"
        @click="editor.chain().focus().toggleUnderline().run()"
        :class="[
          'p-1.5 rounded-lg text-xs transition-colors',
          editor.isActive('underline') ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Underline"
      >
        <UnderlineIcon class="w-4 h-4" />
      </button>

      <button
        type="button"
        @click="editor.chain().focus().toggleStrike().run()"
        :class="[
          'p-1.5 rounded-lg text-xs transition-colors',
          editor.isActive('strike') ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Strikethrough"
      >
        <Strikethrough class="w-4 h-4" />
      </button>

      <div class="w-px h-5 bg-slate-800 light:bg-slate-300 mx-1"></div>

      <!-- Lists -->
      <button
        type="button"
        @click="editor.chain().focus().toggleBulletList().run()"
        :class="[
          'p-1.5 rounded-lg text-xs transition-colors',
          editor.isActive('bulletList') ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Bullet List"
      >
        <List class="w-4 h-4" />
      </button>

      <button
        type="button"
        @click="editor.chain().focus().toggleOrderedList().run()"
        :class="[
          'p-1.5 rounded-lg text-xs transition-colors',
          editor.isActive('orderedList') ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Numbered List"
      >
        <ListOrdered class="w-4 h-4" />
      </button>

      <button
        type="button"
        @click="editor.chain().focus().toggleBlockquote().run()"
        :class="[
          'p-1.5 rounded-lg text-xs transition-colors',
          editor.isActive('blockquote') ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Blockquote"
      >
        <Quote class="w-4 h-4" />
      </button>

      <div class="w-px h-5 bg-slate-800 light:bg-slate-300 mx-1"></div>

      <!-- Alignment -->
      <button
        type="button"
        @click="editor.chain().focus().setTextAlign('left').run()"
        :class="[
          'p-1.5 rounded-lg text-xs transition-colors',
          editor.isActive({ textAlign: 'left' }) ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Align Left"
      >
        <AlignLeft class="w-4 h-4" />
      </button>

      <button
        type="button"
        @click="editor.chain().focus().setTextAlign('center').run()"
        :class="[
          'p-1.5 rounded-lg text-xs transition-colors',
          editor.isActive({ textAlign: 'center' }) ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Align Center"
      >
        <AlignCenter class="w-4 h-4" />
      </button>

      <button
        type="button"
        @click="editor.chain().focus().setTextAlign('right').run()"
        :class="[
          'p-1.5 rounded-lg text-xs transition-colors',
          editor.isActive({ textAlign: 'right' }) ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Align Right"
      >
        <AlignRight class="w-4 h-4" />
      </button>

      <div class="w-px h-5 bg-slate-800 light:bg-slate-300 mx-1"></div>

      <!-- Link & Image Insert -->
      <button
        type="button"
        @click="setLink"
        :class="[
          'p-1.5 rounded-lg text-xs transition-colors',
          editor.isActive('link') ? 'bg-teal-500 text-slate-950' : 'hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black'
        ]"
        title="Insert Link"
      >
        <LinkIcon class="w-4 h-4" />
      </button>

      <button
        type="button"
        @click="addImage"
        class="p-1.5 rounded-lg text-xs hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black transition-colors"
        title="Insert Image by URL"
      >
        <ImageIcon class="w-4 h-4" />
      </button>

      <div class="w-px h-5 bg-slate-800 light:bg-slate-300 mx-1"></div>

      <!-- Undo & Redo -->
      <button
        type="button"
        @click="editor.chain().focus().undo().run()"
        :disabled="!editor.can().undo()"
        class="p-1.5 rounded-lg text-xs hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black disabled:opacity-30 disabled:hover:bg-transparent transition-colors"
        title="Undo"
      >
        <Undo class="w-4 h-4" />
      </button>

      <button
        type="button"
        @click="editor.chain().focus().redo().run()"
        :disabled="!editor.can().redo()"
        class="p-1.5 rounded-lg text-xs hover:bg-slate-800 light:hover:bg-slate-200 hover:text-white light:hover:text-black disabled:opacity-30 disabled:hover:bg-transparent transition-colors"
        title="Redo"
      >
        <Redo class="w-4 h-4" />
      </button>
    </div>

    <!-- Editor Surface -->
    <editor-content
      :editor="editor"
      class="p-4 min-h-[220px] max-h-[500px] overflow-y-auto text-sm text-slate-200 light:text-slate-900 bg-slate-950/40 light:bg-white focus:outline-none prose-hartono"
    />
  </div>
</template>

<script setup>
import { watch, onBeforeUnmount } from 'vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';
import Underline from '@tiptap/extension-underline';
import TextAlign from '@tiptap/extension-text-align';

import {
  Bold,
  Italic,
  Underline as UnderlineIcon,
  Strikethrough,
  List,
  ListOrdered,
  Quote,
  AlignLeft,
  AlignCenter,
  AlignRight,
  Link as LinkIcon,
  Image as ImageIcon,
  Undo,
  Redo
} from 'lucide-vue-next';

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: 'Compose rich event content, rundown details, executive remarks, or media highlights...',
  },
});

const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
    Underline,
    Image.configure({
      inline: true,
      allowBase64: true,
    }),
    Link.configure({
      openOnClick: false,
      HTMLAttributes: {
        target: '_blank',
        rel: 'noopener noreferrer',
      },
    }),
    Placeholder.configure({
      placeholder: props.placeholder,
    }),
    TextAlign.configure({
      types: ['heading', 'paragraph'],
    }),
  ],
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML());
  },
});

watch(
  () => props.modelValue,
  (val) => {
    if (editor.value && editor.value.getHTML() !== val) {
      editor.value.commands.setContent(val || '', false);
    }
  }
);

const setLink = () => {
  const previousUrl = editor.value.getAttributes('link').href;
  const url = window.prompt('Enter URL:', previousUrl);
  if (url === null) return;
  if (url === '') {
    editor.value.chain().focus().extendMarkRange('link').unsetLink().run();
    return;
  }
  editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
};

const addImage = () => {
  const url = window.prompt('Enter image URL (HTTPS / storage path):');
  if (url) {
    editor.value.chain().focus().setImage({ src: url }).run();
  }
};

onBeforeUnmount(() => {
  if (editor.value) {
    editor.value.destroy();
  }
});
</script>

<style>
.ProseMirror {
  outline: none;
  min-height: 180px;
}
.ProseMirror p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: #64748b;
  pointer-events: none;
  height: 0;
}
</style>
