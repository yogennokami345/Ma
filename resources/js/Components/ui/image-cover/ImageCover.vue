<script setup lang="ts">
import Img from '../Img.vue';
defineProps<{
    cdnUrl: string;
    cover: string;
    title: string;
}>();
</script>

<template>
    <div class="w-full h-full">
        <Img :src="cover" :cdn-url="cdnUrl" class="w-full aspect-[2/3] object-cover rounded-md" :alt="title" />
    </div>
</template>
