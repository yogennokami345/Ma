<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3'
import Bold from '@tiptap/extension-bold'
import Italic from '@tiptap/extension-italic'
import Underline from '@tiptap/extension-underline'
import Spoiler from './tiptapSpoiler'
import Strike from '@tiptap/extension-strike'
import { defineEmits } from 'vue'
import Button from '../ui/button/Button.vue'
import CharacterCount from '@tiptap/extension-character-count'
import Document from '@tiptap/extension-document'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import Image from '@tiptap/extension-image'
import { ToggleGroup, ToggleGroupItem } from '../ui/toggle-group'
import { Bold as boldIcon, Italic as intalicIcon, Underline as underlineIcon, StrikethroughIcon, Eye, Send, Image as imageIcon, Smile } from 'lucide-vue-next'
import NumberFlow, { NumberFlowGroup } from '@number-flow/vue'
import { ref } from 'vue'
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/Components/ui/popover'
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/Components/ui/dialog'

const limit = 255;

const editor = useEditor({
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

const focusEditor = () => {
    editor.value?.commands.focus();
};

function GetLink() {
    const url = window.prompt('URL')

    if (url) {
        editor.value?.chain().focus().setImage({ src: url }).run()
    }
}

function addImage(url: string) {
    if (url) {
        editor.value?.chain().focus().setImage({ src: url }).run()
    }
}

const setSpoiler = () => editor.value?.chain().focus().toggleSpoiler().run()

const emit = defineEmits(['send'])

function send() {
    emit('send', JSON.stringify(editor.value?.getJSON()))
}

const active = ref(false)
</script>

<template>
    <div id="emoji"></div>
    <div class="text-white rounded-lg dark border border-input">
        <div class="mb-4 p-2 flex gap-4 border-b border-input">
            <ToggleGroup type="multiple">
                <ToggleGroupItem variant="outline" @click="editor?.chain().focus().toggleBold().run()" value="bold"
                    aria-label="Toggle bold">
                    <boldIcon class="h-4 w-4" />
                </ToggleGroupItem>
                <ToggleGroupItem variant="outline" @click="editor?.chain().focus().toggleItalic().run()" value="italic"
                    aria-label="Toggle italic">
                    <intalicIcon class="h-4 w-4" />
                </ToggleGroupItem>
                <ToggleGroupItem variant="outline" @click="editor?.chain().focus().toggleUnderline().run()"
                    value="underline" aria-label="Toggle underline">
                    <underlineIcon class="h-4 w-4" />
                </ToggleGroupItem>
                <ToggleGroupItem variant="outline" @click="editor?.chain().focus().toggleStrike().run()" value="strike"
                    aria-label="Toggle strike">
                    <StrikethroughIcon class="h-4 w-4" />
                </ToggleGroupItem>
                <Button @click="setSpoiler" variant="outline" size="icon">
                    <Eye class="h-4 w-4" />
                </Button>
                <Button @click="GetLink" variant="outline" size="icon">
                    <imageIcon class="h-4 w-4" />
                </Button>
            </ToggleGroup>
        </div>

        <div @click="focusEditor" class="text-white p-4 min-h-24 border-b border-input w-full">
            <editor-content class="h-full w-full" :editor="editor" />
        </div>

        <div class="p-2 flex justify-between items-center">
            <NumberFlowGroup>
                <div style="--number-flow-char-height: 0.85em"
                    :class="[{ 'text-white': true, '!text-red-600': editor?.storage.characterCount.characters() === limit }, 'flex items-center gap-1']">
                    <NumberFlow :value="editor?.storage.characterCount.characters()" /> / {{ limit }}
                </div>
            </NumberFlowGroup>
            <Button @click="send" variant="secondary" size="sm">
                <Send class="h-4 w-4" /> Enviar
            </Button>
        </div>
    </div>
</template>