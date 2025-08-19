<script lang="ts" setup>
import { GiftCard } from '@/Interfaces/GiftCard';
import { Icon } from '@iconify/vue';
import { ref } from 'vue'
import GiftCardComponent from './ui/gift-card/GiftCardComponent.vue';
// @ts-ignore
import OrderListComponent from './ui/orderComponents/OrderListComponent.vue';
import { FileUpload, FileUploadGrid } from './ui/file-upload';
import ProfileEditor from './ui/profileEditor/ProfileEditor.vue';

interface Props{
    gift: GiftCard[];
    order: Order[];
}

defineProps<Props>();

const active = ref(false)
const generalSetting = ref(false)
const ordersSetting = ref(false)
const giftsSetting = ref(false)
</script>
<template>
    <div class="center">
        <vs-button @click="active = !active" class="w-full font-['inter'] font-bold text-2xl" hape="circle" size="xl" type="flat" color="rgb(75,107,251)" animation-type="vertical"> 
            Configurações
            <template #animate>
            <Icon icon="bi:gear-fill" width="28" height="28" />
            </template>
        </vs-button>
        <vs-dialog v-model="active" scroll lock-scroll>
            <template #header>
                <h3 class="text-xl font-semibold">Configurações</h3>
            </template>
            <div>
                <ul>
                    <li>
                        <button @click="generalSetting = !generalSetting" class="flex hover:bg-selectedSax rounded-md w-full py-4 px-3 transition-colors justify-between group items-center">
                            <div class="flex items-center gap-2">
                                <Icon icon="material-symbols:clear-all-rounded" width="24" height="24" />
                                Geral
                            </div>
                            <Icon icon="iconamoon:arrow-up-2" width="24" height="24" class="transform rotate-90 group-hover:translate-x-1 transition-all duration-500"/>
                        </button>
                    </li>
                    <li>
                        <button @click="ordersSetting = !ordersSetting" class="flex hover:bg-selectedSax rounded-md w-full py-4 px-3 transition-colors justify-between group items-center">
                            <div class="flex items-center gap-2">
                                <Icon icon="flowbite:list-outline" width="24" height="24" />
                                Minhas Compras
                            </div>
                            <Icon icon="iconamoon:arrow-up-2" width="24" height="24" class="transform rotate-90 group-hover:translate-x-1 transition-all duration-500"/>
                        </button>
                    </li>
                    <li>
                        <button @click="giftsSetting = !giftsSetting" class="flex hover:bg-selectedSax rounded-md w-full py-4 px-3 transition-colors justify-between group items-center">
                            <div class="flex items-center gap-2">
                                <Icon icon="f7:giftcard-fill" width="24" height="24" />
                                Meus GiftCards
                            </div>
                            <Icon icon="iconamoon:arrow-up-2" width="24" height="24" class="transform rotate-90 group-hover:translate-x-1 transition-all duration-500"/>
                        </button>
                    </li>
                </ul>
            </div>
        </vs-dialog>

        <vs-dialog v-model="generalSetting" scroll lock-scroll width="550px">
            <template #header>
                <h3 class="text-xl font-semibold">Geral</h3>
            </template>
            <ProfileEditor />
        </vs-dialog>

        <vs-dialog v-model="ordersSetting" scroll lock-scroll width="550px">
            <template #header>
                <h3 class="text-xl font-semibold">Histórico de Pedidos</h3>
            </template>
            <OrderListComponent :orders="order" />
        </vs-dialog>

        <vs-dialog v-model="giftsSetting" scroll lock-scroll width="550px">
          <template #header>
            <h3 class="text-xl font-semibold">Meus GiftCards</h3>
          </template>
          <div class="">
            <div class="flex flex-col gap-4">
              <GiftCardComponent
                v-for="card in gift" 
                :key="card.id" 
                :gift-card="card" 
              />
            </div>
          </div>
        </vs-dialog>
    </div>
</template>

  