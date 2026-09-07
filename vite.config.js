import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'Modules/Messagings/resources/assets/js/app.js',
                'Modules/Announcement/resources/assets/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
