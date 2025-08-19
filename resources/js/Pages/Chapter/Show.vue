<script setup lang="ts">
import Img from '@/Components/ui/Img.vue';
// @ts-ignore
import ReaderLayout from '@/Layouts/ReaderLayout.vue';
// @ts-ignore
import { useReaderSettings } from '@/Stores/useReaderSettings';
import { Head, Link, router } from '@inertiajs/vue3';
import route from 'ziggy-js';
import isNullUser from '@/Utils/nullUser';
// @ts-ignore
import Ads from '@/Components/Ads.vue';

interface ChapterWithComic extends Chapter {
    comic: Comic;
}

interface Props {
    auth: User;
    chapter: {chapter: ChapterWithComic};
    comments: any;
    settings: Settings;
}
// @ts-ignore
const formatReleaseDate = (date: any) => {
    if (!date) return 'Data não definida';

    const releaseDate = new Date(date);
    const now = new Date();
    const diffTime = releaseDate.getTime() - now.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays <= 0) {
        return 'Disponível agora';
    } else if (diffDays === 1) {
        return 'Amanhã';
    } else if (diffDays <= 7) {
        return `Em ${diffDays} dias`;
    } else {
        return releaseDate.toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
};

defineProps<Props>();
defineOptions({ layout: ReaderLayout })
const readerSettings = useReaderSettings();
</script>

<template>
    <Ads v-if="settings.ads.reader_top" id="ads" :html="settings.ads.reader_top" />
    <div v-if="chapter.chapter.pages.length === 0 && chapter.chapter.locked" class="flex justify-center h-screen flex-1 items-center p-4">
        <div class="max-w-md w-full bg-zinc-900 rounded-2xl shadow-xl border border-zinc-800 p-8 text-center">

            <div class="mx-auto w-16 h-16 bg-red-600/20 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-zinc-100 mb-3">
                Capítulo Exclusivo VIP
            </h1>

            <p class="text-zinc-400 mb-6 leading-relaxed">
                Este capítulo está disponível apenas para membros VIP. Torne-se VIP para ter acesso antecipado a todos os capítulos!
            </p>

            <div class="bg-zinc-800 rounded-lg p-4 mb-6 border border-zinc-700">
                <p class="text-sm text-zinc-400 mb-1">Liberação para todos em:</p>
                <p class="text-lg font-semibold text-zinc-200">
                    {{ formatReleaseDate(chapter.chapter.locked) }}
                </p>
            </div>
            <Link  :href="isNullUser(auth) ? route('login') : route('shop')" class="w-full">
                <button class="cursor-pointer w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl mb-4">
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ isNullUser(auth) ? 'Faça Login' : 'Tornar-se VIP' }}
                    </span>
                </button>
            </Link>
            <Link :href="route('comic', {slug: chapter.chapter.comic.slug})" class="w-full">
                <button class="w-full text-zinc-400 hover:text-zinc-200 font-medium py-2 px-4 rounded-lg hover:bg-zinc-800 transition-colors duration-200">
                    Voltar aos capítulos
                </button>
            </Link>
        </div>
    </div>

    <ul v-else class="flex flex-col justify-center items-center">
        <li v-for="(item, index) in chapter.chapter.pages" :key="index">
            <Img
                :cdn-url="settings.url_cdn"
                :src="`${item.page_path}`"
                loading="lazy"
                :quality="100"
                :alt="`Pagina ${index}`"
                class="w-screen lg:w-auto"
                :style="{
                    filter: `
                        brightness(${readerSettings.brightness[0]}%)
                        contrast(${readerSettings.contrast[0]}%)
                        saturate(${readerSettings.saturation[0]}%)
                        sepia(${readerSettings.sepia[0]}%)
                        hue-rotate(${readerSettings.hueRotate[0]}deg)
                        grayscale(${readerSettings.greyscale ? '100%' : '0%'})
                        invert(${readerSettings.negative ? '100%' : '0%'})
                    `}"
            />
        </li>
    </ul>

    <Ads v-if="settings.ads.reader_bottom" id="ads" :html="settings.ads.reader_bottom" />
</template>

<style scoped>

</style>
