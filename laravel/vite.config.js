import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/admin.css',
                'resources/css/landing.css', 
                'resources/css/wizard.css',
                'resources/css/profile.css',
                'resources/css/auth.css',
                'resources/js/app.js', 
                'resources/js/landing.js', 
                'resources/js/auth.js',
                'resources/js/wizard.js',
                'resources/js/projects.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
