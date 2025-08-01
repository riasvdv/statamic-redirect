import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import statamic from './vendor/statamic/cms/resources/js/vite-plugin';
import vue from '@vitejs/plugin-vue';
import { viteExternalsPlugin } from 'vite-plugin-externals';
import svgLoader from 'vite-svg-loader';

export default defineConfig({
    plugins: [
        statamic(),
        laravel({
            hotFile: 'resources/dist/hot',
            publicDirectory: 'resources/dist',
            input: ['resources/js/cp.js'],
        }),
        vue(),
        viteExternalsPlugin({
            vue: 'Vue',
            pinia: 'Pinia',
            'vue-demi': 'Vue',
        }),
        svgLoader(),
    ],
    server: {
        hmr: false,
    },
});
