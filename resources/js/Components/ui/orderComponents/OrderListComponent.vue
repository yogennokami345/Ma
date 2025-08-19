<template>
    <div class="bg-[#12161a]  p-4 rounded-lg">
      <h3 class="text-lg font-semibold text-white mb-4">Meus Pedidos</h3>
      
      <div class="space-y-3">
        <div v-for="order in orders" :key="order.id" 
             class="bg-[#1E2023] rounded-lg p-3 flex flex-col sm:flex-row sm:items-center justify-between">
          <div class="flex-1 mb-2 sm:mb-0">
            <div class="flex items-center">
              <span class="text-white/70 text-xs mr-2">ID:</span>
              <span class="text-white text-sm font-medium truncate max-w-[120px]">{{ order.id }}</span>
            </div>
            
            <div class="flex items-center mt-1">
              <span class="text-white/70 text-xs mr-2">Plano:</span>
              <span class="text-white text-sm">{{ order.plan_id.name }}</span>
              <span class="text-white/50 text-xs ml-2">(ID: {{ order.plan_id.id }})</span>
            </div>
          </div>
          
          <div class="flex flex-col sm:items-end">
            <span 
              class="px-2 py-1 rounded-full text-xs font-medium inline-block text-center w-20 mb-2 sm:mb-1"
              :class="order.paid ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400'"
            >
              {{ order.paid ? 'Pago' : 'Pendente' }}
            </span>
            
            <div class="flex text-xs text-white/50 space-x-2">
              <span>{{ formatDate(order.created_at) }}</span>
            </div>
          </div>
        </div>
        
        <div v-if="!orders.length" class="text-center py-4 text-white/70">
          Nenhum pedido encontrado.
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  
  interface Props{
    orders: Order[];
  }
  const props = defineProps<Props>();
  

  const formatDate = (dateString: string) => {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('pt-BR', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    }).format(date);
  };
  </script>