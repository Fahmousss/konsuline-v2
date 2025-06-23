import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/login.css',
                'resources/css/landing.css',
                'resources/css/register.css',
                'resources/css/forgot-password.css',
                'resources/css/reset-password.css',
                'resources/css/admin/global.css',
                'resources/css/pasien/global.css',
                'resources/css/dokter/global.css',
                'resources/js/admin/global.js',
                'resources/css/admin/dashboard.css',
                'resources/js/admin/dashboard.js',
                'resources/js/admin/manajemen-pasien.js',
            ],
            refresh: true,
        }),
    ],
});
