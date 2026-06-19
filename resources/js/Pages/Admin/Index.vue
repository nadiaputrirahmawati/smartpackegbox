<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";
import {
    Archive,
    Users,
    Shield,
    CheckCircle2,
    DoorOpen,
    ChevronRight,
    Package,
    Plus,
    User,
    UserX,
    CameraOff,
    Smartphone,
    Monitor,
    Hash,
    ScanBarcode,
    ArrowUpRight,
    AlertTriangle
} from "@lucide/vue";

const props = defineProps({
    stats: Object,
    slots: Array,
    recent_logs: Array,
});

// --- MAPPING LOGIKA PINDAH KE FRONTEND ---
const formatEventData = (log) => {
    // Cek jika Gagal
    if (log.status === "failed" || log.event === "access_denied") {
        return {
            title: "Akses Ditolak",
            badgeText: "Gagal",
            badgeClass: "bg-rose-50 text-rose-700 ring-rose-600/20",
            dotClass: "bg-rose-500 animate-pulse",
        };
    }

    // Pemetaan untuk Event yang Berhasil
    switch (log.event) {
        case "door_opened":
            return {
                title: "Kotak Paket Terbuka",
                badgeText: "Sukses",
                badgeClass:
                    "bg-emerald-50 text-emerald-700 ring-emerald-600/20",
                dotClass: "bg-emerald-500",
            };
        case "package_arrived":
            return {
                title: "Paket Telah Diterima",
                badgeText: "Sukses",
                badgeClass:
                    "bg-emerald-50 text-emerald-700 ring-emerald-600/20",
                dotClass: "bg-emerald-500",
            };
        default:
            return {
                title: "Aktivitas Sistem",
                badgeText: "OK",
                badgeClass: "bg-slate-100 text-slate-700 ring-slate-600/20",
                dotClass: "bg-slate-500",
            };
    }
};
</script>

<template>
    <AdminLayout>
        <div class="bg-[#f8f9fc] min-h-screen font-sans">
            <div class="mx-auto space-y-10">
                <header>
                    <h1
                        class="text-4xl font-extrabold text-[#0f172a] tracking-tight mb-2"
                    >
                        Management Sistem Smart Package Box
                    </h1>
                    <p
                        class="text-slate-500 font-medium flex items-center gap-2"
                    >
                        <span
                            class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"
                        ></span>
                        Sistem dapat beroperasi optimal. Memantau aktivitas
                        sistem secara real-time.
                    </p>
                </header>

<section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 relative">
    
    <div class="group relative rounded-[2rem] bg-white/60 backdrop-blur-xl border border-white/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_8px_40px_rgba(59,130,246,0.12)]">
        <div class="absolute -inset-20 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-blue-100/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-blue-400/10 rounded-full blur-2xl group-hover:bg-blue-400/20 transition-colors duration-700 animate-pulse"></div>

        <div class="p-8 relative z-10 h-full flex flex-col justify-between">
            <div class="flex justify-between items-start mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner border border-blue-200/50 group-hover:scale-110 transition-transform duration-500">
                    <Archive class="w-6 h-6" />
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-blue-700 bg-blue-50/80 backdrop-blur-sm px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest ring-1 ring-inset ring-blue-600/20 mb-1">
                        Hari Ini
                    </span>
                </div>
            </div>
            
            <div>
                <p class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider text-[11px]">Total Paket Masuk</p>
                <h3 class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-slate-900 to-slate-600 tracking-tight">
                    {{ stats.total_packages_today }}
                </h3>
            </div>
        </div>
    </div>

    <div class="group relative rounded-[2rem] bg-white/60 backdrop-blur-xl border border-white/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_8px_40px_rgba(79,70,229,0.12)]">
        <div class="absolute -inset-20 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-100/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-400/10 rounded-full blur-2xl group-hover:bg-indigo-400/20 transition-colors duration-700 animate-pulse" style="animation-delay: 200ms;"></div>

        <div class="p-8 relative z-10 h-full flex flex-col justify-between">
            <div class="flex justify-between items-start mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner border border-indigo-200/50 group-hover:scale-110 transition-transform duration-500">
                    <Users class="w-6 h-6" />
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-indigo-700 bg-indigo-50/80 backdrop-blur-sm px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest ring-1 ring-inset ring-indigo-600/20 mb-1">
                        Sistem
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 flex items-center gap-0.5">
                        Stabil &bull; Aktif
                    </span>
                </div>
            </div>
            
            <div>
                <p class="text-slate-500 font-semibold text-sm mb-1 uppercase tracking-wider text-[11px]">Pengguna Terdaftar</p>
                <h3 class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-slate-900 to-slate-600 tracking-tight">
                    {{ stats.active_users }}
                </h3>
            </div>
        </div>
    </div>

    <div :class="['group relative rounded-[2rem] overflow-hidden transition-all duration-500 hover:-translate-y-1',
                  stats.failed_access_today > 0 ? 'bg-gradient-to-b from-rose-950 to-slate-950 border border-rose-900/50 hover:shadow-[0_8px_40px_rgba(225,29,72,0.25)]' : 'bg-gradient-to-b from-slate-900 to-slate-950 border border-slate-800 hover:shadow-[0_8px_40px_rgba(16,185,129,0.15)]']">
        
        <div class="absolute inset-0 backdrop-blur-2xl bg-black/10"></div>
        
        <div :class="['absolute -right-20 -top-20 w-64 h-64 rounded-full blur-3xl transition-all duration-1000 ease-in-out group-hover:scale-110',
                      stats.failed_access_today > 0 ? 'bg-rose-600/20' : 'bg-emerald-500/10']"></div>
        
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 16px 16px;"></div>

        <div class="p-8 relative z-10 h-full flex flex-col justify-between">
            <div class="flex justify-between items-start mb-8">
                <div class="relative">
                    <div v-if="stats.failed_access_today > 0" class="absolute inset-0 bg-rose-500 rounded-2xl animate-ping opacity-20"></div>
                    <div :class="['relative w-12 h-12 rounded-2xl flex items-center justify-center text-white border transition-transform duration-500 group-hover:scale-110 shadow-inner',
                                  stats.failed_access_today > 0 ? 'bg-gradient-to-br from-rose-500/20 to-rose-600/40 border-rose-500/30' : 'bg-gradient-to-br from-white/10 to-white/5 border-white/10']">
                        <AlertTriangle v-if="stats.failed_access_today > 0" class="w-6 h-6 text-rose-400" />
                        <Shield v-else class="w-6 h-6 text-emerald-400" />
                    </div>
                </div>

                <span v-if="stats.failed_access_today === 0" class="text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5 backdrop-blur-md">
                    <CheckCircle2 class="w-3.5 h-3.5" /> Aman
                </span>
                <span v-else class="text-rose-400 bg-rose-500/20 border border-rose-400/30 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5 animate-pulse backdrop-blur-md shadow-[0_0_15px_rgba(225,29,72,0.3)]">
                    <AlertTriangle class="w-3.5 h-3.5" /> Waspada
                </span>
            </div>

            <div>
                <p :class="['font-semibold text-sm mb-1 uppercase tracking-wider text-[11px] transition-colors duration-300', 
                            stats.failed_access_today > 0 ? 'text-rose-300/70 group-hover:text-rose-300' : 'text-slate-400 group-hover:text-emerald-300']">
                    Akses Gagal Hari Ini
                </p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-b from-white to-white/60 tracking-tight">
                        {{ stats.failed_access_today }}
                    </h3>
                    <span class="text-sm font-semibold opacity-50 text-white font-sans tracking-normal">Percobaan</span>
                </div>
            </div>
        </div>
    </div>
</section>

                <section>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-slate-900">
                            Status Loker Fisik
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div
                            v-for="slot in slots"
                            :key="slot.box"
                            class="bg-white rounded-[2rem] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-col sm:flex-row gap-8 items-center border border-slate-100 hover:shadow-lg transition-all duration-300 group"
                        >
                            <div
                                class="flex flex-col items-center justify-center shrink-0 w-32"
                            >
                                <div
                                    :class="[
                                        'w-[84px] h-[84px] rounded-full border-4 flex items-center justify-center mb-3 shadow-sm transition-transform group-hover:scale-105',
                                        slot.is_occupied
                                            ? 'border-blue-100 bg-blue-50 text-blue-600'
                                            : 'border-emerald-50 bg-emerald-50 text-emerald-500',
                                    ]"
                                >
                                    <Package
                                        v-if="slot.is_occupied"
                                        class="w-8 h-8"
                                        :stroke-width="1.5"
                                    />
                                    <DoorOpen
                                        v-else
                                        class="w-8 h-8"
                                        :stroke-width="1.5"
                                    />
                                </div>
                                <span
                                    :class="[
                                        'text-[11px] font-extrabold tracking-widest uppercase px-3 py-1 rounded-full',
                                        slot.is_occupied
                                            ? 'bg-blue-50 text-blue-700'
                                            : 'bg-emerald-50 text-emerald-700',
                                    ]"
                                >
                                    {{
                                        slot.is_occupied ? "Terisi" : "Tersedia"
                                    }}
                                </span>
                            </div>

                            <div class="flex-1 w-full">
                                <div
                                    class="flex justify-between items-start mb-4"
                                >
                                    <div>
                                        <h4
                                            class="text-2xl font-bold text-slate-900 mb-1"
                                        >
                                            {{ slot.name }}
                                        </h4>
                                        <p
                                            v-if="slot.is_occupied"
                                            class="text-xs font-semibold text-slate-500"
                                        >
                                            Kotak Paket Terisi
                                        </p>
                                        <p
                                            v-else
                                            class="text-xs font-semibold text-slate-500"
                                        >
                                            Kotak Paket kosong, siap menerima
                                            paket
                                        </p>
                                    </div>
                                </div>

                                <div
                                    :class="[
                                        'p-4 rounded-2xl flex items-center justify-between mt-2 transition-colors',
                                        slot.has_owner
                                            ? 'bg-slate-50 border border-transparent'
                                            : 'bg-white border border-dashed border-slate-300',
                                    ]"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm overflow-hidden border border-slate-200 shrink-0"
                                        >
                                            <img
                                                v-if="slot.owner_avatar"
                                                :src="slot.owner_avatar"
                                                alt="Avatar"
                                                class="w-full h-full object-cover"
                                            />
                                            <UserX
                                                v-else-if="!slot.has_owner"
                                                class="w-5 h-5 text-slate-300"
                                            />
                                            <User
                                                v-else
                                                class="w-5 h-5 text-slate-400"
                                            />
                                        </div>
                                        <div>
                                            <p
                                                class="text-[12px] font-bold text-slate-600 tracking-widest uppercase"
                                            >
                                                Pemilik Loker
                                            </p>
                                            <p
                                                :class="[
                                                    'text-sm font-bold',
                                                    slot.has_owner
                                                        ? 'text-slate-900'
                                                        : 'text-slate-400 italic',
                                                ]"
                                            >
                                                {{ slot.owner_name }}
                                            </p>
                                        </div>
                                    </div>

                                    <button
                                        v-if="!slot.has_owner"
                                        class="flex items-center gap-1 text-xs font-bold text-slate-700 bg-white border border-slate-200 px-3 py-1.5 rounded-full hover:bg-slate-50 shadow-sm transition"
                                    >
                                        <Plus class="w-3 h-3" /> Tambah Pemilik
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    class="bg-white rounded-[2rem] p-6 md:p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100"
                >
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6"
                    >
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">
                                Aktivitas Sistem Terkini
                            </h2>
                            <p class="text-sm font-medium text-slate-500 mt-1">
                                Riwayat otentikasi, input data, dan penggunaan
                                loker.
                            </p>
                        </div>
                        <Link
                            href="/activity-logs"
                            class="text-sm font-bold text-blue-600 bg-blue-50 px-4 py-2.5 rounded-full hover:bg-blue-100 transition-colors flex items-center gap-1.5"
                        >
                            Lihat Semua Log <ArrowUpRight class="w-4 h-4" />
                        </Link>
                    </div>

                    <div class="overflow-x-auto lg:overflow-visible">
                        <div class="min-w-[800px]">
                            <div
                                class="grid grid-cols-12 gap-4 pb-3 border-b border-slate-200 text-sm font-extrabold text-primary-100 uppercase tracking-widest px-4"
                            >
                                <div class="col-span-1 text-center">Bukti</div>
                                <div class="col-span-3">Aktivitas & Waktu</div>
                                <div class="col-span-3">Aktor & Data Input</div>
                                <div class="col-span-2">Metode</div>
                                <div class="col-span-2">Status</div>
                                <div class="col-span-1 text-right">Detail</div>
                            </div>

                            <div class="divide-y divide-slate-100">
                                <div
                                    v-for="log in recent_logs"
                                    :key="log.id"
                                    class="grid grid-cols-12 gap-4 items-center py-4 px-4 hover:bg-slate-50/80 transition-colors group rounded-xl -mx-4"
                                >
                                    <div class="col-span-1 flex justify-center">
                                        <div
                                            class="relative w-12 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm shrink-0"
                                        >
                                            <img
                                                v-if="log.image_url"
                                                :src="log.image_url"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            />
                                            <div
                                                v-else
                                                class="w-full h-full flex items-center justify-center"
                                            >
                                                <CameraOff
                                                    class="w-5 h-5 text-slate-300"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="col-span-3 flex flex-col justify-center"
                                    >
                                        <p
                                            class="text-sm font-bold text-slate-900"
                                        >
                                            {{ formatEventData(log).title }}
                                        </p>
                                        <p
                                            class="text-xs font-medium text-slate-500 mt-0.5"
                                        >
                                            {{ log.created_at }} WIB
                                        </p>
                                    </div>

                                    <div
                                        class="col-span-3 flex flex-col justify-center items-start"
                                    >
                                        <p
                                            class="text-sm font-semibold text-slate-700 capitalize"
                                        >
                                            {{
                                                log.actor_name === "System"
                                                    ? "Server Core"
                                                    : log.actor_name
                                            }}
                                        </p>
                                        <div
                                            class="mt-1 flex items-center gap-1.5"
                                        >
                                            <span
                                                class="text-[12px] text-slate-600 font-bold uppercase tracking-wide"
                                                >Input:</span
                                            >
                                            <span
                                                class="text-xs font-mono font-medium bg-slate-100 text-slate-800 px-1.5 py-0.5 rounded border-slate-200"
                                            >
                                                {{
                                                    log.input_value || "Kosong"
                                                }}
                                            </span>
                                        </div>
                                    </div>

                                    <div
                                        class="col-span-2 flex items-center gap-2"
                                    >
                                        <div
                                            class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 border border-slate-200"
                                        >
                                            <ScanBarcode
                                                v-if="log.method === 'scan'"
                                                class="w-4 h-4"
                                            />
                                            <Hash
                                                v-else-if="
                                                    log.method === 'keypad'
                                                "
                                                class="w-4 h-4"
                                            />
                                            <Monitor
                                                v-else-if="log.method === 'web'"
                                                class="w-4 h-4"
                                            />
                                            <Smartphone
                                                v-else
                                                class="w-4 h-4"
                                            />
                                        </div>
                                        <span
                                            class="text-sm font-semibold text-slate-700 capitalize"
                                            >{{
                                                log.method || "Otomatis"
                                            }}</span
                                        >
                                    </div>

                                    <div class="col-span-2 flex items-center">
                                        <span
                                            :class="[
                                                'px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 ring-1 ring-inset',
                                                formatEventData(log).badgeClass,
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    'w-1.5 h-1.5 rounded-full',
                                                    formatEventData(log)
                                                        .dotClass,
                                                ]"
                                            ></span>
                                            {{ formatEventData(log).badgeText }}
                                        </span>
                                    </div>

                                    <div class="col-span-1 flex justify-end">
                                        <button
                                            class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-white hover:text-slate-900 hover:shadow-sm border border-transparent hover:border-slate-200 transition-all"
                                        >
                                            <ChevronRight class="w-5 h-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div
                                v-if="recent_logs.length === 0"
                                class="text-center py-12"
                            >
                                <div
                                    class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-100"
                                >
                                    <Archive class="w-8 h-8 text-slate-300" />
                                </div>
                                <h3 class="text-slate-900 font-bold">
                                    Belum ada aktivitas
                                </h3>
                                <p class="text-slate-500 text-sm mt-1">
                                    Sistem sedang memantau log masuk.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AdminLayout>
</template>
