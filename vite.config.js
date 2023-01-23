import fs from 'fs';
import laravel from 'laravel-vite-plugin'
import {defineConfig} from 'vite'
import {homedir} from 'os'
import {resolve} from 'path'

let host = 'joelbutcher.com';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/tailwind.css',
                'resources/js/site.js',
            ],
            valetTls: 'joelbutcher.com.test'
        }),
    ],
});
