import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/css/app.css',
                'resources/css/app_dark.css',
                'resources/js/app.js',
                'resources/js/tapp.js',
                'resources/js/modules/index.js',

                'resources/js/modules/logistic/pickups.js',
                'resources/js/modules/logistic/pickup.js',

                'resources/js/modules/support/branch_inventories/index.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    },
});