<script setup lang="ts">
import { NodeViewWrapper, NodeViewContent } from '@tiptap/vue-3';
import { ref, computed } from 'vue';

const isSpoilerVisible = ref(false);

const toggleSpoiler = (event: Event) => {
    event.stopPropagation();
    isSpoilerVisible.value = !isSpoilerVisible.value;
}
</script>

<template>
    <NodeViewWrapper as="span" class="spoiler rounded flex flex-col gap-2">
        <NodeViewContent @click="toggleSpoiler" :class="[isSpoilerVisible
            ? 'text-black dark:!text-white p-px cursor-pointer'
            : 'text-transparent !select-none bg-black dark:!bg-gray-600 cursor-pointer rounded p-px']" />
    </NodeViewWrapper>
</template>

<style scoped>
.spoiler {
    display: inline-flex;
    background-color: rgba(128, 128, 128, 0.2);
}
</style>