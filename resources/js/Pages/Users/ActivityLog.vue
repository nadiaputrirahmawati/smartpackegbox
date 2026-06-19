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
        <div class="bg-[#f8f9fc] min-h-screen font-sans">
            <div class="mx-auto space-y-8">
                <div>
                    <h1
                        class="text-3xl font-extrabold text-[#111827] mb-2 tracking-tight"
                    >
                        Log Aktivitas Sistem
                    </h1>
                    <p class="text-sm text-[#6b7280]">
                        Pantau riwayat keamanan dan operasional unit SmartBox
                        Anda secara real-time.
                    </p>
                </div>

                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
                >
                    <div
                        class="flex items-center gap-1 md:gap-2 bg-white p-1 rounded-full shadow-sm border border-gray-100 overflow-x-auto hide-scrollbar w-full md:w-auto"
                    >
                        <button
                            @click="setFilter('semua')"
                            :class="[
                                'px-5 py-2 text-sm font-semibold rounded-full whitespace-nowrap transition-all',
                                activeFilter === 'semua'
                                    ? 'bg-[#111827] text-white'
                                    : 'text-gray-500 hover:bg-gray-50',
                            ]"
                        >
                            Semua
                        </button>
                        <button
                            @click="setFilter('paket_masuk')"
                            :class="[
                                'px-4 py-2 text-sm font-medium rounded-full whitespace-nowrap transition-all',
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
                                'px-4 py-2 text-sm font-medium rounded-full whitespace-nowrap transition-all',
                                activeFilter === 'akses_buka'
                                    ? 'bg-[#e0f2fe] text-[#0369a1] font-bold'
                                    : 'text-gray-500 hover:bg-[#e0f2fe] hover:text-[#0369a1]',
                            ]"
                        >
                            Akses Buka
                        </button>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <input
                            type="date"
                            v-model="selectedDate"
                            class="w-full md:w-auto px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-full font-medium text-sm shadow-sm hover:border-gray-300 focus:ring-2 focus:ring-[#1e40af] outline-none cursor-pointer transition"
                        />

                        <button
                            v-if="selectedDate || activeFilter !== 'semua'"
                            @click="
                                () => {
                                    selectedDate = '';
                                    setFilter('semua');
                                }
                            "
                            class="flex items-center justify-center p-2 bg-red-50 text-red-500 rounded-full hover:bg-red-100 transition"
                            title="Reset Filter"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div
                        class="w-full grid grid-cols-1 lg:grid-cols-2 gap-4"
                    >
                        <div v-for="log in logs.data"
                        :key="log.id"
                            class="bg-white rounded-[1.5rem] p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 shadow-sm border border-gray-50 transition-all hover:shadow-md group"
                        >
                            <div
                                :class="[
                                    'w-20 h-20 shrink-0 rounded-2xl flex items-center justify-center overflow-hidden border-2 transition-transform group-hover:scale-105',
                                    log.is_error
                                        ? 'border-dashed border-red-300 bg-red-50'
                                        : 'border-transparent bg-[#111827]',
                                ]"
                            >
                                <img
                                    v-if="log.image_url && !log.is_error"
                                    :src="log.image_url"
                                    class="w-full h-full object-cover"
                                />
                                <AlertTriangle
                                    v-else-if="log.is_error"
                                    class="w-8 h-8 text-red-500 animate-pulse"
                                />
                                <CameraOff
                                    v-else
                                    class="w-6 h-6 text-gray-400"
                                />
                            </div>

                            <div
                                class="flex-1 w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
                            >
                                <div class="space-y-2.5 w-full sm:w-auto">
                                    <div class="flex items-center gap-3">
                                        <span
                                            :class="[
                                                log.badge.class,
                                                'px-3 py-1 rounded-md text-[10px] font-bold tracking-widest uppercase',
                                            ]"
                                        >
                                            {{ log.badge.text }}
                                        </span>
                                        <span
                                            class="text-xs text-gray-400 font-medium"
                                        >
                                            {{ log.timestamp }}
                                        </span>
                                    </div>

                                    <div>
                                        <h3
                                            class="text-lg font-bold text-[#111827]"
                                        >
                                            {{ log.title }}
                                        </h3>
                                        <p
                                            class="text-sm text-gray-500"
                                            v-html="log.subtitle"
                                        ></p>
                                    </div>

                                    <div
                                        class="flex flex-wrap items-center gap-2 pt-1"
                                    >
                                        <div
                                            :class="[
                                                'flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold',
                                                log.is_error
                                                    ? 'bg-red-50 text-red-600'
                                                    : 'bg-slate-100 text-slate-600',
                                            ]"
                                        >
                                            <User class="w-3.5 h-3.5" />
                                            <span>{{ log.actor }}</span>
                                        </div>

                                        <div
                                            class="flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold capitalize"
                                        >
                                            <ScanBarcode
                                                v-if="log.method === 'scan'"
                                                class="w-3.5 h-3.5"
                                            />
                                            <Hash
                                                v-else-if="
                                                    log.method === 'keypad'
                                                "
                                                class="w-3.5 h-3.5"
                                            />
                                            <Monitor
                                                v-else-if="log.method === 'web'"
                                                class="w-3.5 h-3.5"
                                            />
                                            <Settings
                                                v-else
                                                class="w-3.5 h-3.5"
                                            />
                                            <span>{{
                                                log.method || "Otomatis"
                                            }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="hidden lg:flex items-center justify-end w-full sm:w-auto mt-2 sm:mt-0 pt-4 sm:pt-0 border-t sm:border-t-0 border-gray-100"
                                >
                                    <div
                                        :class="[
                                            'flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-bold',
                                            log.is_error
                                                ? 'bg-red-50 text-red-600'
                                                : 'text-emerald-600',
                                        ]"
                                    >
                                        <XCircle
                                            v-if="log.is_error"
                                            class="w-10 h-10"
                                        />
                                        <CheckCircle2
                                            v-else
                                            class="w-10 h-10"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="logs.data.length === 0"
                        class="text-center py-16 bg-white rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center"
                    >
                        <div
                            class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4"
                        >
                            <Filter class="w-8 h-8 text-gray-400" />
                        </div>
                        <p class="text-gray-900 font-bold text-lg">
                            Tidak ada aktivitas ditemukan
                        </p>
                        <p class="text-gray-500 text-sm mt-1">
                            Coba sesuaikan tanggal atau ubah kategori filter
                            Anda.
                        </p>
                    </div>
                </div>

                <div
                    v-if="logs.data.length > 0"
                    class="flex flex-col md:flex-row justify-between items-center gap-4 bg-transparent pt-4"
                >
                    <p class="text-sm text-gray-500 font-medium">
                        Menampilkan
                        <span class="font-bold text-[#111827]">{{
                            logs.from
                        }}</span>
                        -
                        <span class="font-bold text-[#111827]">{{
                            logs.to
                        }}</span>
                        dari
                        <span class="font-bold text-[#111827]">{{
                            logs.total
                        }}</span>
                        riwayat
                    </p>
                    <div class="flex gap-2">
                        <template
                            v-for="(link, index) in logs.links"
                            :key="index"
                        >
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                v-html="
                                    link.label
                                        .replace('Previous', '')
                                        .replace('Next', '')
                                "
                                :class="[
                                    'px-4 py-2 min-w-[1.5rem] flex items-center justify-center rounded-full text-sm font-bold transition',
                                    link.active
                                        ? 'bg-[#111827] text-white shadow-md'
                                        : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50',
                                ]"
                            />
                            <span
                                v-else
                                class="px-4 py-2 min-w-[1.5rem] flex items-center justify-center rounded-full text-sm font-bold bg-transparent text-gray-400 cursor-not-allowed"
                            ></span>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
