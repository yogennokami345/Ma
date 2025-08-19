<script setup lang="ts">
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/Components/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/Components/ui/carousel';
import isNullUser from '@/Utils/nullUser';
import { Head, Link } from '@inertiajs/vue3';
import route from 'ziggy-js';

interface Plan {
  id: string;
  name: string;
  description: string;
  price: number;
  days: number;
}


defineProps<{
    auth: User;
    plans: PaginatedResponse<Plan>
}>();

</script>

<template>
<Head title="Shopping"/>
  <div class="h-screen flex flex-1 flex-col justify-center items-center">
    <div class="max-w-2xl mx-auto mb-16 text-center">
      <h2 class="text-4xl font-bold lg:text-5xl">Escolha seu Plano</h2>
    </div>
    <div class="w-full">
      <Carousel class="w-3/4 lg:w-1/2 mx-auto">
        <CarouselContent>
          <CarouselItem v-for="plan in plans.data" :key="plan.id" class=" lg:basis-1/2">
            <Card class="flex flex-col justify-between h-96">
              <CardHeader>
                <h4 class="text-xl lg:text-2xl font-bold">{{ plan.name }}</h4>
                <p class="text-6xl font-bold">R${{ plan.price }}<span class="text-sm tracking-wide hidden lg:inline"> / {{ plan.days }}
                    dias</span></p>
                <p class="text-sm tracking-wide lg:hidden block font-bold text-muted-foreground">
                    {{ plan.days }} dias
                </p>
              </CardHeader>
              <CardContent>
                <div id="shopping-card" v-html="plan.description" />
              </CardContent>
              <CardFooter>
                <Link :href="isNullUser(auth) ? route('login') : route('pay', {id: plan.id})" class="w-full">
                    <vs-button class="w-full" color="#3b82f6" type="gradient">
                      Comprar
                    </vs-button>
                </Link>
              </CardFooter>
            </Card>
          </CarouselItem>
        </CarouselContent>
        <CarouselPrevious />
        <CarouselNext />
      </Carousel>
    </div>
  </div>
</template>

<style>
#shopping-card ul li {
  list-style: none;
  color: white;
  position: relative;
  line-height: 1.8;
}

#shopping-card ul li::before {
  content: "✔";
  color: white;
  font-size: 1em;
  margin-right: 8px;
}
</style>
