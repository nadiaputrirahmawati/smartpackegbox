<script setup>
import { ref, watch } from "vue";
import { router, Link } from "@inertiajs/vue3";
import UserLayout from "@/Layouts/UserLayout.vue";
import {
    Filter,
    ChevronRight,
    AlertTriangle,
    CameraOff,
    User,
    ScanBarcode,
    Hash,
    Monitor,
    Settings,
    CheckCircle2,
    XCircle,
} from "@lucide/vue";

// Menerima data logs dan status filter yang sedang aktif dari Controller
const props = defineProps({
    logs: Object,
    filters: Object,
});

// Setup Variabel Reaktif berdasarkan URL (agar saat di-refresh filter tidak hilang)
const activeFilter = ref(props.filters?.filter || "semua");
const selectedDate = ref(props.filters?.date || "");

// Fungsi untuk menembak Request ke Laravel
const fetchData = () => {
    let queryParams = {};

    if (activeFilter.value !== "semua") {
        queryParams.filter = activeFilter.value;
    }
    if (selectedDate.value) {
        queryParams.date = selectedDate.value;
    }

    // --- GANTI BARIS INI ---
    // Ubah sesuai dengan URL di web.php Anda.
    // Contoh: Jika di web.php Route::get('/my-logs', ...), maka tulis '/my-logs'
    const url = "/user/history/log"; // Ganti '/activity-logs' dengan URL asli Anda

    router.get(url, queryParams, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

// Fungsi Klik Tombol Kategori
const setFilter = (value) => {
    activeFilter.value = value;
    fetchData();
};

// Pantau jika Tanggal diubah dari kalender, otomatis langsung filter
watch(selectedDate, (newDate) => {
    fetchData();
});
</script>

<template>
    <UserLayout>
        <div class="bg-[#f8f9fc] min-h-screen font-sans ">
            <div class="max-w-7xl mx-auto space-y-5 md:space-y-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#111827] mb-1.5 tracking-tight">
                        Log Aktivitas Sistem
                    </h1>
                    <p class="text-xs sm:text-sm text-[#6b7280] leading-relaxed">
                        Pantau riwayat keamanan dan operasional unit SmartBox Anda secara real-time.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 md:gap-4">
                    <div class="flex items-center gap-1.5 bg-white p-1 rounded-2xl md:rounded-full shadow-sm border border-gray-100 overflow-x-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden w-full md:w-auto">
                        <button
                            @click="setFilter('semua')"
                            :class="[
                                'px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold rounded-full whitespace-nowrap transition-all flex-1 md:flex-none text-center',
                                activeFilter === 'semua'
                                    ? 'bg-[#111827] text-white shadow-sm'
                                    : 'text-gray-500 hover:bg-gray-50',
                            ]"
                        >
                            Semua
                        </button>
                        <button
                            @click="setFilter('paket_masuk')"
                            :class="[
                                'px-4 py-2 text-xs sm:text-sm font-medium rounded-full whitespace-nowrap transition-all flex-1 md:flex-none text-center',
                                activeFilter === 'paket_masuk'
                                    ? 'bg-[#dcecfe] text-[#1e5eb3] font-bold'
                                    : 'text-gray-500 hover:bg-[#dcecfe] hover:text-[#1e5eb3]',
                            ]"
                        >
                            Paket Masuk
                        </button>
                        <button
                            @click="setFilter('akses_buka')"
                            :class="[
                                'px-4 py-2 text-xs sm:text-sm font-medium rounded-full whitespace-nowrap transition-all flex-1 md:flex-none text-center',
                                activeFilter === 'akses_buka'
                                    ? 'bg-[#e0f2fe] text-[#0369a1] font-bold'
                                    : 'text-gray-500 hover:bg-[#e0f2fe] hover:text-[#0369a1]',
                            ]"
                        >
                            Akses Buka
                        </button>
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <input
                            type="date"
                            v-model="selectedDate"
                            class="w-full md:w-auto px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-full font-medium text-xs sm:text-sm shadow-sm hover:border-gray-300 focus:ring-2 focus:ring-[#1e40af] outline-none cursor-pointer transition"
                        />

                        <button
                            v-if="selectedDate || activeFilter !== 'semua'"
                            @click="
                                () => {
                                    selectedDate = '';
                                    setFilter('semua');
                                }
                            "
                            class="flex items-center justify-center p-2.5 bg-red-50 text-red-500 rounded-full hover:bg-red-100 transition shrink-0"
                            title="Reset Filter"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div 
                            v-for="log in logs.data"
                            :key="log.id"
                            class="bg-white rounded-[1.25rem] sm:rounded-[1.5rem] p-4 flex flex-row items-center gap-4 shadow-sm border border-gray-100/50 transition-all hover:shadow-md group relative"
                        >
                            <div
                                :class="[
                                    'w-16 h-16 sm:w-20 sm:h-20 shrink-0 rounded-xl sm:rounded-2xl flex items-center justify-center overflow-hidden border-2 transition-transform group-hover:scale-105',
                                    log.is_error
                                        ? 'border-dashed border-red-300 bg-red-50'
                                        : 'border-transparent bg-[#111827]',
                                ]"
                            >
                                <img
                                    v-if="log.image_url && !log.is_error"
                                    :src="log.image_url"
                                    class="w-full h-full object-cover"
                                    alt="Log Event"
                                />
                                <AlertTriangle
                                    v-else-if="log.is_error"
                                    class="w-5 h-5 sm:w-8 sm:h-8 text-red-500 animate-pulse"
                                />
                                <CameraOff
                                    v-else
                                    class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400"
                                />
                            </div>

                            <div class="flex-1 min-w-0 flex flex-row justify-between items-center gap-2">
                                <div class="space-y-1 flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                        <span
                                            :class="[
                                                log.badge.class,
                                                'px-2 py-0.5 rounded text-[8px] sm:text-[9px] font-bold tracking-wider uppercase whitespace-nowrap',
                                            ]"
                                        >
                                            {{ log.badge.text }}
                                        </span>
                                        <span class="text-[10px] sm:text-xs text-gray-400 font-medium whitespace-nowrap">
                                            {{ log.timestamp }}
                                        </span>
                                    </div>

                                    <div>
                                        <h3 class="text-sm sm:text-base font-bold text-[#111827] truncate">
                                            {{ log.title }}
                                        </h3>
                                        <p class="text-xs text-gray-500 line-clamp-1 sm:line-clamp-2" v-html="log.subtitle"></p>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                                        <div :class="[
                                            'flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] sm:text-xs font-semibold',
                                            log.is_error ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-600'
                                        ]">
                                            <User class="w-3 h-3 shrink-0" />
                                            <span class="truncate max-w-[70px] sm:max-w-[120px]">{{ log.actor }}</span>
                                        </div>

                                        <div class="flex items-center gap-1 px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] sm:text-xs font-semibold capitalize">
                                            <ScanBarcode v-if="log.method === 'scan'" class="w-3 h-3 shrink-0" />
                                            <Hash v-else-if="log.method === 'keypad'" class="w-3 h-3 shrink-0" />
                                            <Monitor v-else-if="log.method === 'web'" class="w-3 h-3 shrink-0" />
                                            <Settings v-else class="w-3 h-3 shrink-0" />
                                            <span class="truncate max-w-[60px] sm:max-w-none">{{ log.method || "Otomatis" }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 ml-1">
                                    <div :class="[
                                        'flex items-center justify-center p-1 sm:p-2 rounded-xl text-sm font-bold',
                                        log.is_error ? 'text-red-500' : 'text-emerald-500'
                                    ]">
                                        <XCircle v-if="log.is_error" class="w-6 h-6 sm:w-9 sm:h-9" />
                                        <CheckCircle2 v-else class="w-6 h-6 sm:w-9 sm:h-9" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="logs.data.length === 0"
                        class="text-center py-12 bg-white rounded-[1.5rem] border border-gray-100 shadow-sm flex flex-col items-center px-4"
                    >
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <Filter class="w-6 h-6 text-gray-400" />
                        </div>
                        <p class="text-gray-900 font-bold text-base">
                            Tidak ada aktivitas ditemukan
                        </p>
                        <p class="text-gray-500 text-xs mt-1 max-w-xs mx-auto">
                            Coba sesuaikan tanggal atau ubah kategori filter Anda.
                        </p>
                    </div>
                </div>

                <div
                    v-if="logs.data.length > 0"
                    class="flex flex-col md:flex-row justify-between items-center gap-4 bg-transparent pt-2"
                >
                    <p class="text-xs sm:text-sm text-gray-500 font-medium order-2 md:order-1">
                        Menampilkan
                        <span class="font-bold text-[#111827]">{{ logs.from }}</span>
                        -
                        <span class="font-bold text-[#111827]">{{ logs.to }}</span>
                        dari
                        <span class="font-bold text-[#111827]">{{ logs.total }}</span>
                    </p>
                    <div class="flex gap-1 order-1 md:order-2 flex-wrap justify-center">
                        <template v-for="(link, index) in logs.links" :key="index">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label.replace('Previous', '').replace('Next', '')"
                                :class="[
                                    'px-3 py-1.5 min-w-[1.75rem] h-8 flex items-center justify-center rounded-full text-xs font-bold transition',
                                    link.active
                                        ? 'bg-[#111827] text-white'
                                        : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50',
                                ]"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
