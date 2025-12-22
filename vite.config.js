import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/ops-dashboard.js",
            ],
            refresh: true,
        }),
    ],

    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },

    server: {
        hmr: {
            overlay: true,
        },
        watch: {
            usePolling: true,
        },
    },

    build: {
        sourcemap: false,
        target: "es2018",
        chunkSizeWarningLimit: 1200,
    },
});
