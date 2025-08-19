<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/Components/ui/dialog';
import { Icon } from '@iconify/vue';

interface Props {
    url?: string;
}
defineProps<Props>();
</script>

<template>
  <div>
    <Dialog>
        <DialogTrigger>
            <button class="p-3 rounded-xl group hover:bg-primary-foreground transition-colors">
                <Icon icon="pajamas:warning-solid" width="25" height="25" class="group-hover:text-red-500 group-hover:opacity-70 transition-colors opacity-50"/>
            </button>
        </DialogTrigger>
        <DialogContent>
        <DialogHeader class="flex flex-col gap-4">
            <DialogTitle class="flex flex-col justify-center items-center gap-5">
                <div class="bg-red-500/30 p-4 rounded-full flex">
                    <Icon icon="pajamas:warning-solid" width="35" height="35" class="text-red-500/80"/>
                </div>
                Reportar Erro
            </DialogTitle>
            <DialogDescription className="text-center">
            <p>Está com Problemas? Entre no nosso Discord e nos conte sobre ele!</p>

            <p class="text-xs text-muted-foreground mt-1">
                Inclua o número do Capítulo e detalhes do problema
            </p>
            </DialogDescription>
        </DialogHeader>

        <DialogFooter class="w-full flex flex-col gap-2 lg:flex-row items-center">
            <DialogClose as-child>
                <Button type="button" variant="secondary" class="w-full">
                    Fechar
                </Button>
            </DialogClose>
            <a :href="url" class="w-full">
                <Button class="w-full">
                    Entrar no Discord
                </Button>
            </a>
        </DialogFooter>
        </DialogContent>
    </Dialog>
  </div>
</template>

<style scoped>

</style>
