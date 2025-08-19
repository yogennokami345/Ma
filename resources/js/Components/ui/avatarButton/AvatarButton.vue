<script setup lang="ts">
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/Components/ui/dropdown-menu';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Icon } from "@iconify/vue";
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, reactive, ref } from 'vue';
import { useUserStore } from '@/Stores/useUser';
import route from 'ziggy-js';

interface Props {
  auth: User;
  trigger?: boolean;
  size?: 'sm' | 'base' | 'lg' | 'xl' | 'xxl';
}

const props = withDefaults(defineProps<Props>(), {
  trigger: false,
  size: 'sm',
});

const user = props.auth;

const initials = computed<string>(() => {
  if (!user || !user.name) return '??';
  return user.name
    .split(' ')
    .map((part: string) => part.charAt(0))
    .join('')
    .toUpperCase();
});

const saudacao = ref('');

const atualizarSaudacao = () => {
  const horaAtual = new Date().getHours();

  if (horaAtual >= 5 && horaAtual < 12) {
    saudacao.value = 'Bom dia';
  } else if (horaAtual >= 12 && horaAtual < 18) {
    saudacao.value = 'Boa tarde';
  } else {
    saudacao.value = 'Boa noite';
  }
};

onMounted(() => {
  atualizarSaudacao();
});

const intervalo = setInterval(atualizarSaudacao, 60000);

onUnmounted(() => {
  clearInterval(intervalo);
});


function submit() {
  router.post('/logout')
}

const userStore = useUserStore()
// @ts-ignore
const baseUrl = import.meta.env.VITE_CDN_URL
</script>

<template>
  <div>
    <Link :href="route('user', {id: user.user_path})"  v-if="trigger" >
      <Avatar :size="size">
        <template v-if="user.cover">
            <AvatarImage :src="`${baseUrl}/${user.cover}`" :alt="user.name"/>
        </template>
        <AvatarFallback class="z-0">{{ initials }}</AvatarFallback>
      </Avatar>
    </Link>
    <DropdownMenu v-else>
        <DropdownMenuTrigger>
        <Avatar>
            <template v-if="user.cover">
              <!-- @vue-ignore -->
            <AvatarImage :src="`${baseUrl}/${user.cover}`" :alt="user.name" />
            </template>
            <AvatarFallback>{{  initials }}</AvatarFallback>
        </Avatar>
        </DropdownMenuTrigger>
        <DropdownMenuContent>
        <DropdownMenuLabel>Olá, {{ saudacao }}</DropdownMenuLabel>
        <DropdownMenuSeparator />
        <Link :href="route('home')">
          <DropdownMenuItem class="items-center flex">
              <Icon icon="solar:user-bold" width="24" height="24" />
              <span>
                  Perfil
              </span>
          </DropdownMenuItem>
        </Link>
        <DropdownMenuSeparator />
        <DropdownMenuItem @click="submit" class="cursor-pointer">
            <Icon icon="material-symbols:logout-rounded" width="24" height="24" />
            <span>
                Logout
            </span>
        </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
  </div>
</template>

<style scoped>

</style>
