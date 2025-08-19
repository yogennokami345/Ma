<script lang="ts" setup>
import AvatarButton from '../ui/avatarButton/AvatarButton.vue';
import { useEditor, EditorContent } from '@tiptap/vue-3'
import Bold from '@tiptap/extension-bold'
import Italic from '@tiptap/extension-italic'
import Underline from '@tiptap/extension-underline'
import Spoiler from '../TextEditor/tiptapSpoiler'
import Strike from '@tiptap/extension-strike'
import CharacterCount from '@tiptap/extension-character-count'
import Document from '@tiptap/extension-document'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import Image from '@tiptap/extension-image'

const props = defineProps<{ comment: any }>();

const limit = 255;

function content() {
    try {
        return JSON.parse(props.comment.comment)
    } catch (error) {
        // return props.comment.comment
    }
}

const editor = useEditor({
    content: content(),
    editable: false,
    editorProps: {
        attributes: {
            class: 'outline-none',
        },
    },
    extensions: [
        Document,
        Text,
        Paragraph,
        Bold,
        Italic,
        Underline,
        Strike,
        Spoiler,
        Image,
        CharacterCount.configure({
            limit: limit,
        }),
    ],
})
</script>

<template>
    <div class="flex gap-3 rounded-lg p-5 border border-input">
        <AvatarButton v-if="comment.commentator" :auth="comment.commentator" :trigger="true" />
        <div class="flex flex-col gap-3">
            <p v-if="comment.commentator">{{ comment.commentator.name }}</p>
            <p v-else>Autor não disponível</p>
            <editor-content class="h-full w-full" :editor="editor" />
        </div>
    </div>
</template>
