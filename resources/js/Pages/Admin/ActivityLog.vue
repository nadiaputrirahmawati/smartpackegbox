<script setup>
import { ref, watch } from "vue";
import { router, Link } from "@inertiajs/vue3";
import UserLayout from "@/Layouts/UserLayout.vue";
import { 
  Download, Package, Search, CameraOff, CalendarDays,
  ScanBarcode, Hash, Monitor, Smartphone, XCircle, FileText, X
} from "@lucide/vue";

const props = defineProps({
  logs: Object, 
  stats: Object,
  filters: Object
});

// --- 1. STATE UNTUK FILTER PENCARIAN & TANGGAL ---
const searchQuery = ref(props.filters?.search || '');
const dateFilter = ref(props.filters?.date || ''); // Ini state untuk filter tanggal di tabel

// --- 2. FUNGSI DEBOUNCE UNTUK FILTER TABEL ---
let searchTimeout = null;
watch([searchQuery, dateFilter], ([newSearch, newDate]) => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    router.get('/admin/activity-log', { search: newSearch, date: newDate }, {
      preserveState: true,
      replace: true
    });
  }, 300); 
});

// --- 3. FUNGSI RESET TANGGAL PADA TABEL ---
const resetDate = () => {
    dateFilter.value = '';
};

// --- 4. STATE UNTUK MODAL EXPORT PDF ---
const showExportModal = ref(false);
const exportType = ref('all'); // Pilihan: 'all', 'single', 'range'
const exportSingleDate = ref('');
const exportStartDate = ref('');
const exportEndDate = ref('');

// --- 5. FUNGSI EKSEKUSI DOWNLOAD PDF ---
const executeDownload = () => {
    const params = new URLSearchParams();
    
    // Bawa filter pencarian jika Admin sedang mencari sesuatu
    if (searchQuery.value) params.append('search', searchQuery.value);
    
    // Bawa parameter tipe export
    params.append('export_type', exportType.value);
    
    // Validasi & masukkan tanggal sesuai tipe export yang dipilih
    if (exportType.value === 'single' && exportSingleDate.value) {
        params.append('date', exportSingleDate.value);
    } 
    else if (exportType.value === 'range' && exportStartDate.value && exportEndDate.value) {
        params.append('start_date', exportStartDate.value);
        params.append('end_date', exportEndDate.value);
    }

    // Tutup modal dan buka tab baru untuk men-download PDF
    showExportModal.value = false;
    window.open(`/admin/activity-logs/export-pdf?${params.toString()}`, '_blank');
};
</script>

<template>
  <UserLayout>
    <div class="p-6 md:p-8 bg-[#f8f9fc] min-h-screen font-sans">
      <div class="max-w-7xl mx-auto space-y-8">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <div>
            <h1 class="text-3xl font-extrabold text-[#0f172a] mb-1 tracking-tight">Log Aktivitas</h1>
            <p class="text-sm font-medium text-slate-500">Riwayat sistem IoT dan manajemen paket secara real-time.</p>
          </div>
          <div class="flex items-center gap-3">
            <button @click="showExportModal = true" class="flex items-center gap-2 px-5 py-2.5 bg-[#0f172a] hover:bg-slate-800 text-white rounded-full font-bold text-sm transition shadow-md">
              <Download class="w-4 h-4" /> Unduh PDF
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
          
          <div class="lg:col-span-1 bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 flex flex-col justify-between transition-transform hover:-translate-y-1">
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 text-blue-600 border border-blue-100/50">
              <Package class="w-6 h-6" />
            </div>
            <div>
              <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Paket</p>
              <h2 class="text-4xl font-black text-slate-900 mb-3">{{ stats.total_packages }}</h2>
            </div>
          </div>

          <div class="lg:col-span-3 bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex flex-col justify-center">
            <div class="flex justify-between items-end mb-5">
              <div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Pencarian & Filter Data</h3>
                <p class="text-sm text-slate-500">Temukan riwayat spesifik berdasarkan resi, PIN, atau tanggal kejadian.</p>
              </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <Search class="h-5 w-5 text-slate-400" />
                </div>
                <input 
                  v-model="searchQuery"
                  type="text" 
                  class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all sm:text-sm font-medium" 
                  placeholder="Cari Nomor Resi atau PIN..."
                >
              </div>

              <div class="relative flex items-center shrink-0">
                <input 
                  v-model="dateFilter"
                  type="date" 
                  class="block w-full sm:w-[200px] pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all sm:text-sm font-medium" 
                >
                <CalendarDays class="absolute left-3.5 h-5 w-5 text-slate-400 pointer-events-none" />
                
                <button v-if="dateFilter" @click="resetDate" class="absolute -top-2 -right-2 bg-rose-100 text-rose-500 hover:bg-rose-200 p-1 rounded-full shadow-sm transition" title="Hapus Filter Tanggal">
                  <XCircle class="w-4 h-4" />
                </button>
              </div>
            </div>

          </div>
        </div>

        <div class="bg-white rounded-[2rem] p-2 md:p-6 shadow-sm border border-slate-100 overflow-hidden flex flex-col">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
              <thead>
                <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                  <th class="py-4 px-6">Bukti</th>
                  <th class="py-4 px-6">Aktivitas & Waktu</th>
                  <th class="py-4 px-6">Aktor & Data Input</th>
                  <th class="py-4 px-6">Metode</th>
                  <th class="py-4 px-6">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50">
                <tr v-for="log in logs.data" :key="log.id" class="hover:bg-slate-50/50 transition-colors">
                  
                  <td class="py-4 px-6">
                    <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0 shadow-sm">
                      <img v-if="log.image_url" :src="log.image_url" class="w-full h-full object-cover">
                      <CameraOff v-else class="w-5 h-5 text-slate-300" />
                    </div>
                  </td>

                  <td class="py-4 px-6">
                    <p class="text-sm font-bold text-slate-900 mb-0.5">{{ log.title }}</p>
                    <div class="flex flex-col">
                      <span class="text-xs font-semibold text-slate-500">{{ log.date }}</span>
                      <span class="text-[11px] text-slate-400">{{ log.time }} WIB</span>
                    </div>
                  </td>

                  <td class="py-4 px-6">
                    <p class="text-sm font-bold text-slate-700 mb-1 capitalize">{{ log.actor }}</p>
                    <span class="inline-block text-xs font-mono font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200">
                      {{ log.input_data }}
                    </span>
                  </td>

                  <td class="py-4 px-6">
                    <div class="flex items-center gap-2 text-slate-600">
                      <ScanBarcode v-if="log.method === 'scan'" class="w-4 h-4" />
                      <Hash v-else-if="log.method === 'keypad'" class="w-4 h-4" />
                      <Monitor v-else-if="log.method === 'web'" class="w-4 h-4" />
                      <Smartphone v-else class="w-4 h-4" />
                      <span class="text-sm font-semibold capitalize">{{ log.method }}</span>
                    </div>
                  </td>

                  <td class="py-4 px-6">
                    <span :class="['px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5',
                                  log.status === 'Gagal' ? 'bg-rose-50 text-rose-600' : 'bg-blue-50 text-blue-600']">
                      <span :class="['w-1.5 h-1.5 rounded-full', log.status === 'Gagal' ? 'bg-rose-500' : 'bg-blue-500']"></span>
                      {{ log.status }}
                    </span>
                  </td>

                </tr>
                
                <tr v-if="logs.data.length === 0">
                  <td colspan="5" class="py-12 text-center">
                    <Search class="w-8 h-8 text-slate-300 mx-auto mb-3" />
                    <p class="text-slate-500 font-medium">Tidak ada riwayat aktivitas yang ditemukan pada filter ini.</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="logs.data.length > 0" class="mt-6 pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 px-2">
            <p class="text-sm font-medium text-slate-500">
              Menampilkan <span class="font-bold text-slate-900">{{ logs.from }}</span>-{{ logs.to }} dari {{ logs.total }} riwayat
            </p>
            <div class="flex gap-1.5">
              <template v-for="(link, index) in logs.links" :key="index">
                <Link 
                  v-if="link.url"
                  :href="link.url"
                  v-html="link.label.replace('&laquo; Previous', '<').replace('Next &raquo;', '>')"
                  :class="[
                    'min-w-[2.5rem] h-10 px-2 flex items-center justify-center rounded-xl text-sm font-bold transition-all duration-200',
                    link.active 
                      ? 'bg-[#0f172a] text-white shadow-md' 
                      : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300'
                  ]"
                />
                <span 
                  v-else 
                  v-html="link.label.replace('&laquo; Previous', '<').replace('Next &raquo;', '>')"
                  class="min-w-[2.5rem] h-10 px-2 flex items-center justify-center rounded-xl text-sm font-bold bg-slate-50 text-slate-300 border border-slate-100 cursor-not-allowed"
                ></span>
              </template>
            </div>
          </div>
        </div>

        <div v-if="showExportModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
          <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                  <FileText class="w-5 h-5" />
                </div>
                <div>
                  <h3 class="font-bold text-slate-900">Export Laporan PDF</h3>
                  <p class="text-xs text-slate-500 font-medium">Pilih rentang data yang ingin diunduh.</p>
                </div>
              </div>
              <button @click="showExportModal = false" class="text-slate-400 hover:text-slate-700 bg-white p-2 rounded-full shadow-sm border border-slate-100 transition">
                <X class="w-4 h-4" />
              </button>
            </div>

            <div class="p-6 space-y-5">
              <div class="space-y-3">
                <label class="text-sm font-bold text-slate-700">Pilih Cakupan Waktu</label>
                <div class="grid grid-cols-1 gap-2">
                  <label :class="['flex items-center p-3 border rounded-xl cursor-pointer transition', exportType === 'all' ? 'border-blue-500 bg-blue-50/50 ring-1 ring-blue-500/50' : 'border-slate-200 hover:bg-slate-50']">
                    <input type="radio" v-model="exportType" value="all" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-3 font-semibold text-slate-700 text-sm">Semua Waktu</span>
                  </label>

                  <label :class="['flex items-center p-3 border rounded-xl cursor-pointer transition', exportType === 'single' ? 'border-blue-500 bg-blue-50/50 ring-1 ring-blue-500/50' : 'border-slate-200 hover:bg-slate-50']">
                    <input type="radio" v-model="exportType" value="single" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-3 font-semibold text-slate-700 text-sm">Satu Hari Spesifik</span>
                  </label>

                  <label :class="['flex items-center p-3 border rounded-xl cursor-pointer transition', exportType === 'range' ? 'border-blue-500 bg-blue-50/50 ring-1 ring-blue-500/50' : 'border-slate-200 hover:bg-slate-50']">
                    <input type="radio" v-model="exportType" value="range" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-3 font-semibold text-slate-700 text-sm">Rentang Tanggal</span>
                  </label>
                </div>
              </div>

              <div v-if="exportType === 'single'" class="space-y-2 animate-in slide-in-from-top-2">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilih Tanggal</label>
                <input v-model="exportSingleDate" type="date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500">
              </div>

              <div v-if="exportType === 'range'" class="grid grid-cols-2 gap-4 animate-in slide-in-from-top-2">
                <div class="space-y-2">
                  <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Dari Tanggal</label>
                  <input v-model="exportStartDate" type="date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="space-y-2">
                  <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sampai Tanggal</label>
                  <input v-model="exportEndDate" type="date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500">
                </div>
              </div>

            </div>

            <div class="p-6 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50">
              <button @click="showExportModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Batal</button>
              
              <button 
                @click="executeDownload"
                :disabled="(exportType === 'single' && !exportSingleDate) || (exportType === 'range' && (!exportStartDate || !exportEndDate))"
                class="flex items-center gap-2 px-6 py-2.5 bg-[#0f172a] hover:bg-slate-800 disabled:bg-slate-300 disabled:cursor-not-allowed text-white rounded-xl font-bold text-sm transition shadow-md"
              >
                <Download class="w-4 h-4" /> Proses Unduhan
              </button>
            </div>

          </div>
        </div>
        </div>
    </div>
  </UserLayout>
</template>