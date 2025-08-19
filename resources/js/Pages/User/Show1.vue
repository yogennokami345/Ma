<script setup lang="ts">
import AvatarButton from "@/Components/ui/avatarButton/AvatarButton.vue";
import ImageCover from "@/Components/ui/image-cover/ImageCover.vue";
import Img from "@/Components/ui/Img.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import { Icon } from "@iconify/vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed, reactive, ref } from "vue";
import route from "ziggy-js";
import { router } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
// @ts-ignore
import NumberFlow, { NumberFlowGroup } from '@number-flow/vue'
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import { User } from "lucide-vue-next";
import EmptyState from "@/Components/ui/EmptyState.vue";
import { GiftCard } from "@/Interfaces/GiftCard";
import SettingsUserButton from "@/Components/SettingsUserButton.vue";

interface UserStats {
  followers: number;
  following: number;
}

interface Props {
    settings: Settings;
    user: User;
    comic: Comic[];
    stats: UserStats;
    isFollowing: boolean;
    giftCards: GiftCard[];
    orders: Order[];
    isSelf: boolean;
}

const props = defineProps<Props>();
const user = props.user;
const isLoading = ref(false);
const following = ref(props.isFollowing);
const followersCount = ref(props.stats.followers);

const initials = computed<string>(() => {
    if (!user || !user.name) return "??";
    return user.name
        .split(" ")
        .map((part: string) => part.charAt(0))
        .join("")
        .toUpperCase();
});

defineOptions({ layout: ProfileLayout });

const toggleFollow = () => {
    isLoading.value = true;

    router.post(route('user.follow', { user: user.user_path }), {}, {
        preserveScroll: true,
        onSuccess: (response) => {
            following.value = !following.value;
            toast.success(
                following.value
                    ? `<span class="font-bold">✨ Você começou a seguir <span class="text-blue-400">${user.name}</span>!</span>`
                    : `<span class="font-bold">👋 Você deixou de seguir <span class="text-gray-400">${user.name}</span></span>`,
                {
                    theme: 'dark',
                    dangerouslyHTMLString: true,
                    position: 'bottom-right',
                    autoClose: 3000,
                    icon: following.value ? '🤝' : '✓'
                }
            )
            if (following.value) {
                followersCount.value++;
            } else {
                followersCount.value--;
            }

            isLoading.value = false;
        },
        onError: (err) => {
            toast.error(err.error, { theme: 'dark', dangerouslyHTMLString: true })
            isLoading.value = false;
        }
    });
};


</script>

<template>
    <Head :title="`${user.name} | ${settings.name}`"/>
    <section class="relative pt-40 pb-24">
        <Img :src="user.banner ? `/storage/${user.banner}` : '/images/banner_default.webp'" :alt="user.name"
            class="w-full absolute top-0 left-0 z-0 h-80 lg:min-h-64 object-cover hautilizada shadow-[rgba(0,_0,_0,_0.25)_0px_-25px_50px_-12px] aspect-[5/4] lg:aspect-[1/0.2]" />
            <div class="bg-background w-full h-20 z-10 absolute top-64 left-0 rounded-t-3xl block lg:hidden"/>
        <div class="w-full max-w-screen-2xl container px-6 md:px-8 z-20 mt-12 lg:mt-36">
            <div class="flex items-center justify-center sm:justify-start relative z-10 mb-5">
            <Avatar size="xxl" class="border-4 border-solid border-background">
                <template v-if="user.cover">
                <AvatarImage :src="`/storage/${user.cover}`" :alt="user.name"/>
                </template>
                <AvatarFallback class="z-0">{{ initials }}</AvatarFallback>
            </Avatar>
            </div>
            <div class="flex items-center justify-center flex-col sm:flex-row max-sm:gap-5 sm:justify-between mb-5">
            <div class="block">
                <h3 class="font-manrope font-bold text-4xl text-white mb-1 max-sm:text-center">{{ user.name }}</h3>
                <div class="font-normal text-base leading-7 text-white/55  max-sm:text-center">
                <span class="font-['inter'] font-bold text-lg">Bio</span>
                <p v-if="user.description" class="font-['Source_Serif_Pro'] font-normal prose w-full lg:w-[27rem]" v-html="user.description"/>
                <p v-else class="font-['Source_Serif_Pro'] font-normal prose">
                    Sem Bio no momento mas... No que você está pensando agora?
                </p>
                </div>
            </div>
            <div class="w-full lg:w-1/5">
                <SettingsUserButton v-if="isSelf" :order="orders" :gift="giftCards"/>
                <vs-button v-else @click="toggleFollow" :loading="isLoading" :disabled="isLoading" shape="circle" size="xl" type="gradient" class="w-full font-['inter'] font-bold text-2xl">
                    {{following ? 'Deixar de Seguir' : 'Seguir'}}
                    <!-- Seguir -->
                    <template #animate>
                        <Icon icon="iconoir:send-diagonal-solid" width="28" height="28" />
                    </template>
                </vs-button>
            </div>
            </div>
            <div class="flex max-sm:flex-wrap max-sm:justify-center items-center gap-4">
            <div class="flex items-center gap-2 opacity-75 text-base">
                <NumberFlowGroup>
                <div style="--number-flow-char-height: 0.85em" class="flex items-center gap-2 font-semibold">
                    <NumberFlow :value="followersCount"/>
                    <p class="font-['Ubuntu'] font-normal hover:underline cursor-pointer">Seguidores</p>
                </div>
                </NumberFlowGroup>
            </div>
            <div class="flex items-center gap-2 opacity-75 text-base">
                <NumberFlowGroup>
                <div style="--number-flow-char-height: 0.85em" class="flex items-center gap-2 font-semibold">
                    <NumberFlow :value="stats.following"/>
                    <p class="font-['Ubuntu'] font-normal hover:underline cursor-pointer">Seguindo</p>
                </div>
                </NumberFlowGroup>
            </div>
            </div>
            <div class="mt-10 flex flex-col gap-4">
            <h1 class="font-['Ubuntu'] font-bold flex items-center gap-2">
                <Icon icon="hugeicons:library" width="20" height="20" />
                Biblioteca
            </h1>
            <ul class="grid grid-cols-2 lg:grid-cols-7 gap-4" v-if="comic && comic.length > 0">
                <li v-for="(item, index) in comic">
                <Link :href="route('comic', {slug: item.slug})">
                    <div class="w-28 lg:w-44 space-y-2">
                    <ImageCover :cover="item.cover" :title="item.title" />
                    <p class="truncate">
                        {{item.title}}
                    </p>
                    </div>
                </Link>
                </li>
            </ul>
            <div v-else class="flex justify-center items-center w-full">
                <EmptyState mood="surprised">
                    Nenhum Item Salvo!
                </EmptyState>
            </div>
            </div>
        </div>
    </section>
</template>

<style scoped></style>
