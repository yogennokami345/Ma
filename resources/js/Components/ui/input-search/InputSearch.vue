<script setup lang="ts">
import { ref, watch, watchEffect } from 'vue';
import axios from 'axios';
import route from 'ziggy-js';
import Img from '../Img.vue';
import ImageCover from '../image-cover/ImageCover.vue';
import { Icon } from '@iconify/vue';
import debounceEvent from '@/Utils/debounceEvent';;
import AvatarButton from '../avatarButton/AvatarButton.vue';
import getStatusColor from '@/Utils/getStatusColor';
import { useMagicKeys } from '@vueuse/core'
import { Link } from '@inertiajs/vue3';

interface Props {
    cdnUrl: string;
}

interface ComicsWithCount extends Comic {
  chapters_count: number;
}

interface SearchResult {
  comics: PaginatedResponse<ComicsWithCount>;
  users: PaginatedResponse<User>;
}
const active = ref(false);
const search = ref('');
const showResults = ref<SearchResult|null>(null);
const { Ctrl_k } = useMagicKeys()

const props = defineProps<Props>();

watch(Ctrl_k, (v) => {
  if (v)
    active.value = !active.value
})


watch(() => search.value, (value) => {
  debounceEvent(() => fetchComics(), 500);
})

const fetchComics = async () => {
  const response = await axios.get(route('search'), {
    params: {
      q: search.value
    }
  });
  showResults.value = response.data;
}
fetchComics();
</script>

<template>
    <button class="rounded-full border border-border py-2 px-3 bg-input/30 hover:bg-input/75 transition-colors flex items-center gap-6" @click="active = !active">
      <Icon icon="material-symbols:search-rounded" width="24" height="24" class="opacity-55"/>
      <kbd class="inline-flex items-center pointer-events-none h-5 select-none gap-1 rounded border border-border bg-muted font-sans font-medium min-h-4 text-[10px] h-4 px-1 pointer-events-none hidden h-5 select-none items-center gap-1 rounded border bg-muted px-1.5 font-mono text-[10px] font-medium opacity-100 sm:flex">
        ⌘ K
      </kbd>
    </button>
    <vs-dialog v-model="active" overlay-blur lock-scroll width="7000px" scroll>
      <template #header>
        <h4 class="not-margin text-lg font-bold">Pesquisar</h4>
      </template>
      <div class="flex flex-col gap-2">
        <div class="con-form">
          <vs-input v-model="search" placeholder="Pesquisar..." block>
            <template #icon>
              <Icon icon="material-symbols:search-rounded" width="24" height="24" />
            </template>
          </vs-input>
        </div>

        <ul class="flex flex-col gap-4">
          <h1 class="font-bold">
            Comics
          </h1>
          <li v-if="showResults && showResults.comics && showResults.comics.data.length" v-for="(item, index) in showResults.comics.data" :key="index">
            <Link :href="route('comic', {slug: item.slug})" class="flex items-start gap-4 w-full transition-colors hover:bg-[#292c30] py-2 px-2 rounded-md">
              <div class="w-16">
                <ImageCover :cdn-url="cdnUrl" :cover="item.cover" :title="item.title" />
              </div>
              <div class="flex flex-col items-start justify-start gap-2">
                <h1 class="text-lg font-semibold">
                  {{ item.title }}
                </h1>
                <h2 class="flex items-center">
                  <Icon icon="material-symbols:book-rounded" width="20" height="20" />
                  {{ item.chapters_count }}
                </h2>
                <h3 :class="[getStatusColor(item.statuses[0].name), 'py-0.5 px-2 rounded-full text-xs']">
                  {{ item.statuses[0].name }}
                </h3>
              </div>
            </Link>
          </li>
          <h1 v-else class="opacity-55 flex justify-center items-center w-full">
            Nada Por Aqui :/
          </h1>
          <h1 class="font-bold">
            Usuários
          </h1>
          <li v-if="showResults && showResults.comics && showResults.users.data.length" v-for="(item, index) in showResults.users.data" :key="index">
            <Link :href="route('user', {id: item.user_path})">
              <div class="flex items-center gap-2 w-full transition-colors hover:bg-[#292c30] py-2 px-2 rounded-md">
                <AvatarButton :auth="item" :trigger="true"/>
                <h1>
                  {{ item.name }}
                </h1>
              </div>
            </Link>
          </li>
          <h1 v-else class="opacity-55 flex justify-center items-center w-full">
            Nada Por Aqui :/
          </h1>
        </ul>
      </div>

      <template #footer>

      </template>
    </vs-dialog>
</template>

