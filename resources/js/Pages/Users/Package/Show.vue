<template>
    <UserLayout>
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8 w-full"
        >
            <div class="flex-1 min-w-0">
                <div
                    class="flex items-center gap-2 text-sm font-bold text-gray-500 mb-2 flex-wrap"
                >
                    <Link
                        href="/user/packages"
                        class="hover:text-primary transition-colors whitespace-nowrap"
                    >
                        Daftar Paket
                    </Link>
                    <ChevronRight class="w-4 h-4 text-gray-400 shrink-0" />
                    <span
                        class="text-primary truncate max-w-[200px] sm:max-w-none"
                    >
                        {{ package.tracking_number }}
                    </span>
                </div>
                <h1
                    class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary tracking-tight truncate"
                >
                    Detail Paket
                </h1>
            </div>

            <div
                class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full md:w-auto shrink-0"
            >
                <div class="w-full sm:w-auto">
                    <button
                        @click="showGuide"
                        class="w-full bg-secondary-100 hover:bg-secondary-200 border border-blue-500/30 text-white py-3 px-5 rounded-2xl font-bold text-sm transition-all flex justify-center items-center gap-2 relative z-10"
                    >
                        <Info class="w-5 h-5 shrink-0" />
                        <span class="whitespace-nowrap"
                            >Panduan Memasukkan Uang</span
                        >
                    </button>
                </div>

                <div class="w-full sm:w-auto">
                    <button
                        @click="openRotarySlot"
                        :disabled="isOpening || sisaTagihan <= 0"
                        class="w-full bg-primary hover:bg-secondary-100 text-white p-3.5 sm:p-4 rounded-2xl font-extrabold text-sm transition-all flex justify-center items-center gap-2 shadow relative z-10 disabled:opacity-50"
                    >
                        <Loader2
                            v-if="isOpening"
                            class="w-5 h-5 animate-spin shrink-0"
                        />
                        <Unlock v-else class="w-5 h-5 shrink-0" />
                        <span class="whitespace-nowrap">
                            Buka Slot Uang
                            {{
                                package.slot?.slot_number
                                    ? `0${package.slot.slot_number}`
                                    : ""
                            }}
                        </span>
                    </button>
                </div>

                <div
                    class="w-full sm:w-auto bg-red-100 border border-red-200 px-4 py-3 rounded-2xl flex items-center justify-center sm:justify-start gap-3 shadow-sm shrink-0"
                >
                    <Hourglass
                        class="w-5 h-5 text-red-500 animate-pulse shrink-0"
                    />
                    <div class="text-left">
                        <p
                            class="text-[9px] font-extrabold text-red-400 tracking-widest uppercase mb-0.5 leading-none"
                        >
                            Sesi Aktif
                        </p>
                        <p
                            class="text-xs font-bold text-red-600 whitespace-nowrap leading-tight"
                        >
                            Berakhir dalam {{ remainingSeconds }} detik
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-4 space-y-6">
                <div
                    class="bg-white rounded-[2rem] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-secondary-600 relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-[#eef2f6] rounded-bl-full -z-10"
                    ></div>

                    <p
                        class="text-md font-extrabold text-gray-500 tracking-widest uppercase mb-1"
                    >
                        Identifikasi Pelacakan
                    </p>
                    <h2 class="text-2xl font-extrabold text-primary mb-8">
                        No.Resi - {{ package.tracking_number }}
                    </h2>

                    <div class="space-y-6 mb-8">
                        <div class="flex gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-[#f4f7fb] flex items-center justify-center text-primary shrink-0"
                            >
                                <Truck class="w-5 h-5" />
                            </div>
                            <div>
                                <p
                                    class="text-xs font-bold text-gray-400 uppercase tracking-widest"
                                >
                                    Status Pengiriman
                                </p>
                                <p class="font-bold text-primary text-sm">
                                    {{
                                        package.package_status === "arrived"
                                            ? "Paket Sudah di terima"
                                            : "Paket Sedang Dalam Perjalanan"
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-[#f4f7fb] flex items-center justify-center text-primary shrink-0"
                            >
                                <Wallet class="w-5 h-5" />
                            </div>
                            <div>
                                <p
                                    class="text-xs font-bold text-gray-400 uppercase tracking-widest"
                                >
                                    Metode Pembayaran
                                </p>
                                <p class="font-bold text-blue-600 text-sm">
                                    {{
                                        package.payment_type === "cod"
                                            ? "Bayar di Tempat (COD)"
                                            : "Prabayar (Prepaid)"
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="w-full p-4 rounded-2xl flex flex-col items-center justify-center gap-2 border-2 transition-all duration-300"
                        :class="[
                            package.payment_status === 'deposited'
                                ? 'bg-green-50 border-green-200'
                                : 'bg-amber-50 border-amber-200',
                        ]"
                    >
                        <template v-if="package.payment_status === 'deposited'">
                            <div class="text-green-600">
                                <Lock class="w-12 h-12 mx-auto mb-2" />
                                <p class="font-bold text-center">
                                    Pembayaran Lunas!
                                </p>
                            </div>
                        </template>

                        <template v-else>
                            <div class="text-amber-700 text-center">
                                <Info class="w-10 h-10 mx-auto mb-2" />
                                <p class="font-bold text-sm">
                                    Peringatan Pembayaran
                                </p>
                                <p class="text-xs px-2 mt-1">
                                    Mohon masukkan uang dengan nominal
                                    <strong>PAS</strong>. Kelebihan uang akan
                                    dianggap sebagai
                                    <strong>TIPS untuk kurir</strong>.
                                </p>
                            </div>
                        </template>
                    </div>
                </div>

                <div
                    v-if="package.payment_type === 'cod'"
                    class="bg-[#111840] rounded-[2rem] hidden lg:block p-8 text-white shadow-xl relative overflow-hidden"
                >
                    <div
                        class="flex justify-between items-start mb-6 relative z-10"
                    >
                        <p
                            class="text-[10px] font-extrabold text-gray-400 tracking-widest uppercase leading-tight"
                        >
                            Antarmuka<br />Perangkat
                        </p>
                        <span
                            class="bg-red-500/20 text-red-400 border border-red-500/30 px-3 py-1 rounded-md text-[9px] font-extrabold tracking-widest uppercase flex items-center gap-1"
                        >
                            <Lock class="w-3 h-3" /> Terkunci
                        </span>
                    </div>

                    <h3 class="text-2xl font-extrabold mb-3 relative z-10">
                        Slot {{ package.slot?.slot_number || "?" }} Tertutup
                    </h3>
                    <p
                        class="text-gray-400 text-xs leading-relaxed mb-8 relative z-10"
                    >
                        Slot penerimaan uang saat ini dalam kondisi terkunci.
                        Silakan tekan tombol di bawah untuk membuka akses fisik
                        dan mulai memasukkan uang.
                    </p>

                    <button
                        @click="openRotarySlot"
                        :disabled="isOpening || sisaTagihan <= 0"
                        class="w-full bg-white hover:bg-gray-100 text-[#111840] py-4 rounded-full font-extrabold text-sm transition-all flex justify-center items-center gap-2 shadow-lg mb-6 relative z-10 disabled:opacity-50"
                    >
                        <Loader2
                            v-if="isOpening"
                            class="w-5 h-5 animate-spin"
                        />
                        <Unlock v-else class="w-5 h-5" />
                        Buka Slot Uang
                        {{
                            package.slot?.slot_number
                                ? `0${package.slot.slot_number}`
                                : ""
                        }}
                    </button>

                    <div
                        class="flex items-start gap-3 bg-white/5 p-4 rounded-xl border border-white/10 relative z-10"
                    >
                        <Info class="w-4 h-4 text-gray-400 shrink-0 mt-0.5" />
                        <p class="text-[12px] text-gray-400 leading-relaxed">
                            Menunggu perintah pembukaan slot dari operator.
                        </p>
                    </div>

                    <Lock
                        class="w-40 h-40 text-white/[0.02] absolute -bottom-8 -right-8 pointer-events-none"
                    />
                </div>
            </div>

            <div class="lg:col-span-8 space-y-6">
                <div
                    class="bg-white rounded-[2rem] p-8 md:p-10 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-secondary-600 flex flex-col md:flex-row items-center gap-10"
                >
                    <div class="flex-1 w-full">
                        <div class="flex items-center gap-3 mb-6">
                            <BarChart class="w-6 h-6 text-primary" />
                            <h2 class="text-xl font-extrabold text-primary">
                                Status Pembayaran Langsung
                            </h2>
                        </div>

                        <p
                            class="text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-1"
                        >
                            Target Alokasi (COD)
                        </p>
                        <h3
                            class="text-4xl md:text-5xl font-extrabold text-primary mb-8 tracking-tight"
                        >
                            Rp {{ formatRupiah(package.amount) }}
                            <!-- Rp {{ formatRupiah(localDepositedAmount) }} -->
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div
                                class="bg-[#eef2f6] rounded-2xl p-5 border border-blue-100"
                            >
                                <p
                                    class="text-[10px] font-extrabold text-blue-400 tracking-widest uppercase mb-1"
                                >
                                    Tersetor
                                </p>
                                <p
                                    class="text-xl font-extrabold text-[#3c5f83]"
                                >
                                    Rp 
                                    {{ formatRupiah(localDepositedAmount) }}
                                </p>
                            </div>
                            <div
                                class="bg-red-50 rounded-2xl p-5 border border-red-100"
                            >
                                <p
                                    class="text-[10px] font-extrabold text-red-400 tracking-widest uppercase mb-1"
                                >
                                    Sisa Tagihan
                                </p>
                                <p class="text-xl font-extrabold text-red-600">
                                    <!-- Rp {{ formatRupiah(sisaTagihan) }} -->
                                    Rp {{ formatRupiah(sisaTagihan) }}
                                </p>
                            </div>
                        </div>
                        <div
                            v-if="localDepositedAmount > package.amount"
                            class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-sm font-bold flex items-center gap-2"
                        >
                            <Info class="w-5 h-5" />
                            <span>
                                Kelebihan pembayaran terdeteksi sebesar:
                                <strong
                                    >Rp
                                    {{
                                        formatRupiah(
                                            localDepositedAmount -
                                                package.amount,
                                        )
                                    }}</strong
                                >
                            </span>
                        </div>
                    </div>

                    <div
                        class="w-full md:w-auto flex flex-col items-center border-t md:border-t-0 md:border-l border-gray-100 pt-8 md:pt-0 md:pl-10 shrink-0"
                    >
                        <div
                            class="relative w-40 h-40 flex items-center justify-center mb-6"
                        >
                            <svg class="transform -rotate-90 w-40 h-40">
                                <circle
                                    cx="80"
                                    cy="80"
                                    r="70"
                                    stroke="currentColor"
                                    stroke-width="12"
                                    fill="transparent"
                                    class="text-gray-100"
                                />
                                <circle
                                    cx="80"
                                    cy="80"
                                    r="70"
                                    stroke="currentColor"
                                    stroke-width="12"
                                    fill="transparent"
                                    :stroke-dasharray="circumference"
                                    :stroke-dashoffset="
                                        circumference -
                                        (percent / 100) * circumference
                                    "
                                    class="text-blue-500 transition-all duration-1000 ease-out"
                                />
                            </svg>
                            <div class="absolute flex flex-col items-center">
                                <span
                                    class="text-4xl font-extrabold text-primary"
                                    >{{ percent }}%</span
                                >
                                <span
                                    class="text-[9px] font-extrabold text-gray-400 tracking-widest uppercase"
                                    >Selesai</span
                                >
                            </div>
                        </div>
                        <p
                            class="text-xs text-center text-gray-500 font-medium max-w-[200px]"
                            v-if="sisaTagihan > 0"
                        >
                            Silakan masukkan sisa pembayaran sebesar
                            <strong class="text-primary"
                                >Rp {{ formatRupiah(sisaTagihan) }}</strong
                            >.
                        </p>
                        <p
                            class="text-xs text-center text-emerald-600 font-bold max-w-[200px]"
                            v-else
                        >
                            Pembayaran telah lunas.
                        </p>
                    </div>
                </div>

                <div
                    v-if="package.payment_type === 'cod'"
                    class="bg-white rounded-[2rem] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-secondary-600"
                >
                    <div
                        class="flex justify-between items-end mb-8 border-b border-gray-100 pb-4"
                    >
                        <div class="flex items-center gap-3">
                            <History class="w-5 h-5 text-primary" />
                            <h2 class="text-lg font-extrabold text-primary">
                                Riwayat Setoran
                            </h2>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div
                            v-for="log in package.deposit_logs"
                            :key="log.id"
                            class="flex items-center justify-between bg-[#f8faff] p-5 rounded-2xl border border-gray-100"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 shrink-0"
                                >
                                    <Plus class="w-4 h-4" />
                                </div>
                                <div>
                                    <h4
                                        class="font-bold text-primary text-sm mb-0.5"
                                    >
                                        Setoran Tunai Dikonfirmasi
                                    </h4>
                                    <p class="text-[10px] text-gray-400">
                                        Slot 0{{ package.slot?.slot_number }} •
                                        ID Transaksi #{{ log.id }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <h4
                                    class="font-extrabold text-blue-600 text-base mb-0.5"
                                >
                                    + Rp {{ formatRupiah(log.nominal) }}
                                </h4>
                                <p class="text-[10px] text-gray-400">
                                    {{ formatTime(log.created_at) }} WIB
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="package.deposit_logs.length === 0"
                            class="text-center py-8 text-gray-400 text-sm font-medium"
                        >
                            Belum ada setoran uang terdeteksi.
                        </div>
                    </div>

                    <!-- <button
                        class="w-full border-2 border-dashed border-secondary-600 hover:border-primary hover:bg-[#f8faff] text-secondary-200 hover:text-primary py-4 rounded-2xl font-bold text-sm transition-all flex justify-center items-center gap-2"
                    >
                        <Printer class="w-4 h-4" /> Cetak Resi Sementara
                    </button> -->
                </div>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";
import axios from "axios";
import Swal from "sweetalert2";
import UserLayout from "@/Layouts/UserLayout.vue";
import {
    ChevronRight,
    Truck,
    Wallet,
    Box,
    Lock,
    Unlock,
    Info,
    BarChart,
    History,
    Plus,
    Printer,
    Hourglass,
    Loader2,
    Package as PackageIcon,
} from "@lucide/vue";

const props = defineProps({
    package: { type: Object, required: true },
});

// --- State ---
const isOpening = ref(false);
const localDeposits = ref(props.package.deposit_logs || []);
const localDepositedAmount = ref(parseFloat(props.package.total_received) || 0);

// --- State Sesi Aktif ---
const remainingSeconds = ref(0);
const isSessionActive = computed(() => remainingSeconds.value > 0);
let sessionInterval = null;
let isWarningModalOpen = false; // Mencegah modal muncul berulang kali

// --- Audio Controller ---
const audio = new Audio("/sounds/cash.mp3");

const playCashSound = () => {
    audio.currentTime = 0;
    audio.play().catch((e) => console.warn("Audio play blocked:", e));
};

// --- Computed Properties ---
const sisaTagihan = computed(() => {
    const sisa = props.package.amount - localDepositedAmount.value;
    return sisa > 0 ? sisa : 0;
});

const percent = computed(() => {
    if (props.package.amount <= 0) return 100;
    const p = Math.round(
        (localDepositedAmount.value / props.package.amount) * 100,
    );
    return Math.min(p, 100);
});

const circumference = 2 * Math.PI * 70;

// --- Utilities ---
const formatRupiah = (angka) =>
    new Intl.NumberFormat("id-ID").format(angka || 0);
const formatTime = (dateString) =>
    new Date(dateString).toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
    });

const formatCountdown = computed(() => {
    const m = Math.floor(remainingSeconds.value / 60)
        .toString()
        .padStart(2, "0");
    const s = (remainingSeconds.value % 60).toString().padStart(2, "0");
    return `${m}:${s}`;
});

// Tambahkan fungsi ini di script setup
const showGuide = () => {
    Swal.fire({
        title: "Perhatian!",
        text: "Pastikan uang lurus dan tidak lecek agar sensor dapat mendeteksi nominal dengan tepat.",
        imageUrl: "/images/PANDUAN.png", // Sesuaikan dengan path/URL gambar kamu
        imageWidth: 400,
        imageAlt: "Panduan Memasukkan Uang",
        confirmButtonText: "Saya Mengerti",
        confirmButtonColor: "#10b981",
        customClass: {
            image: "rounded-xl shadow-sm",
        },
    });
};

// ==============================================================
// MANAJEMEN SESI (LOCAL TIMER - NO POLLING!)
// ==============================================================

let targetExpireTime = null;

// 1. FUNGSI PERPANJANG SESI
const extendSession = async () => {
    try {
        // Ambil respon dari backend
        const { data } = await axios.post(
            `/user/packages/${props.package.id}/extend-session`,
        );

        // Gunakan detik dari backend, kalikan 1000 untuk jadi milidetik
        isWarningModalOpen = false;
        targetExpireTime = Date.now() + data.remaining_seconds * 1000;
        startLocalTimer();
        Swal.fire(
            "Sesi Diperpanjang",
            "Silakan lanjutkan memasukkan uang.",
            "success",
        );
    } catch (error) {
        console.error(error);
    }
};

// 2. FUNGSI AKHIRI SESI (INI YANG HILANG/ERROR TADI)
// Fungsi ini HARUS diletakkan sebelum fungsi startLocalTimer dan showExpiryWarning
const terminateSession = async (showNotification = true) => {
    try {
        await axios.post(
            `/user/packages/${props.package.id}/terminate-session`,
        );
        remainingSeconds.value = 0;
        targetExpireTime = null;

        isWarningModalOpen = false;

        if (sessionInterval) {
            clearInterval(sessionInterval);
            sessionInterval = null;
        }

        if (showNotification) {
            Swal.fire(
                "Sesi Berakhir",
                "Pintu kotak uang telah dikunci kembali untuk keamanan.",
                "info",
            );
        }
    } catch (error) {
        console.error("Gagal mengakhiri sesi:", error);
    }
};

// 3. FUNGSI MENAMPILKAN MODAL PERINGATAN
const showExpiryWarning = () => {
    if (isWarningModalOpen) return;
    isWarningModalOpen = true;
    let timerInterval;

    Swal.fire({
        title: "Sesi Hampir Habis!",
        html: 'Sesi aktif akan berakhir dalam <b class="text-red-600 text-xl"></b> detik.<br><br>Apakah Anda masih memasukkan uang?',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Tunggu!",
        cancelButtonText: "Selesai / Akhiri",
        allowOutsideClick: false,
        didOpen: () => {
            const b = Swal.getHtmlContainer().querySelector("b");
            b.textContent = remainingSeconds.value;

            timerInterval = setInterval(() => {
                b.textContent = remainingSeconds.value;
            }, 1000);
        },
        willClose: () => {
            clearInterval(timerInterval);
            isWarningModalOpen = false;
        },
    }).then((result) => {
        // DI SINI LETAK ERROR TADI JIKA terminateSession TIDAK DITEMUKAN
        if (result.isConfirmed) {
            extendSession();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            terminateSession(true);
        }
    });
};

// 4. FUNGSI TIMER LOKAL (MATEMATIKA)
const startLocalTimer = () => {
    if (sessionInterval) clearInterval(sessionInterval);

    sessionInterval = setInterval(() => {
        if (!targetExpireTime) return;

        const now = Date.now();
        const diffInSeconds = Math.max(
            0,
            Math.floor((targetExpireTime - now) / 1000),
        );

        remainingSeconds.value = diffInSeconds;

        // Ganti bagian if warning menjadi:
        if (
            remainingSeconds.value <= 60 &&
            remainingSeconds.value > 0 &&
            !Swal.isVisible()
        ) {
            showExpiryWarning();
        }

        if (remainingSeconds.value === 0) {
            clearInterval(sessionInterval);
            sessionInterval = null;
            targetExpireTime = null;

            // Tutup paksa SweetAlert jika masih terbuka
            if (isWarningModalOpen) {
                Swal.close();
                isWarningModalOpen = false;
            }

            // Panggil API untuk force close tanpa modal tambahan
            terminateSession(false);
        }
    }, 1000);
};

// 5. FUNGSI AMBIL DATA SESI DARI BACKEND (SAAT HALAMAN DIMUAT)
const fetchSessionData = async () => {
    try {
        const { data } = await axios.get(
            `/user/packages/${props.package.id}/session-status`,
        );

        if (data.remaining_seconds > 0) {
            targetExpireTime = Date.now() + data.remaining_seconds * 1000;
            startLocalTimer();
        } else {
            remainingSeconds.value = 0;
            targetExpireTime = null;
        }
    } catch (error) {
        console.error("Gagal mengambil data sesi", error);
    }
};

// 4. Update fungsi openRotarySlot
const openRotarySlot = async () => {
    const confirmGuide = await Swal.fire({
        title: "Sebelum Membuka Kotak...",
        text: "Mohon pastikan posisi uang dan nominal sesuai panduan agar sensor tidak error.",
        imageUrl: "/images/PANDUAN.png", // Sesuaikan path gambar
        imageWidth: 400,
        showCancelButton: true,
        confirmButtonText: "Buka Sekarang",
        cancelButtonText: "Batal",
        confirmButtonColor: "#111840",
    });

    if (!confirmGuide.isConfirmed) return; // Batalkan jika user klik batal

    isOpening.value = true;
    try {
        audio
            .play()
            .then(() => {
                audio.pause();
                audio.currentTime = 0;
            })
            .catch(() => {});

        // Ambil respon dari backend
        const { data } = await axios.post(
            `/user/packages/${props.package.id}/open-slot`,
        );

        // Gunakan detik dari backend secara dinamis!
        targetExpireTime = Date.now() + data.remaining_seconds * 1000;
        startLocalTimer();

        Swal.fire({
            title: "Kotak Terbuka!",
            text: "Mesin sedang bergerak. Silakan masukkan uang lembar per lembar.",
            icon: "info",
            backdrop: `rgba(17, 24, 64, 0.8)`,
        });
        isWarningModalOpen = false;
    } catch (error) {
        Swal.fire("Gagal!", "Koneksi ke IoT gagal.", "error");
    } finally {
        isOpening.value = false;
    }
};

// ==============================================================
// LIFECYCLE VUE
// ==============================================================
onMounted(() => {
    fetchSessionData();

    if (window.Echo) {
        // -------------------------------------------------------------
        // 1. MENDENGAR JIKA UANG MASUK & MEMINTA KONFIRMASI (NEW)
        // -------------------------------------------------------------
        window.Echo.private(`package.${props.package.id}`)
            .listen(".money-pending-confirmation", (e) => {
                console.log("🔥 EVENT KONFIRMASI MASUK DARI LARAVEL:", e);
                Swal.fire({
                    title: "Konfirmasi Nominal",
                    html: `Sistem mendeteksi uang sebesar <br><strong class="text-3xl text-emerald-600 mt-2 block">Rp ${formatRupiah(e.nominal)}</strong><br>Silakan dorong uang ke dalam kotak lalu klik <b>BENAR</b>.`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#10b981",
                    cancelButtonColor: "#ef4444",
                    confirmButtonText: "Benar, Uang Masuk!",
                    cancelButtonText: "Salah / Tarik Uang",
                    allowOutsideClick: false,
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        // User klik BENAR, panggil API Laravel untuk input database
                        try {
                            await axios.post(
                                `/user/packages/${props.package.id}/confirm-money`,
                                {
                                    is_valid: true,
                                    nominal: e.nominal,
                                    color_signature: e.color_signature,
                                },
                            );
                        } catch (error) {
                            Swal.fire(
                                "Gagal",
                                "Terjadi kesalahan server",
                                "error",
                            );
                        }
                    } else {
                        // User klik SALAH/BATAL
                        try {
                            await axios.post(
                                `/user/packages/${props.package.id}/confirm-money`,
                                {
                                    is_valid: false,
                                },
                            );
                        } catch (error) {}
                    }
                });
            })

            // -------------------------------------------------------------
            // 2. MENDENGAR JIKA SENSOR GAGAL MEMBACA UANG (NEW)
            // -------------------------------------------------------------
            .listen(".money-unrecognized", () => {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "error",
                    title: "Uang tidak dikenali! Tarik dan masukkan ulang dengan lurus.",
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                });
            })

            // -------------------------------------------------------------
            // 3. MENDENGAR JIKA UANG SUDAH FIX DISIMPAN KE DB (LAMA)
            // -------------------------------------------------------------
            .listen(".money-deposited", (e) => {
                localDeposits.value.unshift(e.deposit);
                localDepositedAmount.value += parseFloat(e.deposit.nominal);
                playCashSound();

                if (e.package.payment_status === "deposited") {
                    let alertText =
                        "Jumlah uang sudah mencukupi. Kotak akan segera terkunci otomatis.";
                    if (e.package.overpayment > 0) {
                        alertText += `<br><br><strong style="color:red">Terdapat kelebihan: Rp ${formatRupiah(e.package.overpayment)}</strong>`;
                    }
                    Swal.fire({
                        title: "Pembayaran LUNAS!",
                        text: alertText,
                        icon: "success",
                        confirmButtonColor: "#10b981",
                        confirmButtonText: "Selesai",
                        allowOutsideClick: false,
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: `Salod Rp ${formatRupiah(e.deposit.nominal)} berhasil ditambahkan!`,
                        showConfirmButton: false,
                        timer: 3000,
                    });
                }
            });
    }
});

onUnmounted(() => {
    // Bersihkan semua hal yang berjalan di background untuk mencegah Memory Leak
    if (sessionInterval) clearInterval(sessionInterval);
    if (window.Echo) window.Echo.leave(`package.${props.package.id}`);
});
</script>
