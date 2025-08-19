<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
  href: string;
  icon: string;
  text: string;
}>();

</script>

<template>
    <Link :href="href"
        class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-primary-foreground transition-colors">
    <Icon :icon="icon" width="25" height="25" />
    <p>
        {{ text }}
    </p>
    </Link>
</template>