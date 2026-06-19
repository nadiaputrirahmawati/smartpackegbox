import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import "../css/app.css";
import './echo';
import * as PusherPushNotifications from "@pusher/push-notifications-web";

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        const loggedInUser = props.initialPage.props.auth?.user;
        
        // Panggil inisialisasi Pusher Beams
        if (loggedInUser) {
            initPusherBeams(loggedInUser.id);
        }

        app.use(plugin).mount(el);
    },
});

// Fungsi Inisialisasi Pusher Beams + Registrasi Manual SW PWA
// Sesuaikan potongan fungsi initPusherBeams di app.js Anda pada bagian pendaftaran:
async function initPusherBeams(userId) {
    if (!('serviceWorker' in navigator)) return;

    try {
        window.localStorage.debug = "pusher:beams*"; 
        const instanceId = import.meta.env.VITE_PUSHER_BEAMS_INSTANCE_ID || "e49f734a-45b3-454e-baf4-a83f348027f8";

        console.log("[Pusher] Mendaftarkan Service Worker Kustom...");
        
        // UBAH JALUR INI: Mengarah ke file service-worker.js baru kita di root public
        const registration = await navigator.serviceWorker.register('/service-worker.js', {
            scope: '/'
        });

        console.log("[Pusher] Service Worker Kustom berhasil terikat!");

        const beamsClient = new PusherPushNotifications.Client({
            instanceId: instanceId,
            serviceWorkerRegistration: registration, 
        });

        await beamsClient.start();
        
        const beamsTokenProvider = new PusherPushNotifications.TokenProvider({
            url: "/pusher/beams-auth",
        });

        await beamsClient.setUserId(String(userId), beamsTokenProvider);
        await beamsClient.addDeviceInterest("debug-global-gate-alerts");

        console.log(`%c[Pusher] Beams PWA Sukses! Terkunci untuk User ID: ${userId}`, "color: #00ff00; font-weight: bold;");
    } catch (error) {
        console.error("[Pusher] Beams gagal diinisialisasi:", error);
    }
}