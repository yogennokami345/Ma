<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import { Icon } from '@iconify/vue';
import ImageCover from '@/Components/ui/image-cover/ImageCover.vue';
import { Swiper as SwiperClass } from 'swiper/types';
import route from 'ziggy-js';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import getRandomNumber from '@/Utils/getRandomNumber';
import { FreeMode } from 'swiper/modules';

interface GenreWithComic extends Genre {
  comics: Comic[]
}

interface Props {
    cdnUrl: string;
    title: string;
}

defineProps<Props>()

const isBeginning = ref(true)
const isEnd = ref(false)
const slidesPerView = ref(6)
const spaceBetween = ref(30)
const swiperInstance = ref<SwiperClass | null>(null)

const updateSlidesPerView = () => {
  const width = window.innerWidth
  if (width < 640) {
    slidesPerView.value = 3
    spaceBetween.value = 10
  } else if (width < 768) {
    slidesPerView.value = 4
    spaceBetween.value = 15
  } else if (width < 1024) {
    slidesPerView.value = 5
    spaceBetween.value = 20
  } else if (width < 1280) {
    slidesPerView.value = 6
    spaceBetween.value = 25
  } else {
    slidesPerView.value = 7
    spaceBetween.value = 30
  }
}

const slidePrev = () => {
  if (swiperInstance.value) {
    swiperInstance.value.slidePrev()
  }
}

const slideNext = () => {
  if (swiperInstance.value) {
    swiperInstance.value.slideNext()
  }
}

const onSwiperInit = (swiper: SwiperClass) => {
  swiperInstance.value = swiper
  isBeginning.value = swiper.isBeginning
  isEnd.value = swiper.isEnd

  swiper.on('slideChange', () => {
    isBeginning.value = swiper.isBeginning
    isEnd.value = swiper.isEnd
  })
}

onMounted(() => {
  updateSlidesPerView()
  window.addEventListener('resize', updateSlidesPerView)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', updateSlidesPerView)
})

const idComicByComic = ref<GenreWithComic>()
const idGenre = ref(null)
const getGenres = ref<Genre[]>()

const fetchGenres = async () => {
  const response = await axios.get(route('showGenres'));
  getGenres.value = response.data;
  if (!idGenre.value && response.data.length > 0) {
    idGenre.value = response.data[getRandomNumber(0, response.data.length)].id;
  }
}
fetchGenres();

const fetchComics = async () => {
  if (!idGenre.value) return;
  const response = await axios.get(route('showComicByGenres', { id: idGenre.value }));
  idComicByComic.value = response.data;
}

watch(idGenre, (newVal) => {
  if(newVal) {
    fetchComics();
  }
});

const modules = [FreeMode]
</script>

<template>
  <section class="space-y-5">
    <div class="flex items-center justify-between">
        <div class="flex items-center justify-between gap-4">
            <h3 class="text-lg font-semibold flex items-center gap-2">
              <Icon icon="fa-solid:random" width="20" height="20" />
              {{ title }}
            </h3>
            <vs-select v-model="idGenre" filter>
                <vs-option :label="item.name" :value="item.id" v-for="item in getGenres" :key="item.id">
                  {{ item.name }}
                </vs-option>
            </vs-select>
        </div>

      <div class="lg:flex gap-2 hidden">
        <!-- <vs-button icon color="#18181B" type="gradient" @click="slidePrev" :disabled="isBeginning">
          <Icon icon="ph:caret-left-bold" width="20" height="20" />
        </vs-button>
        <vs-button icon color="#18181B" type="gradient" @click="slideNext" :disabled="isEnd">
          <Icon icon="ph:caret-right-bold" width="20" height="20" />
        </vs-button> -->
        <vs-button icon color="#18181B" type="gradient" @click="slidePrev">
          <Icon icon="ph:caret-left-bold" width="20" height="20" />
        </vs-button>
        <vs-button icon color="#18181B" type="gradient" @click="slideNext">
          <Icon icon="ph:caret-right-bold" width="20" height="20" />
        </vs-button>
      </div>
    </div>
    <swiper class="mySwiper" :spaceBetween="spaceBetween" :slidesPerView="slidesPerView" @swiper="onSwiperInit" :loop="true" :freeMode="true">
      <swiper-slide v-for="(item, index) in idComicByComic?.comics" :key="index">
        <Link :href="route('comic', {slug: item.slug})" class="w-28 lg:w-44 space-y-2">
          <ImageCover :cdn-url="cdnUrl" :cover="item.cover" :title="item.title" />
          <h1 class="truncate text-xs lg:text-sm">
            {{ item.title }}
          </h1>
        </Link>
      </swiper-slide>
    </swiper>
  </section>
</template>
