import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [vue()],
    build: {
        rollupOptions: {
            input: {
                main: 'resources/js/app.js',
            },
        },
    },
    css: {
        devSourcemap: true,
    },
});
