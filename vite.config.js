import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Memberitahu Vite untuk listen di semua network interfaces
        cors: true,
        hmr: {
            host: '192.168.43.14', // Untuk Hot Module Replacement di komputer lokal Anda
        }
    },
});
