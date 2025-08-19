<script setup lang="ts">
import ImageCover from '@/Components/ui/image-cover/ImageCover.vue';
import { Icon } from '@iconify/vue';
import dayjs from 'dayjs';
import 'dayjs/locale/pt-br';
import relativeTime from 'dayjs/plugin/relativeTime';
import { computed, ref } from 'vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify'
import route from 'ziggy-js';
import { useChapterStore } from '@/Stores/chapterStore';
import { Lock } from 'lucide-vue-next';
import isNullUser from '@/Utils/nullUser';
// @ts-ignore
import Ads from '@/Components/Ads.vue';

dayjs.locale('pt-br');
dayjs.extend(relativeTime);

interface Props {
    settings: Settings;
    auth: User;
    comic_infos: Comic;
    inLibrary: boolean;
    likeComic: boolean;
    countLikes: number;
}

const props = defineProps<Props>();

const search = ref('');

const filtered_chapters = computed(() => {
    return props.comic_infos.chapters.filter((item) => item.chapter_number.toString().includes(search.value));
});

const chapters = ref(props.comic_infos.chapters);
const getLibrary = ref<boolean>(props.inLibrary);
const form = useForm({});

const addToLibrary = () => {
    form.post(route('addToLibrary', {comic: props.comic_infos.id}), {
        preserveScroll: true,
        onSuccess: (response) => {
            getLibrary.value = response.props.inLibrary as boolean;

            toast.success(getLibrary.value ? 'Adicionado com Sucesso' : 'Removido com Sucesso',
                { theme: 'dark', dangerouslyHTMLString: true })
        },
        onError: (errors) => {
            toast.error(`Erro ao adicionar mangá: ${(errors.message || 'Tente novamente.')}`,
                { theme: 'dark', dangerouslyHTMLString: true })
        }
    });
};

const likeForm = useForm({})
const addLikeInComic = () => {
    likeForm.post(route('likecomic', {slug: props.comic_infos.slug}), {
        preserveScroll: true,
        onSuccess: (response) => {
            const activeLike = response.props.likeComic as boolean

            toast.success(activeLike ? 'Adicionado com Sucesso' : 'Removido com Sucesso',
                { theme: 'dark', dangerouslyHTMLString: true })
        },
        onError: (errors) => {
            toast.error(`Erro ao Curtir Comic: ${(errors.message || 'Tente novamente.')}`,
                { theme: 'dark', dangerouslyHTMLString: true })
        }
    })
}

const chapterStore = useChapterStore();
</script>

<template>
    <Head :title="`${comic_infos.title} | ${settings.name}`"/>
    <Ads v-if="settings.ads.comic_top" id="ads" :html="settings.ads.comic_top" />
    <div class="grid sm:gap-10 gap-[4vw] w-full">
        <!-- <div class="layer_main bg-cover bg-center" :style="`background-image:url(/storage/${comic_infos.banner ?? comic_infos.cover})`"/> -->
        <!-- <div class="layer_main bg-cover bg-center max-h-[50%]" :style="`background-image:url(https://wsrv.nl/?url=cdn.meowing.org/uploads/QorpyuLz0Lg)`"/> -->
        <div class="flex xl:flex-row flex-col  w-full sm:gap-10 gap-[4vw] relative">
            <div class="flex flex-col justify-center items-center gap-4 h-fit">
                <div class="rounded-lg md:w-[24rem] w-72 bg-[image:--photoURL] aspect-[0.75/1] bg-cover bg-center bg-white/10"
                    :style="`--photoURL:url(${settings.url_cdn}/${comic_infos.cover})`" />
                <div class="w-full flex lg:gap-1 items-center">
                    <vs-button :disabled="isNullUser(auth)" color="danger" shape="circle" type="gradient" animation-type="vertical" block @click="addToLibrary">
                        <h1 class="font-bold text-xl">
                            {{getLibrary ? 'Remover a Biblioteca' : 'Adicionar na Biblioteca'}}
                        </h1>
                        <template #animate>
                            <Icon icon="solar:heart-bold" width="30" height="30" />
                        </template>
                    </vs-button>

                    <vs-button :disabled="isNullUser(auth)" shape="circle" type="flat" icon :active="likeComic" @click="addLikeInComic">
                        <div class="px-1.5">
                            <Icon icon="bxs:like" width="35" height="35" />
                        </div>
                    </vs-button>
                </div>
            </div>
            <div class="grid sm:gap-4 gap-[4vw] w-full h-fit">
                <div class="flex md:flex-row flex-col justify-start items-center gap-6">
                    <div class="grid gap-4">
                        <h1 class="sm:text-4xl text-xl text-[length:10vw] font-black">
                            {{ comic_infos.title }}
                        </h1>
                        <ul class="flex flex-wrap gap-3 justify-start items-start">
                            <li v-for="(item, index) in comic_infos.genres"
                                class="leading-none h-8 px-3 flex justify-center items-center sm:gap-1.5 gap-[1.5vw] bg-white/10 hover:bg-white/20 transition-all cursor-pointer rounded-lg">
                                <p>{{ item.name }}</p>
                            </li>
                        </ul>
                        <div class="grid gap-2 h-fit">
                            <div class="font-medium">
                                Nomes Alternativos
                            </div>
                            <div class="grid gap-2 h-fit">
                                <div class="grid flex-wrap gap-2 w-full mb-2 opacity-80">
                                    <span class="text-md leading-none">
                                        {{ comic_infos.alternative_name ?? 'Sem Titulos alternativos' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="w-full flex gap-3 flex-wrap">
                            <div class="grid gap-2 h-fit">
                                <div class="font-medium flex gap-1 justify-start items-center">
                                    <Icon icon="iconamoon:like" width="24" height="24" />
                                    <span>Likes</span>
                                </div>
                                <div class="min-h-8 py-1 px-3 flex justify-center items-center bg-white/10 hover:bg-white/20 transition-all rounded-lg w-fit">
                                   {{ countLikes ?? 0 }} Curtidas
                                </div>
                            </div>
                            <div class="grid gap-2 h-fit">
                                <div class="font-medium flex gap-1 justify-start items-center">
                                    <Icon icon="fluent:status-16-filled" width="24" height="24" />
                                    <span>Status</span>
                                </div>
                                <div class="min-h-8 py-1 px-3 flex justify-center items-center bg-white/10 hover:bg-white/20 transition-all rounded-lg w-fit">
                                   {{ comic_infos.statuses[0].name }}
                                </div>
                            </div>
                            <div class="grid gap-2 h-fit">
                                <div class="font-medium flex gap-1 justify-start items-center">
                                    <Icon icon="ic:baseline-watch-later" width="24" height="24" />
                                    <span>Atualizado</span>
                                </div>
                                <div class="min-h-8 py-1 px-3 flex justify-center items-center bg-white/10 hover:bg-white/20 transition-all rounded-lg w-fit">
                                    {{ comic_infos.chapters.length > 0
                                        ? dayjs([...comic_infos.chapters].sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime())[0].created_at).fromNow()
                                        : dayjs(comic_infos.updated_at).fromNow()
                                    }}
                                </div>
                            </div>
                            <div class="grid gap-2 h-fit">
                                <div class="font-medium flex gap-1 justify-start items-center">
                                    <Icon icon="tabler:clock-plus" width="24" height="24" />
                                    <span>Publicado</span>
                                </div>
                                <div class="min-h-8 py-1 px-3 flex justify-center items-center bg-white/10 hover:bg-white/20 transition-all rounded-lg w-fit">
                                    {{ dayjs(comic_infos.created_at).fromNow() }}
                                </div>
                            </div>
                        </div>
                        <div class=" bg-white/10 p-4 rounded-2xl grid gap-2">
                            <h1 class="text-lg font-bold">
                                Sinopse
                            </h1>
                            <p class="white-space: pre-wrap prose" v-html="comic_infos.description"/>
                        </div>
                    </div>
                </div>
                <div class="lg:flex gap-4 lg:justify-start lg:items-start" v-if="comic_infos.chapters.length > 0">
                    <div class="w-full grid gap-4">
                        <div class="flex gap-2">
                            <vs-input v-model="search" placeholder="Pesquisar capítulo" color="primary" class=" outline-none" block>
                                <template #icon>
                                    <Icon icon="tabler:search" width="24" height="24" />
                                </template>
                            </vs-input>
                            <div>
                                <vs-button icon class="" size="xl" shape="circle" @click="chapters = chapters.reverse()">
                                    <Icon icon="garden:arrow-reverse-stroke-12" width="21" height="21" />
                                </vs-button>
                            </div>
                        </div>
                        <div class="grid gap-4">
                            <div class="grid relative">
                                <div class="grid">
                                    <ul class="grid 2xl:grid-cols-3 lg:grid-cols-2 grid-cols-1 sm:gap-4 gap-[3vw]">
                                        <li v-for="(item, index) in filtered_chapters" :key="index" class="">
                                            <!-- @vue-ignore -->
                                            <Link :href="route('chapter', {id: item.chapter_path})"
                                                  @click="chapterStore.setLastReadChapter({...item, comic_id: comic_infos.id.toString()})"
                                                  :class="['group cursor-pointer relative overflow-hidden flex  items-center sm:gap-4 gap-[4vw] hover:bg-white/20 bg-white/10 rounded-2xl sm:p-2 p-[2vw] transition-all', chapterStore.isChapterRead(comic_infos.id, item.chapter_number) ? 'opacity-85' : '']">
                                                <div class="border inline-table border-white/10 bg-white/10 overflow-hidden rounded-xl">
                                                    <div class="aspect-[2/1.5] w-28 bg-white/10 bg-cover bg-center rounded-lg" :style="`background-image:url(${settings.url_cdn}/${item.chapter_cover ?? comic_infos.cover})`" />
                                                </div>
                                                <div class="grid h-fit ">
                                                    <span class="flex  gap-1 justify-start items-center overflow-hidden">
                                                        <Lock v-if="item.locked" class="w-4 text-red-500"/>
                                                        <span class="text-lg truncate">
                                                            Capítulo {{ item.chapter_number }} <span v-if="item.chapter_title"> - {{ item.chapter_title }}</span>
                                                        </span>
                                                        <span v-if="settings.chapter_show_views" class="ml-2 text-xs text-white/50 bg-white/10 px-2 py-0.5 rounded-lg flex items-center gap-1.5">
                                                            <Icon icon="solar:eye-bold" width="16" height="16" />
                                                            {{ item.views }}
                                                        </span>
                                                    </span>
                                                    <div class="flex items-start flex-col justify-start gap-1.5">
                                                        <p class="text-xs text-white/50 w-fit">
                                                            {{ dayjs(item.locked ? item.locked : item.created_at).fromNow() }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <!-- @vue-ignore -->
                                                <div class="absolute right-0 bottom-0 opacity-100"
                                                     v-if="chapterStore.isChapterRead(comic_infos.id, item.chapter_number)"
                                                     @click.stop.prevent="
                                                       chapterStore.removeChapterFromHistory(comic_infos.id, item.chapter_number);
                                                       $event.stopPropagation();
                                                       $event.preventDefault();
                                                     ">
                                                  <div class="bg-blue-700 p-1 rounded-tl-lg cursor-pointer hover:bg-blue-600 transition-colors">
                                                    <Icon icon="fe:eye" width="24" height="24" />
                                                  </div>
                                                </div>
                                            </Link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="flex-1 flex flex-col justify-center text-center">
                    <EmptyState mood="angry">
                        Nenhum capítulo encontrado
                    </EmptyState>
                </div>
            </div>
        </div>
    </div>
    <Ads v-if="settings.ads.comic_bottom" id="ads" :html="settings.ads.comic_bottom" />
</template>

<style scoped>
.layer_main {
    position: absolute;
    top: 0px;
    left: 0px;
    margin-left: -2.5rem;
    width: calc(100% + 2.5rem);
    height: 960px;
}

.layer_2 {
    height: 960px;
    background: rgba(9, 9, 11, 0.6);
}

.layer_1 {
    background: linear-gradient(rgba(0, 0, 0, 0.35) 30%, rgba(0, 0, 0, 0.4) 50%, var(--theme_color) 100%);
    -webkit-backdrop-filter: blur(36px);
    backdrop-filter: blur(36px);
}
</style>
