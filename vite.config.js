import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
            'resources/routes/**',
            'routes/**',
            'resources/views/**',
        ],
        }),
    ],
    server: {
        host: '0.0.0.0', // Memberitahu Vite untuk listen di semua network interfaces
        cors: true,
        hmr: {
            host: '192.168.43.111', // Untuk Hot Module Replacement di komputer lokal Anda
        }
    },
});
