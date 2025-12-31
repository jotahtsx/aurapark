import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        // O Tailwind DEVE vir antes do Laravel para processar o CSS corretamente na v4
        tailwindcss(), 
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
        // Adicionado para garantir que o Vite observe mudanças no tailwind.config.js
        watch: {
            usePolling: true,
        }
    },
});