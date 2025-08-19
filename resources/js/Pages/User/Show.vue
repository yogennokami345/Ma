<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import route from 'ziggy-js';
import { Button } from '@/Components/ui/button';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/Components/ui/tabs';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from '@/Components/ui/card';
import { Avatar, AvatarImage, AvatarFallback } from '@/Components/ui/avatar';
import { Skeleton } from '@/Components/ui/skeleton';
import { Badge } from '@/Components/ui/badge';
import NumberFlow, { NumberFlowGroup } from '@number-flow/vue'
import { Progress } from '@/Components/ui/progress';
import { toast } from 'vue3-toastify';
import { GiftCard } from '@/Interfaces/GiftCard';
import SettingsUserButton from '@/Components/SettingsUserButton.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import isNullUser from '@/Utils/nullUser';


export interface UserStats {
  followers: number;
  following: number;
}

interface Props {
    auth: User;
    settings: Settings;
    user: User;
    comic: Comic[];
    stats: UserStats;
    isFollowing: boolean;
    giftCards?: GiftCard[] | null;
    orders: Order[];
    isSelf: boolean;
    loading: boolean;
}


const props = withDefaults(defineProps<Props>(), {
  loading: true
});

const activeTab = ref('library');
const isFollowing = ref(props.isFollowing || false);
const following = ref(props.isFollowing);
const followersCount = ref(props.stats.followers);
const user = props.user;
const isLoading = ref(false);
const followButtonText = computed(() => isFollowing.value ? 'Following' : 'Follow');
const followButtonVariant = computed(() => following.value ? 'outline' : 'default');

const toggleFollow = () => {
    isLoading.value = true;

    router.post(route('user.follow', { user: user.user_path }), {}, {
        preserveScroll: true,
        onSuccess: (response) => {
            following.value = !following.value;
            toast.success(
                following.value
                    ? `<span class="font-bold">✨ You started following <span class="text-blue-400">${user.name}</span>!</span>`
                    : `<span class="font-bold">👋 You stopped following <span class="text-gray-400">${user.name}</span></span>`,
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
  <div class="flex flex-col space-y-6">
    <!-- Banner with skeleton -->
    <div class="relative h-48 md:h-64 w-full overflow-hidden rounded-lg">
      <!-- <div v-if="loading || !user?.banner"
           class="h-full w-full bg-gradient-to-r from-zinc-200 to-zinc-300 dark:from-zinc-800 dark:to-zinc-700 animate-pulse">
      </div> -->
      <img :src="user.banner ? `${settings.url_cdn}/${user.banner}` : '/images/banner_default.webp'" alt="Profile banner" class="w-full h-full object-cover" />
    </div>

    <div class="flex flex-col md:flex-row gap-6 -mt-12 md:-mt-16">
      <!-- Profile photo with skeleton -->
      <div class="relative z-10 flex-shrink-0">
        <div class="relative h-24 w-24 md:h-32 md:w-32 rounded-full border-4 border-background overflow-hidden">
          <!-- <Skeleton v-if="loading || !user?.avatar" class="h-full w-full rounded-full" /> -->
          <Avatar class="h-full w-full">
            <AvatarImage :src="`${settings.url_cdn}/${user.cover}`" alt="Profile picture" />
            <AvatarFallback class="text-2xl">{{ user.name?.charAt(0) }}</AvatarFallback>
          </Avatar>
        </div>
      </div>
      <!-- Profile info with skeleton -->
      <div class="flex-1 space-y-2 pt-2 md:pt-4">
        <!-- <div v-if="loading">
          <Skeleton class="h-8 w-48 mb-2" />
          <Skeleton class="h-4 w-32 mb-3" />
          <Skeleton class="h-16 w-full md:w-3/4" />
        </div> -->
        <div>
          <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-50">{{ user?.name }}</h1>
          <!-- <p class="text-sm text-zinc-500 dark:text-zinc-400">@{{ user?.name?.toLowerCase().replace(/\s+/g, '') }}</p> -->
          <p v-if="user.description" class="mt-2 text-zinc-700 dark:text-zinc-300" v-html="user.description"/>
          <p v-else class="mt-2 text-zinc-700 dark:text-zinc-300">
            No bio at the moment but... What are you thinking about right now?
          </p>
        </div>

        <!-- Follower stats -->
        <div class="flex items-center gap-4 text-sm text-zinc-600 dark:text-zinc-400 mt-3">
          <!-- <div v-if="loading" class="flex gap-4">
            <Skeleton class="h-5 w-20" />
            <Skeleton class="h-5 w-24" />
          </div> -->
          <div class="flex gap-4">
            <!-- <Link :href="route('user.followers', user?.id)" class="hover:text-zinc-900 dark:hover:text-zinc-200"> -->
                <NumberFlowGroup class="hover:text-zinc-900 dark:hover:text-zinc-200">
                    <div style="--number-flow-char-height: 0.85em" class="flex items-center gap-2 font-semibold">
                        <NumberFlow :value="followersCount"/>
                        <p class="hover:underline cursor-pointer font-normal">Followers</p>
                    </div>
                </NumberFlowGroup>
                <NumberFlowGroup class="hover:text-zinc-900 dark:hover:text-zinc-200">
                    <div style="--number-flow-char-height: 0.85em" class="flex items-center gap-2 font-semibold">
                        <NumberFlow :value="stats.following"/>
                        <p class="hover:underline cursor-pointer font-normal">Following</p>
                    </div>
                </NumberFlowGroup>
            <!-- </Link> -->
            <!-- <Link :href="route('user.following', user?.id)" class="hover:text-zinc-900 dark:hover:text-zinc-200">
              <span class="font-semibold">{{ user?.following_count }}</span> Following
            </Link> -->
          </div>
        </div>
      </div>

      <!-- Follow button -->
      <div class="flex gap-2 mt-4 md:mt-6 md:self-start">
        <!-- <Button v-if="loading" disabled>
          <Icon icon="lucide:loader-2" class="mr-2 h-4 w-4 animate-spin" />
          Loading
        </Button> -->
        <!-- <Button @click="toggleFollow" :variant="followButtonVariant">
          <Icon v-if="!isFollowing" icon="lucide:user-plus" class="mr-2 h-4 w-4" />
          <Icon v-else icon="lucide:user-check" class="mr-2 h-4 w-4" />
          {{ followButtonText }}
        </Button> -->
        <SettingsUserButton v-if="isSelf" :order="orders" :gift="giftCards!"/>
        <vs-button v-else @click="toggleFollow" :loading="isLoading" :disabled="isLoading || isNullUser(auth)" shape="circle" size="xl" type="gradient" class="w-full font-['inter'] font-bold text-2xl">
            {{following ? 'Unfollow' : 'Follow'}}
            <!-- Seguir -->
            <template #animate>
                <Icon icon="iconoir:send-diagonal-solid" width="28" height="28" />
            </template>
        </vs-button>
      </div>
    </div>

    <!-- Tabs -->
    <Tabs v-model="activeTab" class="w-full">
      <TabsList class="grid w-full grid-cols-2 md:grid-cols-4">
        <TabsTrigger value="library">Library</TabsTrigger>
        <TabsTrigger value="reading" :disabled="true">Reading</TabsTrigger>
        <TabsTrigger value="continue" :disabled="true">Continue</TabsTrigger>
        <TabsTrigger value="comments" :disabled="true">Coments</TabsTrigger>
      </TabsList>

      <!-- Library Tab Content -->
      <TabsContent value="library" class="mt-6">
        <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-50 mb-4">Biblioteca</h3>
        <!-- <div v-if="loading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
          <div v-for="i in 10" :key="i" class="space-y-2 group">
            <Skeleton class="aspect-[3/4] rounded-md group-hover:opacity-80 transition-opacity" />
            <Skeleton class="h-4 w-full" />
            <Skeleton class="h-3 w-2/3" />
          </div>
        </div> -->
        <div v-if="comic.length > 0"  class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
          <Link v-for="(item, index) in comic":key="item.id" :href="route('comic', {slug: item.slug})"
                class="space-y-2 group transition-transform hover:scale-105">
            <div class="aspect-[3/4] rounded-md overflow-hidden bg-zinc-200 dark:bg-zinc-800">
              <img v-if="item.cover" :src="`${settings.url_cdn}/${item.cover}`" :alt="item.title" class="w-full h-full object-cover" >
              <div v-else class="w-full h-full flex items-center justify-center text-zinc-400">
                <Icon icon="lucide:book-open" class="h-12 w-12" />
              </div>
            </div>
            <h4 class="font-medium text-zinc-900 dark:text-zinc-50 line-clamp-1">{{ item.title }}</h4>
            <!-- <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ item.author }}</p> -->
          </Link>
        </div>
        <div v-else>
          <EmptyState mood="surprised">
              No Saved Items!
          </EmptyState>
        </div>
      </TabsContent>

      <!-- Reading Tab Content -->
      <TabsContent value="reading" class="mt-6">
        <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-50 mb-4">Currently Reading</h3>
        <div v-if="loading" class="space-y-4">
          <Card v-for="i in 5" :key="i">
            <CardContent class="p-4 flex gap-4">
              <Skeleton class="h-24 w-16 rounded" />
              <div class="flex-1 space-y-2">
                <Skeleton class="h-5 w-3/4" />
                <Skeleton class="h-4 w-1/2" />
                <div class="flex items-center gap-2 mt-2">
                  <Skeleton class="h-8 w-24" />
                  <Skeleton class="h-3 w-32" />
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
        <!-- <div v-else class="space-y-4">
          <Card v-for="manga in reading" :key="manga.id" class="hover:shadow-md transition-shadow">
            <CardContent class="p-4 flex gap-4">
              <div class="h-24 w-16 rounded overflow-hidden bg-zinc-200 dark:bg-zinc-800">
                <img v-if="manga.cover" :src="manga.cover" :alt="manga.title" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-zinc-400">
                  <Icon icon="lucide:book-open" class="h-8 w-8" />
                </div>
              </div>
              <div class="flex-1">
                <h4 class="font-medium text-zinc-900 dark:text-zinc-50">{{ manga.title }}</h4>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ manga.author }}</p>
                <div class="flex items-center gap-2 mt-3">
                  <Link :href="route('manga.read', { id: manga.id, chapter: manga.current_chapter })" class="inline-flex">
                    <Button size="sm">Continue Reading</Button>
                  </Link>
                  <Badge variant="outline">Ch. {{ manga.current_chapter }}</Badge>
                </div>
              </div>
            </CardContent>
          </Card>
        </div> -->
      </TabsContent>

      <!-- Continue Reading Tab Content -->
      <TabsContent value="continue" class="mt-6">
        <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-50 mb-4">Continue Reading</h3>
        <div v-if="loading" class="space-y-4">
          <Card v-for="i in 3" :key="i">
            <CardContent class="p-4">
              <div class="flex gap-4 mb-3">
                <Skeleton class="h-20 w-14 rounded" />
                <div class="flex-1 space-y-2">
                  <Skeleton class="h-5 w-3/4" />
                  <Skeleton class="h-4 w-1/2" />
                  <Skeleton class="h-3 w-1/3" />
                </div>
              </div>
              <Skeleton class="h-2.5 w-full rounded-full" />
              <div class="flex justify-between mt-2">
                <Skeleton class="h-3 w-16" />
                <Skeleton class="h-3 w-16" />
              </div>
            </CardContent>
          </Card>
        </div>
        <!-- <div v-else class="space-y-4">
          <Card v-for="manga in reading" :key="manga.id" class="hover:shadow-md transition-shadow">
            <CardContent class="p-4">
              <div class="flex gap-4 mb-3">
                <div class="h-20 w-14 rounded overflow-hidden bg-zinc-200 dark:bg-zinc-800">
                  <img v-if="manga.cover" :src="manga.cover" :alt="manga.title" class="w-full h-full object-cover" />
                  <div v-else class="w-full h-full flex items-center justify-center text-zinc-400">
                    <Icon icon="lucide:book-open" class="h-6 w-6" />
                  </div>
                </div>
                <div class="flex-1">
                  <h4 class="font-medium text-zinc-900 dark:text-zinc-50">{{ manga.title }}</h4>
                  <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ manga.author }}</p>
                  <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">
                    Last read {{ manga.last_read_at }}
                  </p>
                </div>
              </div>
              <Progress :value="manga.progress || 0" class="h-2.5" />
              <div class="flex justify-between mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                <span>Chapter {{ manga.current_chapter }}/{{ manga.total_chapters || '?' }}</span>
                <span>{{ manga.progress }}% complete</span>
              </div>
            </CardContent>
          </Card>
        </div> -->
      </TabsContent>

      <!-- Comments Tab Content -->
      <TabsContent value="comments" class="mt-6">
        <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-50 mb-4">Recent Comments</h3>
        <div v-if="loading" class="space-y-6">
          <Card v-for="i in 4" :key="i">
            <CardContent class="p-4">
              <div class="flex items-center gap-3 mb-3">
                <Skeleton class="h-10 w-10 rounded-full" />
                <div class="space-y-1 flex-1">
                  <div class="flex justify-between">
                    <Skeleton class="h-4 w-32" />
                    <Skeleton class="h-3 w-24" />
                  </div>
                  <Skeleton class="h-3 w-48" />
                </div>
              </div>
              <div class="space-y-2">
                <Skeleton class="h-4 w-full" />
                <Skeleton class="h-4 w-full" />
                <Skeleton class="h-4 w-2/3" />
              </div>
              <div class="flex items-center gap-4 mt-3">
                <Skeleton class="h-4 w-16" />
                <Skeleton class="h-4 w-16" />
              </div>
            </CardContent>
          </Card>
        </div>
        <!-- <div v-else class="space-y-6">
          <Card v-for="comment in comments" :key="comment.id">
            <CardContent class="p-4">
              <div class="flex items-center gap-3 mb-3">
                <Avatar class="h-10 w-10">
                  <AvatarImage :src="user?.avatar" :alt="user?.name" />
                  <AvatarFallback>{{ user?.name?.charAt(0) }}</AvatarFallback>
                </Avatar>
                <div class="space-y-1 flex-1">
                  <div class="flex justify-between">
                    <span class="font-medium text-zinc-900 dark:text-zinc-50">{{ user?.name }}</span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ comment.created_at }}</span>
                  </div>
                  <Link :href="route('manga.show', { id: comment.id, chapter: comment.chapter })" class="text-xs text-zinc-500 dark:text-zinc-400 hover:underline">
                    {{ comment.manga_title }} - Chapter {{ comment.chapter }}
                  </Link>
                </div>
              </div>
              <p class="text-zinc-700 dark:text-zinc-300">{{ comment.content }}</p>
              <div class="flex items-center gap-4 mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                <button class="flex items-center gap-1 hover:text-zinc-900 dark:hover:text-zinc-200 transition-colors">
                  <Icon icon="lucide:thumbs-up" class="h-4 w-4" />
                  Like ({{ comment.likes_count }})
                </button>
                <button class="flex items-center gap-1 hover:text-zinc-900 dark:hover:text-zinc-200 transition-colors">
                  <Icon icon="lucide:reply" class="h-4 w-4" />
                  Reply
                </button>
              </div>
            </CardContent>
          </Card>
        </div> -->
      </TabsContent>
    </Tabs>
  </div>
</template>

<style scoped>
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}

/* Smooth transitions */
.transition-transform {
  transition-property: transform;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

.transition-colors {
  transition-property: color, background-color, border-color;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

.transition-shadow {
  transition-property: box-shadow;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}
</style>
