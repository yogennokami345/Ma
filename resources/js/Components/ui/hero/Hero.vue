<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import Img from '../Img.vue';

interface Props{
    cdnUrl: string;
    hero: Hero[];
}
defineProps<Props>()
</script>

<template>
  <div>
    <swiper class="mySwiper" style="border-radius: var(--radius);" :spaceBetween="20" :autoplay="{delay: 4000, disableOnInteraction: true}" :loop="true">
        <swiper-slide v-for="(items, index) in hero" :key="index">
          <Img :cdn-url="cdnUrl" :src="items.hero_path" alt="Hero" class="aspect-[1/0.5] lg:aspect-[6/2] w-full rounded-lg" v-if="!items.hero_link"/>
          <a :href="items.hero_link" v-else>
            <Img :cdn-url="cdnUrl" :src="items.hero_path" alt="Hero" class="aspect-[1/0.5] lg:aspect-[6/2] w-full rounded-lg"/>
          </a>
        </swiper-slide>
    </swiper>
  </div>
</template>

<style scoped>

</style>
