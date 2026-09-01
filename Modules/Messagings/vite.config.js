import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { dirname } from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

export default defineConfig({
    build: {
        outDir: '../../public/build-messagings',
        emptyOutDir: true,
        manifest: true,
    },

    plugins: [
        laravel({
            publicDirectory: '../../public',

            buildDirectory: 'build-messagings',


            input: [
                __dirname + '/resources/assets/sass/app.scss',
                __dirname + '/resources/assets/js/app.js',
                            'resources/css/app.css',
                            'resources/js/app.js',
                'Modules/Messagings/resources/assets/js/app.js',
            ],

            refresh: true,
        }),
    ],
});
