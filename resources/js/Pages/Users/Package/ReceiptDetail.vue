<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
import {
    ChevronRight,
    CheckCircle2,
    Info,
    Truck,
    Download,
    Share2,
    Banknote,
} from "@lucide/vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    packageInfo: Object,
    receiptImage: Object,
});

// Fungsi dummy untuk download gambar
const handleDownload = () => {
    // Logika download file
    const link = document.createElement("a");
    link.href = props.receiptImage.url;
    link.download = `Bukti_${props.packageInfo.transaction_id}.jpg`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

// Fungsi dummy untuk share
const handleShare = () => {
    if (navigator.share) {
        navigator
            .share({
                title: "Bukti Penerimaan Paket",
                text: `Paket dengan ID ${props.packageInfo.transaction_id} telah diterima di SmartBox.`,
                url: window.location.href,
            })
            .catch(console.error);
    } else {
        alert("Fitur share tidak didukung di browser ini.");
    }
};
</script>

<template>
    <UserLayout title="Detail Bukti Penerimaan">
        <div class="px-6 bg-[#f7f9ff] min-h-screen">
            <!-- Menggunakan warna background yang sangat terang -->
            <div class="max-w-7xl mx-auto">
                <!-- Breadcrumbs -->
                <div
                    class="flex items-center gap-2 text-sm font-medium mb-6 text-secondary-200"
                >
                    <a
                        href="/user/packages"
                        class="hover:text-primary transition"
                        >Daftar Paket</a
                    >
                    <ChevronRight class="w-4 h-4" />
                    <span class="text-primary font-bold"
                        >Detail Bukti #{{ packageInfo.tracking_number }}</span
                    >
                </div>

                <!-- Header & Status Badge -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4"
                >
                    <div>
                        <h1 class="text-3xl font-bold text-primary mb-1">
                            Detail Bukti Penerimaan
                        </h1>
                        <p class="text-sm text-secondary-200">
                            Laporan bukti penerimaan paket
                        </p>
                    </div>

                    <div
                        class="flex items-center gap-2 bg-emerald-100 text-emerald-800 px-4 py-2.5 rounded-full font-bold text-sm tracking-wide"
                    >
                        <CheckCircle2 class="w-5 h-5" />
                        PAKET SUDAH DITERIMA
                    </div>
                </div>

                <!-- Main Grid Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Kiri: Penampil Gambar (span 2) -->
                    <div
                        class="lg:col-span-2 bg-white rounded-3xl p-4 shadow-sm border border-neutral-100"
                    >
                        <!-- Area Gambar dengan Overlay -->
                        <div
                            class="relative w-full aspect-video bg-neutral-900 rounded-2xl overflow-hidden mb-4 group"
                        >
                            <!-- Gambar Kamera -->
                            <img
                                :src="receiptImage.url"
                                alt="Bukti Penerimaan"
                                class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition duration-300"
                            />

                            <!-- Overlay Text (Nama Kamera) -->
                            <div
                                class="absolute top-4 left-4 bg-black/60 backdrop-blur-sm px-3 py-1.5 rounded-full flex items-center gap-2"
                            >
                                <div
                                    class="w-2 h-2 rounded-full bg-red-500 animate-pulse"
                                ></div>
                                <span
                                    class="text-white text-xs font-mono font-medium"
                                    >{{ receiptImage.camera_name }}</span
                                >
                            </div>

                            <!-- Overlay Text (Waktu) -->
                            <div
                                class="absolute top-4 right-4 bg-black/60 backdrop-blur-sm px-3 py-1.5 rounded-full"
                            >
                                <span
                                    class="text-white text-xs font-mono font-medium"
                                    >{{ receiptImage.timestamp_overlay }}</span
                                >
                            </div>
                        </div>

                        <!-- Action Bar bawah gambar -->
                        <div class="flex items-center justify-between px-2">
                            <div class="flex gap-3">
                                <button
                                    @click="handleDownload"
                                    class="flex items-center gap-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-primary rounded-xl font-semibold text-sm transition"
                                >
                                    <Download class="w-4 h-4" /> Simpan Foto
                                </button>
                                <button
                                    @click="handleShare"
                                    class="flex items-center gap-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-primary rounded-xl font-semibold text-sm transition"
                                >
                                    <Share2 class="w-4 h-4" /> Bagikan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: Informasi Paket & Bantuan (span 1) -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Kartu Informasi Paket -->
                        <div
                            class="bg-[#0B1221] rounded-3xl p-8 shadow-lg text-white"
                        >
                            <div class="flex items-center gap-3 mb-8">
                                <Info class="w-6 h-6 text-tertiary-200" />
                                <h3 class="text-xl font-bold">
                                    Informasi Paket
                                </h3>
                            </div>

                            <div class="space-y-6">
                                <!-- Info 1 -->
                                <div>
                                    <p
                                        class="text-[11px] font-bold text-secondary-300 uppercase tracking-widest mb-1"
                                    >
                                        Nomer Resi
                                    </p>
                                    <p class="text-lg font-semibold">
                                        {{ packageInfo.tracking_number }}
                                    </p>
                                </div>

                                <!-- Info 2 -->
                                <div>
                                    <p
                                        class="text-[11px] font-bold text-secondary-300 uppercase tracking-widest mb-2"
                                    >
                                        Status Pengiriman
                                    </p>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0"
                                        >
                                            <Truck class="w-5 h-5 text-white" />
                                        </div>
                                        <p class="font-semibold">
                                            Sudah Diterima
                                        </p>
                                    </div>
                                </div>

                                <!-- Info 3 -->
                                <div>
                                    <p
                                        class="text-[11px] font-bold text-secondary-300 uppercase tracking-widest mb-1"
                                    >
                                        Waktu Kedatangan
                                    </p>
                                    <p class="font-semibold">
                                        {{ packageInfo.arrival_time_formatted }}
                                    </p>
                                </div>
                                <div>
                                    <Link
                                        :href="`/user/packages/${packageInfo.package_id}`"
                                        class="bg-tertiary-600 hover:bg-secondary-400 flex items-center justify-center space-x-2 px-6 py-3 rounded-2xl text-md font-semibold text-primary hover:text-primary  transition-colors shadow-md"
                                        title="Detail Paket"
                                    >
                                        <Banknote />
                                        <span>Detail Pembayaran</span>
                                    </Link>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
