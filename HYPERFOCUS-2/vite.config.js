import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    // Este bloque asegura que se use HTTPS y URLs correctas
    server: {
        https: true,
        host: '0.0.0.0', // importante para Docker
        port: 5173,       // o el puerto que uses localmente para dev
    },
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            input: ['resources/js/app.js'],
        },
    },
});
