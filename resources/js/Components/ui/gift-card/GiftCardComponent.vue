<template>
    <div class="bg-[#12161a] p-3 rounded-lg w-full">
        <div class="rounded-xl p-5 text-white shadow-lg w-full">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-white/10">
                <h3 class="text-lg font-semibold m-0">Cartão de Presente</h3>
                <span class="px-3 py-1 rounded-full text-xs font-medium"
                    :class="giftCard.active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'">
                    {{ giftCard.active ? 'Ativo' : 'Inativo' }}
                </span>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-white/70 text-xs">Código:</span>
                    <!-- <button @click="copyCode"
                        class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded transition-colors">
                        {{ copied ? 'Copiado!' : 'Copiar' }}
                    </button> -->
                    <vs-button type="flat" :active="copied" @click="copyCode" size="sm">
                        {{ copied ? 'Copiado!' : 'Copiar' }}
                    </vs-button>
                </div>
                <div class="font-medium text-sm bg-[#252729] p-2 rounded overflow-hidden">
                    {{ truncatedCode }}
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="flex flex-col">
                    <span class="text-white/70 text-xs mb-1">Nome do Plano:</span>
                    <span class="font-medium">{{ giftCard.plan_id.name }}</span>
                </div>

                <div class="flex flex-col">
                    <span class="text-white/70 text-xs mb-1">Uso:</span>
                    <span class="font-medium">{{ giftCard.usage_count }} / {{ giftCard.usage_limit }}</span>
                </div>

                <div class="flex flex-col">
                    <span class="text-white/70 text-xs mb-1">Expira em:</span>
                    <span class="font-medium">{{ formatDate(giftCard.expires_at) }}</span>
                </div>

                <div class="flex flex-col">
                    <span class="text-white/70 text-xs mb-1">Proprietário:</span>
                    <span class="font-medium">{{ giftCard.owner_user_id?.name ?? 'Não atribuído' }}</span>
                </div>
            </div>

            <div class="flex justify-between pt-3 border-t border-white/10 text-xs">
                <div class="flex flex-col">
                    <span class="text-white/70 text-xs mb-1">Criado em:</span>
                    <span class="text-xs">{{ formatDate(giftCard.created_at) }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-white/70 text-xs mb-1">Atualizado em:</span>
                    <span class="text-xs">{{ formatDate(giftCard.updated_at) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { GiftCard } from '@/Interfaces/GiftCard';
import { Icon } from '@iconify/vue';
import { computed, ref } from 'vue';


interface Props {
    giftCard: GiftCard
}

const props = defineProps < Props > ()

const copied = ref(false);

// const ownerName = computed(() => {
//     if (props.giftCard.owner_user_id && props.giftCard.owner_user_id.name) {
//         return props.giftCard.owner_user_id.name;
//     }
//     return 'Não atribuído';
// });

const truncatedCode = computed(() => {
    if (props.giftCard.code.length > 40) {
        return props.giftCard.code.substring(0, 40) + '...';
    }
    return props.giftCard.code;
});

const formatDate = (dateString: string) => {
    if (!dateString) return 'N/A';

    const date = new Date(dateString);
    return new Intl.DateTimeFormat('pt-BR', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    }).format(date);
};

const copyCode = () => {
    navigator.clipboard.writeText(props.giftCard.code)
        .then(() => {
            copied.value = true;
            setTimeout(() => {
                copied.value = false;
            }, 2000);
        })
        .catch(err => {
            console.error('Erro ao copiar: ', err);
        });
};
</script>