<script setup lang="ts">
import Empty from '@/Layouts/Empty.vue';
import { Icon } from '@iconify/vue';
import { computed, reactive, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
// @ts-ignore
import route from 'ziggy-js';

defineOptions({ layout: Empty })

const name = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')

const hasVisiblePassword = ref(false)

const inputType = computed(() =>
  hasVisiblePassword.value ? 'text' : 'password'
)

const gridImgUrl = computed(() => `${import.meta.env.BASE_URL}images/grid.svg`);

function submit() {
  router.post(route('registerApi'), {
    name: name.value,
    email: email.value,
    password: password.value,
    password_confirmation: confirmPassword.value
  }, {
    onSuccess: () => {
      toast.success('Registro realizado com sucesso!', { theme: 'dark', dangerouslyHTMLString: true })
    },
    onError: (errors) => {
      toast.error(errors.email, { theme: 'dark', dangerouslyHTMLString: true })
    }
  })
}
</script>

<template>
  <Head title="Registro"/>
  <div class="relative w-screen h-screen">

    <div class="absolute inset-0 opacity-20 pointer-events-none"
      style="background: radial-gradient(91.76% 91.76% at 0% 0%, rgb(50, 138, 241) 0%, rgba(50, 138, 241, 0) 100%);"
      aria-hidden="true" />

    <div style="background-image: linear-gradient(transparent 80%, rgb(19, 23, 28));"
      class="absolute inset-0 z-20 pointer-events-none" />

    <div
      class="hidden md:block opacity-4 lg:opacity-[8%] w-screen absolute bottom-0 left-0 right-0 top-0 z-10 overflow-hidden">
      <video class="mx-auto h-full w-full object-cover" autoplay="true" loop="true" playsinline="true" muted="true">
        <source
          src="/public/videos/vid1.mp4"
          type="video/mp4">
      </video>
    </div>

    <div class="absolute inset-0 w-full h-full pointer-events-none z-20" aria-hidden="true">
      <img :src="gridImgUrl" alt=""
           class="w-full h-full opacity-20 object-cover">
    </div>

    <div class="absolute flex justify-center items-center z-30 w-full h-full flex-col">
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-background dark:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight text-center tracking-tight text-gray-900 md:text-2xl dark:text-white">
                  Logue com sua conta
              </h1>
            <div class="flex flex-col gap-4">
              <!-- <a :href="route('discordAuth')">
                <vs-button block>
                  <Icon icon="ic:outline-discord" class="text-3xl"/>
                  <template #animate>
                    <h1 class="text-xl font-bold leading-tight text-center tracking-tight text-gray-900 md:text-xl dark:text-white">
                      Entrar com Discord
                    </h1>
                  </template>
                </vs-button>
              </a>
                <div class="relative flex items-center">
                  <div class="flex-grow border-t border-gray-300 dark:border-gray-600"/>
                  <span class="flex-shrink mx-4 text-gray-400 dark:text-gray-500">Ou</span>
                  <div class="flex-grow border-t border-gray-300 dark:border-gray-600"/>
                </div> -->

                <form class="flex flex-col gap-5 items-center" action="#" @submit.prevent="submit">
                  <div class="flex flex-col gap-3 w-full">
                    <div>
                        <vs-input v-model="name" placeholder="Nome" block type="text" label-float class="w-full" required>
                          <!-- <template #icon>
                            <Icon icon="entypo:email" class="text-xl"/>
                          </template> -->
                        </vs-input>
                    </div>
                    <div>
                        <vs-input v-model="email" placeholder="Email" block type="email" label-float class="w-full" required>
                          <template #icon>
                            <Icon icon="entypo:email" class="text-xl"/>
                          </template>
                        </vs-input>
                    </div>
                    <div>
                        <vs-input v-model="password" placeholder="Senha"  label-float icon-after :type="inputType" block  @click-icon="hasVisiblePassword = !hasVisiblePassword" required>
                          <template #icon>
                            <Icon v-if="!hasVisiblePassword" icon="jam:padlock-alt-f" class="text-xl"/>
                            <Icon v-else icon="jam:padlock-alt-open-f" class="text-xl"/>
                          </template>
                        </vs-input>
                    </div>
                    <div>
                        <vs-input v-model="confirmPassword" placeholder="Confirme sua Senha"  label-float icon-after :type="inputType" block  @click-icon="hasVisiblePassword = !hasVisiblePassword" required>
                          <template #icon>
                            <Icon v-if="!hasVisiblePassword" icon="jam:padlock-alt-f" class="text-xl"/>
                            <Icon v-else icon="jam:padlock-alt-open-f" class="text-xl"/>
                          </template>
                        </vs-input>
                    </div>
                  </div>

                  <vs-button block>
                    Registrar
                  </vs-button>
                  <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                      Ja tem uma conta?
                      <Link :href="route('login')" class="font-medium text-primary-600 hover:underline dark:text-primary-500">
                        Entrar
                      </Link>
                  </p>
                </form>
            </div>
          </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
