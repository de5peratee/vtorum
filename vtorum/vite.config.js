import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/tags.js',
                'resources/js/add_record.js',
                'resources/js/transfer.js',


                'resources/css/style.css',
                'resources/css/header.css',
                'resources/css/footer.css',
                'resources/css/button.css',
                'resources/css/record.css',
            ],
            refresh: true,
        }),
    ],
});
