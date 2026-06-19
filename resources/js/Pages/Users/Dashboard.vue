<template>
    <UserLayout>
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
            <div
                class="xl:col-span-3 bg-white rounded-[2rem] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-secondary-600 relative overflow-hidden group hover:shadow-lg transition-shadow"
            >
                <div class="flex justify-between items-start mb-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-tertiary-800 flex items-center justify-center text-tertiary-200"
                    >
                        <PackageIcon class="w-6 h-6" />
                    </div>
                    <span
                        class="text-[15px] font-extrabold text-gray-400 tracking-widest uppercase"
                        >Dalam Perjalanan</span
                    >
                </div>
                <h2 class="text-5xl font-extrabold text-primary mb-2">
                    {{
                        activePackagesCount < 10
                            ? "0" + activePackagesCount
                            : activePackagesCount
                    }}
                </h2>
                <p class="text-secondary-200 font-bold text-sm mb-6">
                    Total Data Paket
                </p>
                <Link
                    href="/user/packages"
                    class="text-sm font-bold text-tertiary-200 group-hover:text-primary transition-colors flex items-center gap-1"
                >
                    Lihat Semua Pengiriman <ArrowRight class="w-4 h-4" />
                </Link>
            </div>

            <div
                class="xl:col-span-9 bg-primary rounded-[2rem] p-8 md:p-10 text-white shadow-xl relative overflow-hidden flex flex-col justify-center"
            >
                <div
                    class="bg-white/10 px-3 py-1.5 rounded-full inline-block w-max text-[10px] font-extrabold tracking-widest uppercase mb-4 relative z-10 backdrop-blur-sm"
                >
                    Status Perangkat
                </div>
                <h1
                    class="text-3xl md:text-4xl font-extrabold mb-3 relative z-10"
                >
                    SmartBox {{ letterBoxId }}
                </h1>
                <p
                    class="text-gray-400 font-medium text-sm max-w-lg mb-8 relative z-10 leading-relaxed"
                >
                    Perangkat ini sedang aktif dan beroperasi dengan normal.
                    Seluruh sistem berjalan dengan baik dan pemantauan dilakukan
                    secara real-time.
                </p>
                <div class="flex items-center gap-6 relative z-10">
                    <div class="flex items-center gap-2">
                        <div
                            class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"
                        ></div>
                        <span
                            class="text-xs font-bold tracking-widest uppercase"
                            >Online</span
                        >
                    </div>
                    <div class="flex items-center gap-2">
                        <Lock class="w-3.5 h-3.5 text-blue-400" />
                        <span
                            class="text-xs font-bold tracking-widest uppercase text-blue-400"
                            >Secured</span
                        >
                    </div>
                </div>
                <Box
                    class="w-48 h-48 text-white/[0.03] absolute -right-8 -bottom-8 pointer-events-none stroke-[1]"
                />
            </div>

            <div class="xl:col-span-4 space-y-6">
                <div class="flex justify-between items-end mb-2">
                    <h2 class="text-xl font-extrabold text-primary">
                        My SmartBox
                    </h2>
                </div>

                <div
                    class="bg-white rounded-[2rem] p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-secondary-600"
                >
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-tertiary-800 flex items-center justify-center text-primary font-bold text-lg"
                            >
                                01
                            </div>
                            <div>
                                <h3
                                    class="font-extrabold text-primary text-lg leading-tight"
                                >
                                    Pintu Utama Pengguna
                                </h3>
                                <p class="text-xs font-bold text-secondary-200">
                                    Akses Pintu Belakang
                                </p>
                            </div>
                        </div>
                        <span
                            class="px-3 py-1 rounded-full text-[10px] font-extrabold tracking-widest uppercase border transition-colors duration-300"
                            :class="
                                isDoorOpen
                                    ? 'bg-emerald-50 text-emerald-600 border-emerald-100'
                                    : 'bg-red-50 text-red-600 border-red-100'
                            "
                        >
                            {{ isDoorOpen ? "Terbuka" : "Terkunci" }}
                        </span>
                    </div>

                    <div class="flex justify-center my-8">
                        <div
                            class="relative w-40 h-48 bg-tertiary-800 rounded-xl shadow-inner border-4 border-secondary-600"
                            style="perspective: 1000px"
                        >
                            <div
                                class="absolute inset-0 flex flex-col items-center justify-center bg-gray-800 rounded-lg overflow-hidden shadow-inner"
                            >
                                <Box
                                    class="w-12 h-12 text-emerald-400 mb-2 transition-all duration-700 ease-out"
                                    :class="
                                        isDoorOpen
                                            ? 'opacity-100 scale-100 translate-y-0'
                                            : 'opacity-0 scale-50 translate-y-4'
                                    "
                                />
                                <span
                                    class="text-[9px] text-gray-300 font-bold tracking-widest uppercase transition-all duration-700 delay-100"
                                    :class="
                                        isDoorOpen ? 'opacity-100' : 'opacity-0'
                                    "
                                >
                                    Paket Aman
                                </span>
                            </div>

                            <div
                                class="absolute inset-0 bg-primary rounded-lg flex flex-col items-center justify-center shadow-lg border border-primary-100/20 z-10"
                                style="
                                    transform-origin: left;
                                    transition: transform 0.8s
                                        cubic-bezier(0.4, 0, 0.2, 1);
                                    transform-style: preserve-3d;
                                "
                                :style="{
                                    transform: isDoorOpen
                                        ? 'rotateY(-105deg)'
                                        : 'rotateY(0deg)',
                                }"
                            >
                                <div
                                    class="w-2.5 h-2.5 rounded-full absolute top-4 shadow-[0_0_10px_rgba(0,0,0,0.5)] transition-colors duration-500"
                                    :class="
                                        isDoorOpen
                                            ? 'bg-emerald-400 shadow-emerald-400/50'
                                            : 'bg-red-500 shadow-red-500/50'
                                    "
                                ></div>

                                <span
                                    class="text-white/40 text-4xl font-extrabold tracking-wider"
                                    >01</span
                                >

                                <div
                                    class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-10 bg-white/20 rounded-full shadow-inner"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 w-full">
                        <button
                            @click="toggleDoor"
                            :disabled="isLoading"
                            class="w-full py-4 rounded-full font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-lg relative overflow-hidden"
                            :class="[
                                isLoading
                                    ? 'bg-gray-300 text-gray-500 cursor-not-allowed shadow-none'
                                    : doorStatus === 'waiting'
                                      ? 'bg-yellow-500 text-white animate-pulse shadow-yellow-500/20'
                                      : doorStatus === 'open'
                                        ? 'bg-red-500 hover:bg-red-600 text-white shadow-red-500/20'
                                        : 'bg-primary hover:bg-primary-100 text-white shadow-primary/20',
                            ]"
                        >
                            <Loader2
                                v-if="isLoading"
                                class="w-4 h-4 animate-spin relative z-10"
                            />
                            <template v-else>
                                <Unlock
                                    v-if="doorStatus !== 'closed'"
                                    class="w-4 h-4 relative z-10"
                                />
                                <Lock v-else class="w-4 h-4 relative z-10" />
                            </template>

                            <span class="relative z-10">
                                {{
                                    isLoading
                                        ? "Menyambungkan..."
                                        : doorStatus === "waiting"
                                          ? "Tarik Pintu Sekarang"
                                          : doorStatus === "open"
                                            ? "Tutup Pintu Paket"
                                            : "Buka Pintu Paket"
                                }}
                            </span>
                        </button>

                        <transition
                            enter-active-class="transition duration-300 ease-out"
                            enter-from-class="transform -translate-y-2 opacity-0"
                            enter-to-class="transform translate-y-0 opacity-100"
                            leave-active-class="transition duration-200 ease-in"
                            leave-from-class="transform translate-y-0 opacity-100"
                            leave-to-class="transform -translate-y-2 opacity-0"
                        >
                            <div
                                v-if="doorStatus === 'waiting'"
                                class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-2xl text-xs flex items-center gap-3"
                            >
                                <div
                                    class="bg-yellow-500 p-1.5 rounded-full text-white"
                                >
                                    <Unlock class="w-3 h-3" />
                                </div>
                                <p>
                                    <b>Solenoid Terlepas!</b> Pintu sudah tidak
                                    terkunci, silakan tarik pintu untuk
                                    mengambil paket.
                                </p>
                            </div>
                        </transition>
                    </div>

                    <!-- <button
                        @click="toggleDoor"
                        :disabled="isLoading"
                        class="w-full py-4 rounded-full font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-lg relative overflow-hidden"
                        :class="[
                            isLoading
                                ? 'bg-gray-300 text-gray-500 cursor-not-allowed shadow-none'
                                : isDoorOpen
                                  ? 'bg-red-500 hover:bg-red-600 text-white shadow-red-500/20'
                                  : 'bg-primary hover:bg-primary-100 text-white shadow-primary/20',
                        ]"
                    >
                        <Loader2
                            v-if="isLoading"
                            class="w-4 h-4 animate-spin relative z-10"
                        />
                        <Lock
                            v-else-if="isDoorOpen"
                            class="w-4 h-4 relative z-10"
                        />
                        <Unlock v-else class="w-4 h-4 relative z-10" />

                        <span class="relative z-10">
                            {{
                                isLoading
                                    ? "Menyambungkan ke SmartBox..."
                                    : isDoorOpen
                                      ? "Tutup Pintu Paket"
                                      : "Buka Pintu Paket"
                            }}
                        </span>
                    </button> -->
                </div>
            </div>

            <div class="xl:col-span-8 space-y-6">
                <div class="flex justify-between items-end mb-2">
                    <h2 class="text-xl font-extrabold text-primary">
                        Pengiriman Saya
                    </h2>
                    <div class="flex gap-2">
                        <button
                            class="w-8 h-8 rounded-full bg-tertiary-800 flex items-center justify-center text-tertiary-200 hover:text-primary transition-colors"
                        >
                            <Filter class="w-3.5 h-3.5" />
                        </button>
                    </div>
                </div>

                <div
                    class="bg-white rounded-[2rem] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-secondary-600"
                >
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr
                                    class="text-[15px] font-extrabold text-gray-700 uppercase tracking-widest border-b border-secondary-600"
                                >
                                    <th class="pb-4 pr-6">Nomer Resi</th>
                                    <th class="pb-4 pr-6">Pembayaran</th>
                                    <th class="pb-4 pr-6">Nominal</th>
                                    <th class="pb-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-600/50">
                                <tr
                                    v-for="pkg in recentDeliveries"
                                    :key="pkg.id"
                                    class="group"
                                >
                                    <td class="py-5 pr-6">
                                        <p
                                            class="font-extrabold text-primary text-sm mb-0.5"
                                        >
                                            {{ pkg.tracking_number }}
                                        </p>
                                        <p
                                            class="text-[10px] font-bold text-gray-400"
                                        >
                                            Paket
                                            {{ formatDateTime(pkg.created_at) }}
                                        </p>
                                    </td>

                                    <td class="py-5 pr-6">
                                        <span
                                            class="px-3 py-1 rounded-lg text-[10px] font-extrabold tracking-widest uppercase"
                                            :class="
                                                pkg.payment_type === 'cod'
                                                    ? 'bg-blue-50 text-blue-600'
                                                    : 'bg-green-100 text-green-500'
                                            "
                                        >
                                            {{
                                                pkg.payment_type == "cod"
                                                    ? "COD"
                                                    : "Non - COD"
                                            }}
                                        </span>
                                    </td>
                                    <td class="py-5 pr-6">
                                        <div
                                            class="flex items-center gap-2 text-sm font-bold text-secondary-200"
                                        >
                                            <Banknote class="w-4 h-4" />
                                            <span
                                                v-if="pkg.payment_type == 'cod'"
                                                >{{
                                                    formatRupiah(pkg.amount)
                                                }}</span
                                            >
                                            <span v-else> Sudah Lunas </span>
                                        </div>
                                    </td>
                                    <td class="py-5">
                                        <div
                                            class="flex items-center gap-2 text-sm font-bold"
                                            :class="
                                                pkg.package_status === 'arrived'
                                                    ? 'text-emerald-600'
                                                    : 'text-amber-600'
                                            "
                                        >
                                            <div
                                                class="w-1.5 h-1.5 rounded-full"
                                                :class="
                                                    pkg.package_status ===
                                                    'arrived'
                                                        ? 'bg-emerald-500'
                                                        : 'bg-amber-500'
                                                "
                                            ></div>
                                            {{
                                                pkg.package_status === "arrived"
                                                    ? "Paket Sudah Sampai"
                                                    : "Paket Sedang Dalam Perjalanan"
                                            }}
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="recentDeliveries.length === 0">
                                    <td
                                        colspan="4"
                                        class="py-10 text-center text-gray-400 text-sm font-medium"
                                    >
                                        Tidak ada riwayat pengiriman.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="mt-6 text-center border-t border-secondary-600 pt-6"
                    >
                        <Link
                            href="/user/packages"
                            class="text-[13px] font-extrabold text-gray-600 tracking-widest uppercase hover:text-primary transition-colors"
                        >
                            Tampilkan Semua Pengiriman
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import axios from "axios";
import Swal from "sweetalert2";
import UserLayout from "@/Layouts/UserLayout.vue";
import { formatRupiah, formatDateTime } from "@/helpers/Formatter";
import {
    Package as PackageIcon,
    ArrowRight,
    Lock,
    Box,
    Clock,
    Unlock,
    Filter,
    Truck,
    Loader2,
    Banknote,
} from "@lucide/vue";

// --- PROPS DARI CONTROLLER ---
const props = defineProps({
    activePackagesCount: {
        type: Number,
        default: 0,
    },
    recentDeliveries: {
        type: Array,
        default: () => [],
    },
    letterBoxId: {
        type: String,
        default: "Node-01",
    },
    userId: {
        type: Number,
        required: true,
    },
});

// --- STATE ANTI SPAM BUKA PINTU ---
const isUnlocking = ref(false);
// --- STATE PINTU ---
const isLoading = ref(false);
const doorStatus = ref("closed"); // Default status

// Computed untuk memudahkan pengecekan di template
const isDoorOpen = computed(() => doorStatus.value === "open");
const isWaiting = computed(() => doorStatus.value === "waiting");

// --- FUNGSI TOGGLE PINTU ---
// --- FUNGSI TOGGLE PINTU ---
const toggleDoor = async () => {
    // 1. JIKA PINTU SEDANG TERBUKA FISIK (OPEN)
    if (doorStatus.value === "open") {
        Swal.fire({
            title: "Pintu Masih Terbuka!",
            text: "Sistem tidak bisa mengunci karena pintu secara fisik masih terbuka. Silakan dorong/rapatkan pintu SmartBox Anda, sistem akan mengunci secara otomatis dalam 3 detik.",
            icon: "info",
            confirmButtonColor: "#111840",
            confirmButtonText: "Mengerti",
        });
        return; // Hentikan eksekusi, jangan kirim perintah ke server
    }

    // 2. JIKA STATUS WAITING (Batal Buka) ATAU CLOSED (Mau Buka)
    const isWantToOpen = doorStatus.value === "closed";
    const actionStr = isWantToOpen ? "membuka" : "mengunci kembali";

    const result = await Swal.fire({
        title: `${isWantToOpen ? "Buka" : "Kunci"} Pintu?`,
        text: `Apakah Anda yakin ingin ${actionStr} pintu belakang SmartBox?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#111840",
        cancelButtonColor: "#d33",
        confirmButtonText: `Ya, ${actionStr}!`,
        cancelButtonText: "Batal",
    });

    if (!result.isConfirmed) return;

    isLoading.value = true;
    try {
        await axios.post("/user/box/unlock-door", {
            action: isWantToOpen ? "open" : "close",
        });
    } catch (error) {
        Swal.fire(
            "Gagal!",
            error.response?.data?.message || "Koneksi gagal.",
            "error",
        );
        isLoading.value = false;
    }
};

// --- LIFECYCLE (WEBSOCKETS LISTENER) ---
// Tambahkan satu variabel pendukung DI LUAR onMounted untuk mengunci popup alert
let lastAlertStatus = null;

onMounted(() => {
    if (window.Echo) {
        console.log(`[WEBSOCKET] Mencoba mendengarkan private channel: user.${props.userId}`);

        // PERBAIKAN: NAMA EVENT HARUS SAMA PERSIS DENGAN broadcastAs() DI LARAVEL
        window.Echo.private(`user.${props.userId}`).listen(
            ".RearDoorStatusUpdated", 
            (e) => {
                console.log("[WEBSOCKET] Event diterima! Full Payload:", e);

                const receivedStatus = e.data?.status || e.status; 
                
                console.log(`[WEBSOCKET] Status Pintu Terdeteksi: "${receivedStatus}"`);

                if (!receivedStatus) {
                    console.error("[WEBSOCKET] Error: Properti status tidak ditemukan!");
                    return;
                }

                // Mengubah status pintu untuk menjalankan animasi di UI
                doorStatus.value = receivedStatus; 
                isLoading.value = false;

                // OPTIMASI ANTI-SPAM SWEETALERT:
                if (receivedStatus === "waiting") {
                    if (lastAlertStatus !== "waiting") {
                        lastAlertStatus = "waiting"; 

                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "info",
                            title: "Kunci terbuka! Silakan tarik pintu.",
                            showConfirmButton: false,
                            timer: 4000,
                            didClose: () => {
                                if (doorStatus.value !== "waiting") {
                                    lastAlertStatus = null;
                                }
                            }
                        });
                    }
                } else {
                    lastAlertStatus = receivedStatus;
                }
            }
        );
    } else {
        console.error("[WEBSOCKET] Laravel Echo tidak terdeteksi di browser!");
    }
});
</script>
