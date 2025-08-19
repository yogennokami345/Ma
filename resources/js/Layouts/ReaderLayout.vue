<script setup lang="ts">
// @ts-ignore
import Progress from '@/Components/ui/progress/Progress.vue';
// @ts-ignore
import ReaderConfigButton from '@/Components/ui/readerConfigButtom/ReaderConfigButton.vue';
// @ts-ignore
import WarningButton from '@/Components/ui/warningButton/WarningButton.vue';
import { Icon } from '@iconify/vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { watch } from 'fs';
import { onMounted, onUnmounted, ref } from 'vue';
import { useReaderSettings } from '@/Stores/useReaderSettings';
import CommentsButton from '@/Components/ui/commentsButton/CommentsButton.vue';
import route from 'ziggy-js';
import isNullUser from '@/Utils/nullUser';
import { useChapterStore } from '@/Stores/chapterStore';

interface ChapterWithComic extends Chapter{
    comic: Comic;
}
interface Props{
    chapter: ChapterWithComic;
    lastChapter?: Chapter | null;
    previousChapter?: Chapter | null;
    nextChapter?: Chapter | null;
}
const props = defineProps<{
    auth: User;
    settings: Settings;
    chapter: Props
    comments: any
}>();

const progress = ref(0);
const lastScrollY = ref(0);
const scrollDirection = ref<'up' | 'down'>('up');

const handleScroll = () => {
    const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
    const scrolled = window.scrollY;
    progress.value = Math.round((scrolled / totalHeight) * 100);

    if (scrolled > lastScrollY.value) {
        scrollDirection.value = 'down';
    } else {
        scrollDirection.value = 'up';
    }
    lastScrollY.value = scrolled;
};

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const readerSettings = useReaderSettings();
const chapterStore = useChapterStore();
</script>

<template>
    <Head :title="`Capítulo ${chapter.chapter.chapter_number} - ${chapter.chapter.comic.title} | ${settings.name}`" />
    <div class="min-h-screen flex flex-col">
        <header
            :class="{
                '-translate-y-full': readerSettings.fixed === false && scrollDirection === 'down',
                'fixed': readerSettings.fixed
            }"
            class="sticky z-40 top-0 bg-background/80 backdrop-blur-lg border-b border-border transition-all duration-500">

            <div class="container max-w-screen-2xl py-2">
                <nav class="flex items-center gap-4 justify-between">
                    <Link :href="route('comic', {slug: chapter.chapter.comic.slug})">
                        <button type="button" class="p-1 rounded-full transition-colors group hover:bg-primary-foreground">
                            <Icon icon="iconamoon:arrow-left-2-duotone" width="35" height="35" class="opacity-50 group-hover:opacity-95 transition-colors" />
                        </button>
                    </Link>
                    <div class="flex flex-col w-1/2 lg:w-full">
                        <h2 class="text-sm opacity-95 font-semibold">
                            Capítulo {{ chapter.chapter.chapter_number }}
                        </h2>
                        <h3 class="text-sm truncate text-muted-foreground italic max-w-28 lg:max-w-[100%]">
                            {{ chapter.chapter.comic.title }}
                        </h3>
                    </div>
                    <div class="flex gap-2 items-center">
                        <!-- <DownloadButtonChapter :chapter="chapter"/> -->
                        <CommentsButton v-if="!isNullUser(auth)" :chapter="chapter.chapter" :comments="comments"/>
                        <WarningButton v-if="settings.warning_channel" :url="settings.warning_channel" />
                    </div>
                </nav>
            </div>
        </header>
        <div class="flex flex-col flex-1">
            <slot />
        </div>
        <div class="justify-end flex container max-w-screen-2xl">
            <button
                @click="scrollToTop"
                :class="{ 'translate-y-[30rem]': scrollDirection === 'down' }"
                class="p-3 rounded-full bg-primary-foreground/65 shadow-[-10px_-10px_30px_4px_rgba(0,0,0,0.1),_10px_10px_30px_4px_rgba(45,78,255,0.15)] backdrop-blur-xl fixed bottom-20 fall-button transition-all duration-700" >
                <Icon icon="iconamoon:arrow-up-2" width="30" height="30" class=""/>
            </button>
        </div>
        <footer
        :class="{
            'translate-y-full': readerSettings.fixed === false && scrollDirection === 'down',
            'fixed': readerSettings.fixed
        }"
        class="sticky z-40 bottom-0 bg-background/80 backdrop-blur-lg border-t border-border transition-all duration-500">
            <div class="container max-w-screen-2xl py-2">
                <nav class="flex items-center gap-4 justify-between">
                    <div class="flex gap-2 items-center">
                        <!-- @vue-ignore -->
                        <a :href="route('chapter', {id: chapter.previousChapter?.chapter_path})" v-if="chapter.previousChapter?.chapter_path" @click="chapterStore.setLastReadChapter({
                          chapter_path: chapter.previousChapter.chapter_path,
                          chapter_number: chapter.previousChapter.chapter_number,
                          comic_id: chapter.chapter.comic.id
                        })">
                            <button class="p-1 hover:bg-primary-foreground rounded-full items-center justify-center transition-colors">
                                <Icon icon="iconamoon:arrow-up-2" width="30" height="30" class="-rotate-90 opacity-90"/>
                            </button>
                        </a>
                        <button v-else>
                            <Icon icon="iconamoon:arrow-up-2" width="30" height="30" class="-rotate-90 cursor-not-allowed opacity-55"/>
                        </button>
                        <h1 class="text-muted-foreground text-sm">
                            {{ chapter.chapter.chapter_number }} / {{ chapter.lastChapter?.chapter_number }}
                        </h1>
                        <!-- @vue-ignore -->
                        <a :href="route('chapter', {id: chapter.nextChapter?.chapter_path})" v-if="chapter.nextChapter?.chapter_path" @click="chapterStore.setLastReadChapter({
                          chapter_path: chapter.nextChapter.chapter_path,
                          chapter_number: chapter.nextChapter.chapter_number,
                          comic_id: chapter.chapter.comic.id
                        })">
                            <button class="p-1 hover:bg-primary-foreground rounded-full items-center justify-center transition-colors">
                                <Icon icon="iconamoon:arrow-up-2" width="30" height="30" class="rotate-90 opacity-90"/>
                            </button>
                        </a>
                        <button v-else>
                            <Icon icon="iconamoon:arrow-up-2" width="30" height="30" class="rotate-90 cursor-not-allowed opacity-55"/>
                        </button>
                    </div>
                    <Progress :model-value="progress" class="lg:w-2/3 w-2/6"/>
                    <div>
                        <ReaderConfigButton />
                    </div>
                </nav>
            </div>
        </footer>
    </div>
</template>

<style scoped>

</style>
