<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/Components/ui/dialog';
import { Icon } from '@iconify/vue';
import Slider from '@/Components/ui/slider/Slider.vue';
import { cn } from '@/lib/utils';
import { ref } from 'vue';
import Switch from '@/Components/ui/switch/Switch.vue';
import { useReaderSettings } from '@/Stores/useReaderSettings';

const readerSettings = useReaderSettings();

function resetSettings() {
  readerSettings.resetSettings();
}


</script>

<template>
  <div>
    <Dialog>
        <DialogTrigger>
            <button class="p-3 rounded-xl group hover:bg-primary-foreground transition-colors">
                <Icon icon="mdi:gear" width="24" height="24" class="group-hover:opacity-80 opacity-55 transition-colors"/>
            </button>
        </DialogTrigger>
        <DialogContent>
        <DialogHeader class="flex flex-col gap-4">
            <DialogTitle class="flex flex-col justify-center items-center gap-5">
               Configurações do Leitor
            </DialogTitle>
            <DialogDescription className="text-center">
            
            </DialogDescription>
        </DialogHeader>
        <ul class="flex flex-col gap-4">
            <li class="flex items-center gap-4 justify-between font-medium text-sm">
                <h1>
                    Brilho
                </h1>
                <Slider v-model="readerSettings.brightness" :max="200" :step="1" class="w-1/2"/>
            </li>
            <li class="flex items-center gap-4 justify-between font-medium text-sm">
                <h1>
                    Contraste
                </h1>
                <Slider v-model="readerSettings.contrast" :max="200" :step="1" class="w-1/2"/>
            </li>
            <li class="flex items-center gap-4 justify-between font-medium text-sm">
                <h1>
                    Saturação
                </h1>
                <Slider v-model="readerSettings.saturation" :max="200" :step="1" class="w-1/2"/>
            </li>
            <li class="flex items-center gap-4 justify-between font-medium text-sm">
                <h1>
                    Sepia
                </h1>
                <Slider v-model="readerSettings.sepia" :max="100" :step="1" class="w-1/2"/>
            </li>
            <li class="flex items-center gap-4 justify-between font-medium text-sm">
                <h1>
                    Girar Matiz
                </h1>
                <Slider v-model="readerSettings.hueRotate" :max="100" :step="1" class="w-1/2"/>
            </li>
            <li class="flex items-center gap-4 justify-between font-medium text-sm">
                <h1>
                    Negativo
                </h1>
                <!-- <Switch :checked="readerSettings.negative" @update:checked="(value: boolean) => readerSettings.setNegative(value)"/> -->
                <vs-switch v-model="readerSettings.negative" color="success">
                    <template #off>
                        <Icon icon="ic:round-cancel" width="15" height="15" />
                    </template>
                    <template #on>
                        <Icon icon="lets-icons:check-fill" width="15" height="15" />
                    </template>
                </vs-switch>
            </li>
            <li class="flex items-center gap-4 justify-between font-medium text-sm">
                <h1>
                    Preto e Branco
                </h1>
                <!-- <Switch :checked="readerSettings.greyscale" @update:checked="(value: boolean) => readerSettings.setGreyscale(value)"/> -->
                <vs-switch v-model="readerSettings.greyscale" color="success">
                    <template #off>
                        <Icon icon="ic:round-cancel" width="15" height="15" />
                    </template>
                    <template #on>
                        <Icon icon="lets-icons:check-fill" width="15" height="15" />
                    </template>
                </vs-switch>
            </li>
            <li class="flex items-center gap-4 justify-between font-medium text-sm">
                <h1>
                    Fixar Infos
                </h1>
                <!-- <Switch :checked="readerSettings.greyscale" @update:checked="(value: boolean) => readerSettings.setGreyscale(value)"/> -->
                <vs-switch v-model="readerSettings.fixed" color="success">
                    <template #off>
                        <Icon icon="ic:round-cancel" width="15" height="15" />
                    </template>
                    <template #on>
                        <Icon icon="lets-icons:check-fill" width="15" height="15" />
                    </template>
                </vs-switch>
            </li>
        </ul>
        <DialogFooter class="w-full flex flex-col gap-2 lg:flex-row items-center">
            <Button type="button" variant="secondary" class="w-full mt-4" @click="resetSettings">
                Resetar
            </Button>
        </DialogFooter>
        </DialogContent>
    </Dialog>
  </div>
</template>

<style scoped>

</style>