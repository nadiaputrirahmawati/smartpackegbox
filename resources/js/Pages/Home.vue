<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";

// State untuk animasi pintu loker
const isDoorOpen = ref(false);
let doorAnimationLoop;

// --- STATE BARU UNTUK MODAL VIDEO ---
const isModalOpen = ref(false);

const startAnimation = () => {
    doorAnimationLoop = setInterval(() => {
        isDoorOpen.value = !isDoorOpen.value;
    }, 3500);
};

const stopAnimation = () => {
    if (doorAnimationLoop) clearInterval(doorAnimationLoop);
};

// Fungsi untuk membuka modal & stop animasi pintu sementara
const openModal = () => {
    isModalOpen.value = true;
    stopAnimation();
};

// Fungsi untuk menutup modal & menjalankan kembali animasi pintu
const closeModal = () => {
    isModalOpen.value = false;
    startAnimation();
};

onMounted(() => {
    startAnimation();
});

onUnmounted(() => {
    stopAnimation();
});
</script>

<template>
    <Head title="Smart Package Box - Sistem Otomatisasi Terpadu" />

    <div
        class="min-h-screen bg-[#060b26] text-slate-100 font-sans antialiased selection:bg-sky-500 selection:text-white overflow-x-hidden"
    >
        <div
            class="absolute top-0 left-1/4 w-[300px] md:w-[500px] h-[300px] md:h-[500px] bg-indigo-600/10 blur-[100px] md:blur-[150px] rounded-full -z-10"
        ></div>
        <div
            class="absolute top-[400px] right-10 w-[400px] md:w-[600px] h-[400px] md:h-[600px] bg-sky-600/10 blur-[120px] md:blur-[180px] rounded-full -z-10"
        ></div>

        <nav
            class="max-w-7xl mx-auto px-4 sm:px-6 py-6 flex items-center justify-between relative z-10"
        >
            <div
                class="flex items-center gap-3 font-bold text-lg md:text-xl tracking-wider text-white"
            >
                <div
                    class="w-8 h-8 bg-gradient-to-tr from-sky-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20"
                >
                    <span class="text-white text-sm font-black">S</span>
                </div>
                <span>Smart<span class="text-sky-400">Box</span></span>
            </div>

            <div
                class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-400"
            >
                <a href="#" class="hover:text-white transition duration-200"
                    >Beranda</a
                >
                <a href="#alur" class="hover:text-white transition duration-200"
                    >Alur Kerja</a
                >
                <a
                    href="#spesifikasi"
                    class="hover:text-white transition duration-200"
                    >Tentang</a
                >
            </div>

            <div class="flex items-center gap-4">
                <Link
                    href="/login"
                    class="px-5 md:px-6 py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 rounded-full shadow-lg shadow-indigo-500/20 transition duration-300"
                >
                    Masuk
                </Link>
            </div>
        </nav>

        <header
            class="max-w-7xl mx-auto px-4 sm:px-6 pt-12 md:pt-16 pb-16 md:pb-24 text-center relative z-10"
        >
            <h1
                class="text-3xl sm:text-4xl md:text-6xl font-black tracking-tight leading-[1.2] md:leading-[1.15] max-w-4xl mx-auto text-white"
            >
                Terima Paket Secara <br class="hidden sm:inline" />
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-blue-400 to-indigo-400"
                >
                    Otomatis Dengan Smart Box
                </span>
            </h1>

            <p
                class="mt-4 md:mt-6 text-sm md:text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed px-2"
            >
                Integrasi platform web dengan perangkat keras menggunakan
                verifikasi nomor resi. Memberikan kemudahan untuk logistik
                kurir.
            </p>

            <div
                class="showcase-scene relative w-full max-w-[900px] mx-auto h-[530px] sm:h-[600px] mt-12 flex justify-center items-center group px-4"
            >
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3/4 h-3/4 bg-sky-500/20 blur-[80px] md:blur-[100px] rounded-full -z-20"
                ></div>

                <div
                    class="phone-mockup phone-left bg-slate-950 hidden md:block"
                >
                    <div class="dynamic-island"></div>
                    <div class="w-full h-full pt-14 px-4 flex flex-col gap-3">
                        <h3 class="text-xs text-slate-400 font-semibold mb-2">
                            Riwayat Logistik
                        </h3>
                        <div
                            class="bg-slate-900 border border-slate-800 p-3 rounded-2xl flex flex-col gap-1.5"
                        >
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] text-slate-500"
                                    >Baru saja</span
                                >
                                <span
                                    class="text-[9px] bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-full font-bold"
                                    >Valid</span
                                >
                            </div>
                            <span class="text-xs font-bold text-white"
                                >Resi: JNT-891029</span
                            >
                            <span class="text-[10px] text-slate-400"
                                >Pintu tertutup & terkunci otomatis.</span
                            >
                        </div>
                        <div
                            class="bg-slate-900 border border-slate-800 p-3 rounded-2xl flex flex-col gap-1.5 opacity-70"
                        >
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] text-slate-500"
                                    >2 Jam lalu</span
                                >
                                <span
                                    class="text-[9px] bg-sky-500/20 text-sky-400 px-2 py-0.5 rounded-full font-bold"
                                    >Sinkron</span
                                >
                            </div>
                            <span class="text-[10px] text-slate-400"
                                >Pembaruan data Node ESP32 berhasil.</span
                            >
                        </div>
                    </div>
                </div>

                <div
                    class="phone-mockup phone-right bg-slate-950 hidden md:block"
                >
                    <div class="dynamic-island"></div>
                    <div class="w-full h-full pt-14 px-4 flex flex-col gap-4">
                        <h3 class="text-xs text-slate-400 font-semibold mb-1">
                            Proteksi Hardware
                        </h3>
                        <div
                            class="bg-slate-900 border border-slate-800 p-4 rounded-2xl flex flex-col gap-3"
                        >
                            <div class="flex justify-between items-center">
                                <div class="flex flex-col">
                                    <span
                                        class="text-[11px] font-bold text-slate-200"
                                        >Buka Rotary Money Box</span
                                    >
                                    <span
                                        class="text-[9px] text-slate-500 w-32 leading-tight mt-1"
                                        >Membuka Kotak Penyimpanan Uang.</span
                                    >
                                </div>
                                <div
                                    class="w-8 h-4 bg-emerald-500 rounded-full relative shadow-inner"
                                >
                                    <div
                                        class="w-3 h-3 bg-white rounded-full absolute right-0.5 top-0.5 shadow-sm"
                                    ></div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-slate-900 border border-slate-800 p-4 rounded-2xl flex flex-col gap-3"
                        >
                            <div class="flex justify-between items-center">
                                <div class="flex flex-col">
                                    <span
                                        class="text-[11px] font-bold text-slate-200"
                                        >Daftar Paket Di terima</span
                                    >
                                    <span class="text-[9px] text-slate-500 mt-1"
                                        >Komunikasi data real-time.</span
                                    >
                                </div>
                                <div
                                    class="w-8 h-4 bg-emerald-500 rounded-full relative shadow-inner"
                                >
                                    <div
                                        class="w-3 h-3 bg-white rounded-full absolute right-0.5 top-0.5 shadow-sm"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="phone-mockup phone-center bg-[#030712] relative overflow-hidden"
                >
                    <div class="dynamic-island"></div>
                    <div
                        class="absolute top-0 left-0 w-full h-[45%] bg-gradient-to-b from-sky-900/40 to-transparent -z-0"
                    ></div>

                    <div
                        class="w-full h-full pt-12 sm:pt-14 px-5 flex flex-col relative z-10 pb-6 justify-between md:justify-start"
                    >
                        <div class="text-center mb-2">
                            <h2
                                class="text-lg sm:text-xl font-black text-white tracking-tight"
                            >
                                Smart<span class="text-sky-400">Box</span>
                            </h2>
                            <p class="text-[10px] text-slate-400 mt-1">
                                Kotak Penerimaan Paket Otomatis
                            </p>
                        </div>

                        <div class="flex justify-center my-4 sm:my-8">
                            <div
                                class="relative w-36 h-44 sm:w-40 sm:h-48 bg-slate-900 rounded-xl shadow-inner border-4 border-slate-700"
                                style="perspective: 1000px"
                            >
                                <div
                                    class="absolute inset-0 flex flex-col items-center justify-center bg-gray-800 rounded-lg overflow-hidden shadow-inner"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-10 h-10 sm:w-12 sm:h-12 text-emerald-400 mb-2 transition-all duration-700 ease-out"
                                        :class="
                                            isDoorOpen
                                                ? 'opacity-100 scale-100 translate-y-0'
                                                : 'opacity-0 scale-50 translate-y-4'
                                        "
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"
                                        />
                                    </svg>
                                    <span
                                        class="text-[9px] text-gray-300 font-bold tracking-widest uppercase transition-all duration-700 delay-100"
                                        :class="
                                            isDoorOpen
                                                ? 'opacity-100'
                                                : 'opacity-0'
                                        "
                                    >
                                        Paket Aman
                                    </span>
                                </div>

                                <div
                                    class="absolute inset-0 bg-indigo-600 rounded-lg flex flex-col items-center justify-center shadow-lg border border-indigo-400/20 z-10"
                                    style="
                                        transform-origin: left;
                                        transition: transform 1s
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
                                        class="text-white/40 text-3xl sm:text-4xl font-extrabold tracking-wider"
                                        >01</span
                                    >
                                    <div
                                        class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-10 bg-white/20 rounded-full shadow-inner"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <button
                            @click="openModal"
                            class="w-full bg-gradient-to-r from-sky-500 to-indigo-600 text-white py-3 rounded-xl text-xs font-bold shadow-xl shadow-indigo-500/20 hover:opacity-90 transition transform hover:-translate-y-0.5 mt-2"
                        >
                            Demo Perangkat
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <section
            id="alur"
            class="max-w-7xl mx-auto px-4 sm:px-6 py-16 md:py-28 relative z-10"
        >
            <div class="text-center mb-12 md:mb-20">
                <h2
                    class="text-2xl md:text-4xl font-black tracking-tight text-white mb-4"
                >
                    Arsitektur Sistem Modern & Responsif
                </h2>
                <p
                    class="text-xs md:text-base text-slate-400 max-w-xl mx-auto leading-relaxed"
                >
                    Alur terintegrasi demi menjaga paket tetap aman, memastikan
                    fungsionalitas hardware sinkron dengan aplikasi web.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-gradient-to-b from-slate-900/90 to-slate-950/90 p-6 md:p-8 rounded-3xl border border-slate-800/80 shadow-xl flex flex-col justify-between"
                >
                    <div>
                        <div
                            class="w-10 h-10 md:w-12 md:h-12 bg-sky-500/10 border border-sky-500/20 text-sky-400 rounded-2xl flex items-center justify-center font-bold text-sm md:text-lg mb-6 md:mb-8 shadow-inner"
                        >
                            01
                        </div>
                        <h3
                            class="font-bold text-lg md:text-xl text-white mb-3 tracking-wide"
                        >
                            Konfigurasi Identitas & Resi
                        </h3>
                        <p
                            class="text-xs md:text-sm text-slate-400 leading-relaxed"
                        >
                            Pengguna mengatur identitas serta mendaftarkan nomor
                            resi aktif ke dalam sistem web secara terpusat.
                        </p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-b from-slate-900/90 to-slate-950/90 p-6 md:p-8 rounded-3xl border border-slate-800/80 shadow-xl flex flex-col justify-between"
                >
                    <div>
                        <div
                            class="w-10 h-10 md:w-12 md:h-12 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-2xl flex items-center justify-center font-bold text-sm md:text-lg mb-6 md:mb-8 shadow-inner"
                        >
                            02
                        </div>
                        <h3
                            class="font-bold text-lg md:text-xl text-white mb-3 tracking-wide"
                        >
                            Validasi Real-Time Kurir
                        </h3>
                        <p
                            class="text-xs md:text-sm text-slate-400 leading-relaxed"
                        >
                            Kurir memasukkan nomor resi pada panel hardware.
                            Sistem IoT memverifikasi string data jika cocok,
                            pintu terbuka.
                        </p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-b from-slate-900/90 to-slate-950/90 p-6 md:p-8 rounded-3xl border border-slate-800/80 shadow-xl flex flex-col justify-between relative overflow-hidden group"
                >
                    <div
                        class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-500/5 blur-xl group-hover:bg-emerald-500/10 transition duration-300 rounded-full"
                    ></div>
                    <div>
                        <div
                            class="w-10 h-10 md:w-12 md:h-12 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-center font-bold text-sm md:text-lg mb-6 md:mb-8 shadow-inner"
                        >
                            03
                        </div>
                        <h3
                            class="font-bold text-lg md:text-xl text-white mb-3 tracking-wide flex items-center gap-2"
                        >
                            Kunci Otomatis Fail-Safe
                        </h3>
                        <p
                            class="text-xs md:text-sm text-slate-400 leading-relaxed"
                        >
                            Jika pengguna lupa menutup/mengunci dari aplikasi
                            web, modul IoT akan otomatis mengunci pintu demi
                            keamanan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section
            id="spesifikasi"
            class="max-w-7xl mx-auto px-4 sm:px-6 pb-20 md:pb-32 relative z-10"
        >
            <div
                class="bg-gradient-to-br from-slate-900 via-[#0b1233] to-slate-950 rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-16 border border-slate-800 shadow-2xl flex flex-col lg:flex-row items-center justify-between gap-12 relative overflow-hidden"
            >
                <div
                    class="absolute -top-24 -left-24 w-72 h-72 bg-sky-500/10 blur-[100px] rounded-full"
                ></div>

                <div class="flex-1 relative z-10 text-left">
                    <h2
                        class="text-2xl md:text-3xl font-black text-white leading-tight mb-4"
                    >
                        Kelola & Pantau <br class="hidden sm:inline" />Seluruh
                        Slot dalam Satu Tempat
                    </h2>
                    <p
                        class="text-xs md:text-sm text-slate-400 mb-6 md:mb-8 leading-relaxed max-w-md"
                    >
                        Dashboard Web App menyediakan monitoring log kedatangan
                        paket, status kunci solenoid, riwayat resi terdaftar,
                        serta konfigurasi penuh untuk keamanan paket anda.
                    </p>
                    <div class="flex">
                        <Link
                            href="/dashboard"
                            class="bg-white text-slate-950 px-6 py-3.5 rounded-full text-xs font-bold shadow-lg hover:bg-slate-200 transition duration-200 w-full sm:w-auto text-center"
                        >
                            Buka Manajemen Web App
                        </Link>
                    </div>
                </div>

                <div
                    class="w-full max-w-[260px] sm:max-w-none flex-1 flex justify-center relative z-10 font-sans"
                >
                    <div
                        class="w-full max-w-[260px] h-[26rem] md:h-[28rem] bg-[#030712] rounded-[2.5rem] md:rounded-[3rem] shadow-2xl border-4 border-slate-800/90 overflow-y-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] relative p-3"
                    >
                        <div
                            class="absolute top-0 inset-x-0 h-6 flex justify-center items-center z-20"
                        >
                            <div
                                class="w-20 h-4 bg-slate-800 rounded-b-xl"
                            ></div>
                        </div>

                        <div
                            class="pt-6 pb-4 min-h-full flex flex-col gap-3 text-left"
                        >
                            <div
                                class="h-7 bg-slate-900 rounded-lg w-max px-2 border border-slate-800 flex items-center mt-2"
                            >
                                <span
                                    class="text-[9px] bg-sky-500/20 text-sky-400 px-2 py-0.5 rounded-full border border-sky-500/30 font-bold uppercase tracking-wide"
                                    >Status Loker</span
                                >
                            </div>

                            <div
                                class="bg-slate-900/60 rounded-[1.25rem] p-3 shadow-md flex flex-col gap-3 border border-slate-800 relative group"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full border-[3px] flex items-center justify-center shrink-0 border-blue-500/30 bg-blue-500/10 text-blue-400"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path d="m7.5 4.27 9 5.15" />
                                            <path
                                                d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"
                                            />
                                            <path d="m3.3 7 8.7 5 8.7-5" />
                                            <path d="M12 22V12" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4
                                            class="text-xs sm:text-sm font-bold text-white leading-none mb-1"
                                        >
                                            Kotak Utama #1
                                        </h4>
                                        <span
                                            class="text-[8px] font-extrabold tracking-widest uppercase px-2 py-0.5 rounded-full mt-3 bg-blue-500/20 text-blue-400 border border-blue-500/30"
                                            >Terisi</span
                                        >
                                    </div>
                                </div>
                                <p
                                    class="text-[9px] font-medium text-slate-400 leading-tight px-1"
                                >
                                    Kotak sedang menyimpan paket.
                                </p>
                                <div
                                    class="p-2 rounded-xl flex items-center justify-between bg-slate-800/50 border border-slate-700/50"
                                >
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 bg-slate-800 rounded-full flex items-center justify-center border border-slate-700 shrink-0"
                                        >
                                            <svg
                                                class="w-3.5 h-3.5 text-slate-400"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path
                                                    d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"
                                                />
                                                <circle cx="12" cy="7" r="4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p
                                                class="text-[8px] font-bold text-slate-500 tracking-widest uppercase"
                                            >
                                                Pemilik
                                            </p>
                                            <p
                                                class="text-[11px] font-bold text-white leading-none mt-0.5"
                                            >
                                                Nadia
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-slate-900/60 rounded-[1.25rem] p-3 shadow-md flex flex-col gap-3 border border-slate-800 relative group"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full border-[3px] flex items-center justify-center shrink-0 border-emerald-500/30 bg-emerald-500/10 text-emerald-400"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path
                                                d="M13 4h3a2 2 0 0 1 2 2v14"
                                            />
                                            <path d="M2 20h3" />
                                            <path d="M13 20h9" />
                                            <path d="M10 12v.01" />
                                            <path
                                                d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"
                                            />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4
                                            class="text-xs sm:text-sm font-bold text-white leading-none mb-1"
                                        >
                                            Kotak Utama #2
                                        </h4>
                                        <span
                                            class="text-[8px] font-extrabold tracking-widest uppercase px-2 py-0.5 rounded-full mt-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30"
                                            >Tersedia</span
                                        >
                                    </div>
                                </div>
                                <p
                                    class="text-[9px] font-medium text-slate-400 leading-tight px-1"
                                >
                                    Kotak kosong, siap menerima paket.
                                </p>
                                <div
                                    class="p-2 rounded-xl flex items-center justify-between bg-slate-900/40 border border-dashed border-slate-700"
                                >
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 bg-slate-800/80 rounded-full flex items-center justify-center border border-slate-700 shrink-0"
                                        >
                                            <svg
                                                class="w-3.5 h-3.5 text-slate-500"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path
                                                    d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                                                />
                                                <circle cx="9" cy="7" r="4" />
                                                <line
                                                    x1="17"
                                                    y1="8"
                                                    x2="22"
                                                    y2="13"
                                                />
                                                <line
                                                    x1="22"
                                                    y1="8"
                                                    x2="17"
                                                    y2="13"
                                                />
                                            </svg>
                                        </div>
                                        <div>
                                            <p
                                                class="text-[8px] font-bold text-slate-500 tracking-widest uppercase"
                                            >
                                                Pemilik
                                            </p>
                                            <p
                                                class="text-[10px] font-bold text-slate-500 italic leading-none mt-0.5"
                                            >
                                                Belum Ada
                                            </p>
                                        </div>
                                    </div>
                                    <button
                                        class="flex items-center gap-1 text-[9px] font-bold text-slate-300 bg-slate-800 border border-slate-700 px-2 py-1 rounded-full hover:bg-slate-700 hover:text-white shadow-sm transition"
                                    >
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer
            id="kontak"
            class="border-t border-slate-900 bg-slate-950/80 backdrop-blur-md pt-12 pb-8 relative z-10"
        >
            <div
                class="max-w-7xl mx-auto px-6 text-center text-xs text-slate-500 tracking-wide font-medium"
            >
                <p>&copy; 2026 Smart Package Box. Hak Cipta Dilindungi.</p>
                <p class="mt-1">Nadia Putri Rahmawati.</p>
            </div>
        </footer>
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md transition-all duration-300"
            @click.self="closeModal"
        >
            <div
                class="relative w-full max-w-3xl bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl transform scale-100 transition-all"
            >
                <div
                    class="flex items-center justify-between px-6 py-4 border-b border-slate-800 bg-slate-950/40"
                >
                    <h3
                        class="text-sm font-bold text-white tracking-wide flex items-center gap-2"
                    >
                        <span
                            class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"
                        ></span>
                        Demo Demonstrasi Alat Smart Box IoT
                    </h3>
                    <button
                        @click="closeModal"
                        class="p-1 rounded-full text-slate-400 hover:bg-slate-800 hover:text-white transition"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="relative w-full aspect-video bg-black">
                    <iframe
                        v-if="isModalOpen"
                        class="absolute inset-0 w-full h-full"
                        src="https://www.youtube.com/embed/6_I4qNtYQ-g?autoplay=1"
                        title="YouTube video player"
                        frameborder="0"
                        allow="
                            accelerometer;
                            autoplay;
                            clipboard-write;
                            encrypted-media;
                            gyroscope;
                            picture-in-picture;
                            web-share;
                        "
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.showcase-scene {
    perspective: 1200px;
    transform-style: preserve-3d;
}

.phone-mockup {
    position: absolute;
    border-radius: 3rem;
    border: 10px solid #1e293b;
    box-shadow:
        inset 0 0 0 2px #0f172a,
        0 25px 50px -12px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1);
}

.dynamic-island {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 90px;
    height: 24px;
    background-color: #000;
    border-radius: 9999px;
    z-index: 50;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

/* Kunci Lebar & Tinggi Statis menggunakan media query desktop agar tidak merusak layout HP */
@media (min-width: 768px) {
    .phone-left {
        width: 250px;
        height: 520px;
        transform: translateX(-65%) translateZ(-150px) rotateY(25deg)
            rotateZ(-3deg);
        z-index: 10;
        opacity: 0.85;
    }

    .phone-right {
        width: 250px;
        height: 520px;
        transform: translateX(65%) translateZ(-150px) rotateY(-25deg)
            rotateZ(3deg);
        z-index: 10;
        opacity: 0.85;
    }

    .phone-center {
        width: 280px;
        height: 580px;
        transform: translateZ(50px) translateY(15px);
        z-index: 20;
        box-shadow:
            0 35px 60px -15px rgba(0, 0, 0, 0.8),
            inset 0 0 0 2px #334155;
        border-color: #0f172a;
    }

    .showcase-scene:hover .phone-left {
        transform: translateX(-75%) translateZ(-100px) rotateY(20deg)
            rotateZ(-2deg);
        opacity: 1;
    }
    .showcase-scene:hover .phone-right {
        transform: translateX(75%) translateZ(-100px) rotateY(-20deg)
            rotateZ(2deg);
        opacity: 1;
    }
    .showcase-scene:hover .phone-center {
        transform: translateZ(80px) translateY(0px);
    }
}

/* Rule Khusus Layar HP/Mobile */
@media (max-width: 767px) {
    .phone-center {
        position: relative;
        width: 100%;
        max-width: 270px;
        height: 100%;
        max-height: 500px;
        transform: none !important; /* Matikan efek geser 3D di HP */
        border-width: 8px;
    }
}
</style>
