<script setup lang="ts">
import Hero from '@/Components/ui/hero/Hero.vue';
import ComicSlider from '@/Components/ComicSlider.vue';
import { Icon } from '@iconify/vue';
import ImageCover from '@/Components/ui/image-cover/ImageCover.vue';
import Img from '@/Components/ui/Img.vue';
import dayjs from 'dayjs';
import 'dayjs/locale/pt-br';
import relativeTime from 'dayjs/plugin/relativeTime';
// @ts-ignore
import NewChaptersSection from '@/Components/NewChaptersSection.vue';
import { Head, Link } from '@inertiajs/vue3';
import PerGenresSlider from '@/Components/PerGenresSlider.vue';
import { computed } from "vue";
import { useColorMode } from "@vueuse/core";
import CardSpotlight from '@/Components/ui/card-spotlight/CardSpotlight.vue';
import route from 'ziggy-js';
import isNullUser from '@/Utils/nullUser';
import CardAd from '@/Components/CardAd/index.vue';
// @ts-ignore
import Ads from '@/Components/Ads.vue';
// import { CardSpotlight } from '@/Components/ui/card-spotlight';

interface ComicAndChapter {
  comic: Comic;
  chapters: Chapter[];
}

interface GenreWithComic extends Genre{
  comic: Comic[]
}

interface Props {
    auth: User;
    settings: Settings;
    hero: Hero[];
    new_releases: Comic[];
    new_chapters: ComicAndChapter[];
    per_genres: GenreWithComic[];
}

defineProps<Props>()

dayjs.locale('en');
dayjs.extend(relativeTime);

const isDark = computed(() => useColorMode().value == "dark");
</script>

<template>
  <Head :title="`${$page.component} | ${settings.name}`"/>
  <div class="flex flex-col gap-10">
    <Ads v-if="settings.ads.home_top" id="ads" :html="settings.ads.home_top" />
    <section>
      <Hero :cdn-url="settings.url_cdn" :hero="hero" />
    </section>

    <section v-if="settings.vip_banner">
      <Link :href="isNullUser(auth) ? route('login') : route('shop')" class="flex h-auto min-h-[100px] lg:min-h-[250px] w-full flex-col gap-4 lg:h-[250px] lg:flex-row">
        <!-- @vue-ignore -->
        <CardSpotlight
          class="cursor-pointer flex-col justify-center px-4 py-6 shadow-2xl md:p-4 lg:whitespace-nowrap"
          :gradient-color="isDark ? '#363636' : '#C9C9C9'"
        >
          <div class="flex flex-1 flex-col gap-3 md:gap-4">
            <h2 class="bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-2xl font-bold tracking-tighter text-transparent dark:from-blue-400 dark:via-purple-700  sm:text-3xl lg:text-4xl">
              Compre seu VIP
            </h2>

            <p class="max-w-md text-xs text-gray-600 dark:text-gray-300 sm:text-sm md:text-base">
              Sua assinatura VIP ajuda diretamente a manter o projeto funcionando,
              garantindo melhorias constantes e novas funcionalidades.
              <span class="mt-1 block text-sm font-medium text-foreground sm:mt-2 sm:text-base">
                Faça parte dessa evolução! 🚀
              </span>
            </p>
          </div>
        </CardSpotlight>
      </Link>
    </section>

    <section>
        <Ads v-if="settings.ads.home_recent" id="ads" :html="settings.ads.home_recent" />
        <ComicSlider title="New Releases" :items="new_releases" :cdnUrl="settings.url_cdn" />
    </section>
    <section>
        <Ads v-if="settings.ads.home_new_chapters" id="ads" :html="settings.ads.home_new_chapters" />
        <NewChaptersSection :items="new_chapters" :cdnUrl="settings.url_cdn" />
    </section>
    <section>
        <Ads v-if="settings.ads.home_per_genre" id="ads" :html="settings.ads.home_per_genre" />
        <PerGenresSlider title="Per Genre" :cdnUrl="settings.url_cdn"/>
    </section>
  </div>
  <CardAd  v-if="settings.ads.home_card">
    <Ads id="ads" :html="settings.ads.home_card" />
  </CardAd>
  <Ads v-if="settings.ads.home_bottom" id="ads" :html="settings.ads.home_bottom" />
</template>

<style scoped>

</style>
