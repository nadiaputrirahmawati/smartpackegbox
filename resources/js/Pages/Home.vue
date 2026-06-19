<template>
    <div
        class="min-h-screen bg-[#f4f7fb] font-sans text-primary selection:bg-tertiary-500"
    >
        <header
            class="w-full px-6 md:px-16 py-6 flex justify-between items-center max-w-[1920px] mx-auto absolute top-0 left-0 right-0 z-50"
        >
            <div class="text-xl font-extrabold tracking-tight text-primary">
                SmartBox
            </div>

            <nav
                class="hidden md:flex gap-10 text-sm font-bold text-secondary-200"
            >
                <a href="#" class="hover:text-primary transition-colors"
                    >Security</a
                >
                <a href="#" class="hover:text-primary transition-colors"
                    >Guide</a
                >
                <a href="#" class="hover:text-primary transition-colors"
                    >Support</a
                >
            </nav>

            <div class="flex items-center gap-6">
                <Globe
                    class="w-5 h-5 text-secondary-200 hover:text-primary cursor-pointer transition-colors hidden md:block"
                />
                <Link
                    href="/login"
                    class="bg-primary hover:bg-primary-100 text-white px-6 py-2.5 rounded-full font-bold text-sm transition-all shadow-md"
                >
                    Sign in
                </Link>
            </div>
        </header>

        <section
            class="pt-32 pb-20 md:pt-40 md:pb-32 px-6 md:px-16 max-w-[1920px] mx-auto"
        >
            <div
                class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center"
            >
                <div>
                    <div
                        class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-full text-[9px] font-extrabold tracking-widest uppercase inline-flex items-center gap-2 mb-6"
                    >
                        <div
                            class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"
                        ></div>
                        Smart Package Box
                    </div>

                    <h1
                        class="text-4xl md:text-6xl font-extrabold leading-[1.1] tracking-tight mb-6 text-primary"
                    >
                        Automatic Package Mechine
                    </h1>

                    <p
                        class="text-secondary-200 font-medium text-lg max-w-lg mb-10 leading-relaxed"
                    >
                        Terima paket kapan saja tanpa khawatir. Smart Package
                        Box memberikan kemudahan
                        dalam setiap proses pengiriman terakhir <i><b>(last mile
                        delivery).</b></i>
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <Link
                            href="/login"
                            class="bg-primary hover:bg-primary-100 text-white px-8 py-4 rounded-full font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-lg shadow-primary/20"
                        >
                            Lanjut ke Login <ArrowRight class="w-4 h-4" />
                        </Link>
                        <a
                            href="#protocol"
                            class="bg-white hover:bg-gray-50 border border-secondary-600 text-primary px-8 py-4 rounded-full font-bold text-sm transition-all flex items-center justify-center text-center"
                        >
                            Pelajari Protokol
                        </a>
                    </div>
                </div>

                <div
                    class="relative w-full aspect-square md:aspect-auto md:h-[500px] bg-[#0a1128] rounded-[3rem] shadow-2xl overflow-hidden flex flex-col justify-between p-8 group"
                >
                    <div
                        class="absolute inset-0 flex items-center justify-center opacity-40"
                    >
                        <div
                            class="w-[300px] h-[300px] rounded-full border-[1px] border-blue-500/30 border-dashed animate-[spin_20s_linear_infinite]"
                        ></div>
                        <div
                            class="absolute w-[200px] h-[200px] rounded-full border-[1px] border-cyan-400/20 border-dotted animate-[spin_15s_linear_infinite_reverse]"
                        ></div>
                    </div>

                    <div
                        class="absolute top-0 left-0 w-full h-1/2 bg-gradient-to-b from-blue-500/10 to-transparent"
                    ></div>

                    <div
                        class="flex-1 flex items-center justify-center relative z-10 gap-8"
                    >
                        <div
                            class="w-8 h-32 bg-cyan-400 rounded-sm shadow-[0_0_30px_rgba(34,211,238,0.6)]"
                        ></div>
                        <div
                            class="w-32 h-32 border-[12px] border-cyan-400 rounded-2xl shadow-[0_0_30px_rgba(34,211,238,0.6),inset_0_0_30px_rgba(34,211,238,0.4)]"
                        ></div>
                    </div>

                    <div
                        class="relative z-10 bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/10 flex justify-between items-center"
                    >
                        <div>
                            <p
                                class="text-[9px] text-cyan-200/70 font-extrabold tracking-widest uppercase mb-1"
                            >
                                Encryption Status
                            </p>
                            <p class="text-white font-bold text-sm">
                                256-bit AES Active
                            </p>
                        </div>
                        <ShieldCheck class="w-6 h-6 text-cyan-400" />
                    </div>
                </div>
            </div>
        </section>

        <section
            id="protocol"
            class="py-20 px-6 md:px-16 max-w-[1920px] mx-auto"
        >
            <div
                class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6"
            >
                <div>
                    <h2 class="text-3xl font-extrabold mb-4 text-primary">
                        Cara Kerja Ekosistem
                    </h2>
                    <p
                        class="text-secondary-200 text-sm max-w-md leading-relaxed"
                    >
                        Sistem terdistribusi yang menyatukan keamanan fisik dan
                        digital dalam satu alur kerja linear.
                    </p>
                </div>
                <div
                    class="w-full md:w-auto flex-1 md:flex-none border-b border-gray-300 md:border-none pb-2 md:pb-0"
                >
                    <p
                        class="text-[10px] font-extrabold text-gray-400 tracking-widest uppercase text-right"
                    >
                        PROTOCOL V.4.0
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="bg-white rounded-[2rem] p-10 shadow-[0_4px_20px_rgb(0,0,0,0.03)] relative overflow-hidden group hover:shadow-lg transition-all"
                >
                    <div
                        class="w-12 h-12 bg-[#f4f7fb] rounded-xl flex items-center justify-center text-primary mb-8"
                    >
                        <UserCheck class="w-5 h-5" />
                    </div>
                    <h3
                        class="text-xl font-extrabold mb-3 text-primary relative z-10"
                    >
                        Registrasi Akun
                    </h3>
                    <p
                        class="text-sm text-secondary-200 leading-relaxed relative z-10"
                    >
                        Verifikasi identitas multifaktor untuk memastikan akses
                        hanya diberikan kepada personil yang berwenang.
                    </p>
                    <div
                        class="absolute -bottom-6 -left-2 text-[120px] font-extrabold text-gray-50 leading-none pointer-events-none select-none transition-transform group-hover:-translate-y-2"
                    >
                        01
                    </div>
                </div>

                <div
                    class="bg-white rounded-[2rem] p-10 shadow-[0_4px_20px_rgb(0,0,0,0.03)] relative overflow-hidden group hover:shadow-lg transition-all"
                >
                    <div
                        class="w-12 h-12 bg-[#f4f7fb] rounded-xl flex items-center justify-center text-primary mb-8"
                    >
                        <Truck class="w-5 h-5" />
                    </div>
                    <h3
                        class="text-xl font-extrabold mb-3 text-primary relative z-10"
                    >
                        Pengiriman Paket
                    </h3>
                    <p
                        class="text-sm text-secondary-200 leading-relaxed relative z-10"
                    >
                        Lacak setiap milidetik perjalanan paket Anda dengan
                        sensor IoT yang terintegrasi secara real-time.
                    </p>
                    <div
                        class="absolute -bottom-6 -left-2 text-[120px] font-extrabold text-gray-50 leading-none pointer-events-none select-none transition-transform group-hover:-translate-y-2"
                    >
                        02
                    </div>
                </div>

                <div
                    class="bg-white rounded-[2rem] p-10 shadow-[0_4px_20px_rgb(0,0,0,0.03)] relative overflow-hidden group hover:shadow-lg transition-all"
                >
                    <div
                        class="w-12 h-12 bg-[#f4f7fb] rounded-xl flex items-center justify-center text-primary mb-8"
                    >
                        <Binary class="w-5 h-5" />
                    </div>
                    <h3
                        class="text-xl font-extrabold mb-3 text-primary relative z-10"
                    >
                        Pengambilan Aman
                    </h3>
                    <p
                        class="text-sm text-secondary-200 leading-relaxed relative z-10"
                    >
                        Gunakan kode PIN dinamis yang dihasilkan sistem untuk
                        membuka kunci fisik unit SmartBox Anda.
                    </p>
                    <div
                        class="absolute -bottom-6 -left-2 text-[120px] font-extrabold text-gray-50 leading-none pointer-events-none select-none transition-transform group-hover:-translate-y-2"
                    >
                        03
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 px-6 md:px-16 max-w-[1920px] mx-auto">
            <div
                class="bg-primary rounded-[3rem] p-8 md:p-16 shadow-2xl text-white grid grid-cols-1 lg:grid-cols-2 gap-16 items-center"
            >
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold mb-12">
                        Visualisasi Keamanan<br />Teknis
                    </h2>

                    <div class="space-y-10">
                        <div class="flex gap-6">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 shrink-0"
                            >
                                <Lock class="w-4 h-4" />
                            </div>
                            <div>
                                <h4 class="font-extrabold text-base mb-2">
                                    Enkripsi Data End-to-End
                                </h4>
                                <p
                                    class="text-xs text-gray-400 leading-relaxed"
                                >
                                    Data pengiriman tidak pernah tersimpan dalam
                                    bentuk teks biasa. Algoritma SHA-256
                                    memastikan integritas paket tetap terjaga.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-6">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 shrink-0"
                            >
                                <Shield class="w-4 h-4" />
                            </div>
                            <div>
                                <h4 class="font-extrabold text-base mb-2">
                                    Proteksi Fisik Anti-Tamper
                                </h4>
                                <p
                                    class="text-xs text-gray-400 leading-relaxed"
                                >
                                    Material baja berlapis karbon dengan sensor
                                    getaran mendeteksi upaya paksa dan secara
                                    otomatis mengunci sistem.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-6">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 shrink-0"
                            >
                                <EyeOff class="w-4 h-4" />
                            </div>
                            <div>
                                <h4 class="font-extrabold text-base mb-2">
                                    Privacy Cloak Technology
                                </h4>
                                <p
                                    class="text-xs text-gray-400 leading-relaxed"
                                >
                                    Menyamarkan identitas penerima pada label
                                    fisik, hanya dapat dibaca melalui pemindai
                                    sistem otentikasi kami.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-[#1a214d] border border-white/10 rounded-3xl p-8 shadow-2xl w-full max-w-lg mx-auto transform hover:scale-[1.02] transition-transform"
                >
                    <div class="flex items-center gap-2 mb-8">
                        <div class="flex gap-1.5">
                            <div
                                class="w-2.5 h-2.5 rounded-full bg-red-400"
                            ></div>
                            <div
                                class="w-2.5 h-2.5 rounded-full bg-yellow-400"
                            ></div>
                            <div
                                class="w-2.5 h-2.5 rounded-full bg-green-400"
                            ></div>
                        </div>
                        <p
                            class="text-[8px] text-gray-500 font-mono tracking-widest uppercase ml-4"
                        >
                            Security_Core_Dashboard
                        </p>
                    </div>

                    <div class="mb-8 flex items-center justify-between gap-4">
                        <div
                            class="h-2 flex-1 bg-white/5 rounded-full overflow-hidden"
                        >
                            <div class="h-full bg-blue-500 w-[65%]"></div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-extrabold">65%</p>
                            <p
                                class="text-[8px] text-gray-400 uppercase tracking-widest"
                            >
                                Load
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div
                            class="bg-white/5 rounded-2xl p-5 text-center border border-white/5"
                        >
                            <h3 class="text-2xl font-extrabold text-white mb-1">
                                10.4ms
                            </h3>
                            <p
                                class="text-[8px] text-gray-400 uppercase tracking-widest"
                            >
                                Response Latency
                            </p>
                        </div>
                        <div
                            class="bg-blue-500/10 rounded-2xl p-5 text-center border border-blue-500/20"
                        >
                            <h3
                                class="text-2xl font-extrabold text-blue-400 mb-1"
                            >
                                ACTIVE
                            </h3>
                            <p
                                class="text-[8px] text-blue-400/60 uppercase tracking-widest"
                            >
                                Encryption Hub
                            </p>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-end mb-3">
                            <p class="text-xs font-medium text-gray-300">
                                Node Integrity Check
                            </p>
                            <p
                                class="text-[9px] font-extrabold text-emerald-400 uppercase tracking-widest"
                            >
                                Passed
                            </p>
                        </div>
                        <div class="flex gap-1">
                            <div
                                v-for="n in 8"
                                :key="n"
                                class="h-1 flex-1 rounded-full bg-emerald-400"
                                :class="{ 'opacity-20': n > 6 }"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer
            class="bg-white border-t border-gray-200 pt-10 pb-6 px-6 md:px-16"
        >
            <div
                class="max-w-[1920px] mx-auto flex flex-col md:flex-row justify-between items-center gap-6"
            >
                <div>
                    <h4 class="text-lg font-extrabold text-primary mb-1">
                        SmartBox IoT
                    </h4>
                    <p class="text-[10px] text-gray-400">
                        © 2024 SmartBox IoT. High-End Command Editorial
                        Infrastructure.
                    </p>
                </div>

                <div
                    class="flex flex-wrap justify-center gap-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider"
                >
                    <a href="#" class="hover:text-primary transition-colors"
                        >Security Protocol</a
                    >
                    <a href="#" class="hover:text-primary transition-colors"
                        >Hardware Privacy</a
                    >
                    <a href="#" class="hover:text-primary transition-colors"
                        >System Compliance</a
                    >
                    <a href="#" class="hover:text-primary transition-colors"
                        >Contact Technician</a
                    >
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import {
    Globe,
    ArrowRight,
    ShieldCheck,
    UserCheck,
    Truck,
    Binary,
    Lock,
    Shield,
    EyeOff,
} from "@lucide/vue";

// Jika Anda menggunakan <Head> dari Inertia, letakkan title di sini
</script>

<style scoped>
/* Optional: Menambahkan animasi scroll halus jika ada menu anchor (#) */
html {
    scroll-behavior: smooth;
}
</style>
