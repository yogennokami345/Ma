<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import route from 'ziggy-js';

defineProps({
  paid: Boolean,
})
</script>

<template>
  <div class="h-screen flex flex-1 flex-col justify-center items-center text-white">
    <div>
      <svg v-if="paid" viewBox="0 0 24 24" class="text-green-600 w-16 h-16 mx-auto my-6">
        <path fill="currentColor"
          d="M12,0A12,12,0,1,0,24,12,12.014,12.014,0,0,0,12,0Zm6.927,8.2-6.845,9.289a1.011,1.011,0,0,1-1.43.188L5.764,13.769a1,1,0,1,1,1.25-1.562l4.076,3.261,6.227-8.451A1,1,0,1,1,18.927,8.2Z">
        </path>
      </svg>
      <svg v-else fill="#e01b24" class="text-green-600 w-16 h-16 mx-auto my-6" viewBox="0 -8 528 528"
        xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
          <title>fail</title>
          <path
            d="M264 456Q210 456 164 429 118 402 91 356 64 310 64 256 64 202 91 156 118 110 164 83 210 56 264 56 318 56 364 83 410 110 437 156 464 202 464 256 464 310 437 356 410 402 364 429 318 456 264 456ZM264 288L328 352 360 320 296 256 360 192 328 160 264 224 200 160 168 192 232 256 168 320 200 352 264 288Z">
          </path>
        </g>
      </svg>
      <div class="text-center">
        <div v-if="paid">
          <h3 class="md:text-2xl text-base font-semibold text-center">Pagamento realizado!</h3>
          <p class="my-2">Obrigado por concluir seu pagamento online com segurança.</p>
          <p>Tenha um ótimo dia!</p>
        </div>
        <div v-else>
          <h3 class="md:text-2xl text-base font-semibold text-center">Pagamento falhou!</h3>
          <p class="my-2">Desculpe, mas seu pagamento não foi concluído.</p>
          <p>Por favor, tente novamente mais tarde.</p>
        </div>

        <div class="py-10 text-center">
          <Link :href="route('home')" class="w-full">
          <vs-button class="w-full" color="#3b82f6" type="gradient">
            Inicio
          </vs-button>
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>