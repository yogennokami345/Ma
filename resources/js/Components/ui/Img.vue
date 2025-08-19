<script setup lang="ts">
import { computed } from 'vue';
// @ts-ignore
import VLazyImage from 'v-lazy-image';

interface Props {
    cdnUrl: string;
    src: string;
    alt?: string;
    quality?: number;
    class?: string;
    style?: object;
}

const props = defineProps<Props>();

const computedSrc = computed(() => {
  if (!props.src) return '';
  //@ts-ignore
//   const baseUrl = import.meta.env.VITE_CDN_URL || '';
  const cleanSrc = props.src.startsWith('/') ? props.src.slice(1) : props.src;
  const qualityParam = props.quality !== undefined ? `&q=${props.quality}` : '';
  return `${props.cdnUrl}/${cleanSrc}`;
});

const handleError = () => {
  console.error('Falha ao carregar a imagem:', computedSrc.value);
};
</script>

<template>
    <!-- @vue-ignore -->
  <VLazyImage
    :src="computedSrc"
    :alt="alt"
    :class="class"
    :style="style"
    @error="handleError"
  />
</template>
