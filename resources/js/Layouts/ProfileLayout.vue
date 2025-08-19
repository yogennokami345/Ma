<script setup lang="ts">
import AvatarButton from "@/Components/ui/avatarButton/AvatarButton.vue";
import ButtonHeader from "@/Components/ui/buttonHeader/ButtonHeader.vue";
import Img from "@/Components/ui/Img.vue";
import InputSearch from "@/Components/ui/input-search/InputSearch.vue";
import { Icon } from "@iconify/vue";
import { Link } from "@inertiajs/vue3";
import { computed, onMounted } from "vue";
import route from "ziggy-js";

interface Props {
    auth: User;
}

const props = defineProps<Props>();
</script>

<template>
    <div class="flex flex-col min-h-screen">
        <header
            class="border-grid sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60"
        >
            <div
                class="container max-w-screen-2xl items-center flex py-1 justify-between"
            >
                <div class="flex items-center gap-2 py-2">
                    <Link :href="route('home')">
                        <Icon
                            icon="simple-icons:instatus"
                            width="40"
                            height="40"
                        />
                    </Link>
                    <!-- <ButtonHeader href="/comics" icon="arcticons:comicscreen" text="Comics" class="hidden md:flex" /> -->
                </div>

                <div class="flex items-center justify-center gap-4">
                    <InputSearch class="hidden md:block" />
                    <AvatarButton :auth="auth" :trigger="true" />
                </div>
            </div>
        </header>
        <div class="flex flex-col flex-1">
            <slot />
        </div>

        <footer
            class="bg-white rounded-lg shadow-sm m-4 dark:bg-primary-foreground"
        >
            <div
                class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between"
            >
                <span
                    class="text-sm text-gray-500 sm:text-center dark:text-gray-400"
                    >© {{ new Date().getFullYear() }}
                    <a
                        href="https://github.com/YanEmmanuel"
                        class="hover:underline"
                        >MrZero™</a
                    >. All Rights Reserved.
                </span>
                <ul
                    class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0"
                >
                    <li>
                        <Link
                            :href="route('dmca')"
                            class="hover:underline me-4 md:me-6"
                        >
                            DMCA
                        </Link>
                    </li>
                    <li>
                        <Link
                            :href="route('privacy')"
                            class="hover:underline me-4 md:me-6"
                        >
                            Privacy Policy
                        </Link>
                    </li>
                    <li>
                        <a
                            href="https://discord.gg/6wxuEPNs4Q"
                            class="hover:underline"
                            >Contact</a
                        >
                    </li>
                </ul>
            </div>
        </footer>
    </div>
</template>

<style>
.effect::before {
    --size: 45px;
    --line: color-mix(in lch, canvasText, transparent 85%);
    content: "";
    height: 100vh;
    width: 100vw;
    /* position: fixed; */
    background:
        linear-gradient(90deg, var(--line) 1px, transparent 1px var(--size)) 50%
            50% / var(--size) var(--size),
        linear-gradient(var(--line) 1px, transparent 1px var(--size)) 50% 50% /
            var(--size) var(--size);
    mask: linear-gradient(-20deg, transparent 50%, white);
    /* top: 0; */
    transform-style: flat;
    pointer-events: none;
    z-index: -1;
}
</style>
