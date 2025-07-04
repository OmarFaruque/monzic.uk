import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({

    server: {
  https: true,
  host: '0.0.0.0',
  origin: 'https://044a-103-16-24-142.ngrok-free.app', // your public URL
  cors: true,
},
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
