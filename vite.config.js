import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import tailwindcss from "@tailwindcss/vite";
import path from "path";
import { VitePWA } from "vite-plugin-pwa";

export default defineConfig({
    base: "/",
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
        VitePWA({
            outDir: "public",
            buildBase: "/", 
            scope: "/",
            registerType: "autoUpdate",
            injectRegister: false, // Kita biarkan tetap false karena kita panggil manual di app.js sesuai urutan login
            workbox: {
                // KUNCI AMAN 1: Hanya cache file JS & CSS hasil build di folder assets, manifest tidak akan disentuh!
                globPatterns: [
                    "assets/*.{js,css}",
                ],
                navigateFallback: null,
                cleanupOutdatedCaches: true,
                clientsClaim: true,
                maximumFileSizeToCacheInBytes: 2 * 1024 * 1024, 
            },
            manifest: {
                name: "Smart Package Box",
                short_name: "SmartBox",
                start_url: "/",
                description: "Sistem Penerimaan Paket Otomatis berbasis IoT",
                theme_color: "#ffffff",
                background_color: "#ffffff",
                display: "standalone",
                orientation: "portrait",
                icons: [
                    {
                        src: "/images/iconsbaru.png",
                        sizes: "192x192",
                        type: "image/png",
                    },
                    {
                        src: "/images/iconsbaru.png", 
                        sizes: "512x512",
                        type: "image/png",
                    },
                ],
            },
            devOptions: {
                enabled: false, // Amankan mode dev sesuai saran rekan Anda
                type: "module",
            },
        }),
    ],
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js"),
        },
    },
});