import { defineConfig } from 'vite';
import { fileURLToPath, URL } from 'node:url';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import autoprefixer from 'autoprefixer'
import tailwind from 'tailwindcss'

export default defineConfig({
    css: {
        postcss: {
          plugins: [tailwind(), autoprefixer()],
        },
      },
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('resources/js', import.meta.url)),
        },
    },
});
