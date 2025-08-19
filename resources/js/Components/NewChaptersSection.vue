<script setup lang="ts">
import { Icon } from '@iconify/vue';
import ImageCover from '@/Components/ui/image-cover/ImageCover.vue';
import dayjs from 'dayjs';
import { Link } from '@inertiajs/vue3';
import route from 'ziggy-js';
import { ref, computed } from 'vue';
import { useChapterStore } from '@/Stores/chapterStore';
import { Lock } from 'lucide-vue-next';

interface ComicAndChapter {
  comic: Comic;
  chapters: Chapter[];
}

interface Props {
    cdnUrl: string;
    items: ComicAndChapter[];
}

const props = defineProps<Props>();
const displayLimit = ref(24);
const visibleItems = computed(() => props.items.slice(0, displayLimit.value));
const hasMoreItems = computed(() => props.items.length > displayLimit.value);

const loadMore = () => {
  displayLimit.value = props.items.length;
};

const chapterStore = useChapterStore();
</script>

<template>
  <section class="space-y-5">
    <h3 class="text-lg font-semibold flex items-center gap-2">
      <Icon icon="mdi:books" width="20" height="20" />
      Novos Capítulos
    </h3>
    <ul class="grid 2xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-2 grid-cols-1 gap-4">
      <li v-for="(item, index) in visibleItems" :key="index" class="group latest-poster bg-white/10 hover:bg-white/20 rounded-lg transition-all border border-white/5 flex aspect-video overflow-hidden relative">
        <Link :href="route('comic', {slug: item.comic.slug})" class="flex flex-col justify-between rounded-l-lg transition-all aspect-[0.75/1] h-full bg-white/10 bg-no-repeat bg-cover bg-center relative2">
          <ImageCover :cdn-url="cdnUrl" :cover="item.comic.cover" :title="item.comic.title" class=""/>
        </Link>
        <div class="flex flex-col justify-between sm:py-2.5 sm:px-3.5 py-[2vw] px-[2.5vw] w-full">
          <Link :href="route('comic', {slug: item.comic.slug})" >
            <h4 class="h-fit text-[15px] line-clamp-2 leading-[16.5px] break-words">
              {{ item.comic.title }}
            </h4>
          </Link>
          <div class="grid text-xs divide-y divide-white/20 divide-dashed -mb-2">
            <Link :href="route('chapter', {id: chapter.chapter_path}) " v-for="(chapter, index) in item.chapters" :key="index" class="grid leading-none gap-1 py-2 hover:-mx-2 hover:px-2 hover:bg-white/10 hover:rounded-lg transition-all">
              <!-- @vue-ignore -->
              <div class="flex gap-2.5 justify-start items-center" @click="chapterStore.setLastReadChapter({...chapter, comic_id: item.comic.id.toString()})">
                <div class="aspect-[2/1.5] w-[60px] rounded-lg bg-center bg-cover bg-white/20" :style="`background-image:url(${cdnUrl}/${chapter.chapter_cover ?? item.comic.cover})`"/>
                <div class="grid gap-0.5">
                  <h5 class="flex justify-start h-4 items-center gap-1.5 w-full overflow-hidden">
                      Capítulo {{ chapter.chapter_number }} <span v-if="chapter.chapter_title"> - {{ chapter.chapter_title }}</span>
                      <Lock v-if="chapter.locked" class="w-3 text-red-500"/>
                  </h5>
                  <p class="flex justify-start items-center gap-1 w-full">
                    {{ dayjs(chapter.locked ? chapter.locked : chapter.created_at).fromNow() }}
                  </p>
                </div>
              </div>
            </Link>
          </div>
        </div>
      </li>
    </ul>

    <div v-if="hasMoreItems" class="flex justify-center">
      <button
        @click="loadMore"
        class="px-6 py-2 bg-white/10 hover:bg-white/20 text-sm font-medium rounded-lg transition-all border w-full border-white/5"
      >
        Ver mais
      </button>
    </div>
  </section>
</template>
