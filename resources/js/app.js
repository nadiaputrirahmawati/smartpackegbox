import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import "../css/app.css";
import "./echo";
import * as PusherPushNotifications from "@pusher/push-notifications-web";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
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
    if (!("serviceWorker" in navigator)) return;

    try {
        window.localStorage.debug = "pusher:beams*";
        const instanceId =
            import.meta.env.VITE_PUSHER_BEAMS_INSTANCE_ID ||
            "e49f734a-45b3-454e-baf4-a83f348027f8";

        console.log("[Pusher] Mendaftarkan Service Worker Kustom...");

        const registration = await navigator.serviceWorker.register(
            "/service-worker.js",
            {
                scope: "/",
            },
        );

        console.log("[Pusher] Service Worker Kustom berhasil terikat!");

        const beamsClient = new PusherPushNotifications.Client({
            instanceId: instanceId,
            serviceWorkerRegistration: registration,
        });

        // ─── AMANKAN LOGIKA AKUN LAMA ─────────────────────────────────────
        // Ambil ID user yang saat ini sedang menempel di Pusher Beams browser
        const currentBeamsUser = await beamsClient
            .getUserId()
            .catch(() => null);

        // Jika ada akun lama terdaftar DAN ID-nya berbeda dengan user yang baru login
        if (currentBeamsUser && currentBeamsUser !== String(userId)) {
            console.warn(
                `[Pusher] Deteksi perbedaan akun! Menghentikan sesi User: ${currentBeamsUser} untuk User baru: ${userId}`,
            );

            // Hapus semua interest lama agar tidak bocor
            await beamsClient.clearDeviceInterests();
            // Hapus device token lama dari instance Pusher Beams
            await beamsClient.stop();

            console.log(
                "[Pusher] Sesi dan interest akun lama berhasil dibersihkan total.",
            );
        }
        // ──────────────────────────────────────────────────────────────────

        // Mulai/jalankan kembali client untuk user baru
        await beamsClient.start();

        const beamsTokenProvider = new PusherPushNotifications.TokenProvider({
            url: "/pusher/beams-auth",
        });

        // Daftarkan User ID baru ke server auth Pusher Anda
        await beamsClient.setUserId(String(userId), beamsTokenProvider);

        // Daftarkan kembali interest global/spesifik untuk akun yang baru
        await beamsClient.addDeviceInterest("debug-global-gate-alerts");

        console.log(
            `%c[Pusher] Beams PWA Sukses! Terkunci untuk User ID: ${userId}`,
            "color: #00ff00; font-weight: bold;",
        );
    } catch (error) {
        console.error("[Pusher] Beams gagal diinisialisasi:", error);
    }
}
