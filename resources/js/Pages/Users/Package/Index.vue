<template>
    <UserLayout title="Daftar Paket">
        <!-- HEADER & TOOLBAR -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-6"
        >
            <!-- Text Header -->
            <div class="w-full md:w-auto">
                <h1
                    class="text-3xl md:text-4xl font-extrabold text-primary tracking-tight mb-1"
                >
                    Daftar Paket
                </h1>
                <p class="text-secondary-200 font-medium text-sm md:text-md">
                    Kelola dan pantau status pengiriman paket ke SmartBox Anda.
                </p>
            </div>

            <!-- Toolbar Action -->
            <div
                class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto"
            >
                <!-- SEARCH BAR (Full Width di Mobile, Fixed di Desktop) -->
                <div class="relative w-full sm:w-auto md:w-64">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <Search class="h-4 w-4 text-gray-400" />
                    </div>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari no. resi..."
                        class="block w-full pl-9 pr-3 py-2.5 bg-tertiary-700 shadow-md rounded-full text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                    />
                </div>

                <!-- TOMBOL FILTER & TAMBAH DATA (Bersebelahan di Mobile) -->
                <div class="flex w-full sm:w-auto gap-3">
                    <!-- FILTER TOGGLE BUTTON -->
                    <button
                        @click="showFilters = !showFilters"
                        :class="[
                            'flex-1 sm:flex-none flex items-center shadow-md justify-center gap-2 px-5 py-2.5 rounded-full font-bold text-sm transition-colors whitespace-nowrap',
                            showFilters || hasActiveFilters
                                ? 'bg-primary text-white shadow-md shadow-primary/20'
                                : 'bg-gray-100 text-primary hover:bg-gray-200',
                        ]"
                    >
                        <Filter class="w-4 h-4" />
                        <span>Filter</span>
                    </button>

                    <!-- TOMBOL TAMBAH RESI -->
                    <Link
                        href="/user/packages/create"
                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-primary hover:bg-primary-100 text-white px-5 py-2.5 rounded-full font-bold text-sm transition-all shadow-md shadow-primary/20 whitespace-nowrap"
                    >
                        <Plus class="w-4 h-4" />
                        <span>Tambah Data</span>
                        <!-- Teks diperpendek agar muat sebelahan di HP -->
                    </Link>
                </div>
            </div>
        </div>
        <!-- FILTER DROPDOWNS -->
        <div
            v-show="showFilters"
            class="bg-white p-5 rounded-2xl shadow-sm border border-secondary-600 mb-6 flex flex-col md:flex-row gap-4 animate-in slide-in-from-top-2 fade-in duration-200"
        >
            <div class="flex-1">
                <label
                    class="block text-xs font-bold text-gray-600 uppercase mb-2"
                    >Metode Pembayaran</label
                >
                <select
                    v-model="paymentType"
                    class="w-full bg-gray-50 border border-gray-200 text-gray-700 rounded-xl focus:ring-primary focus:border-primary block p-2.5 text-sm"
                >
                    <option value="">Semua Metode</option>
                    <option value="prepaid">Prepaid (Non-COD)</option>
                    <option value="cod">COD</option>
                </select>
            </div>
            <div class="flex-1">
                <label
                    class="block text-xs font-bold text-gray-600 uppercase mb-2"
                    >Status Paket</label
                >
                <select
                    v-model="packageStatus"
                    class="w-full bg-gray-50 border border-gray-200 text-gray-700 rounded-xl focus:ring-primary focus:border-primary block p-2.5 text-sm"
                >
                    <option value="">Semua Status</option>
                    <option value="waiting">Menunggu (Belum Tiba)</option>
                    <option value="arrived">Tiba di SmartBox</option>
                </select>
            </div>
            <div class="flex items-end">
                <button
                    @click="resetFilters"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 rounded-xl transition"
                >
                    <X class="w-4 h-4" /> Reset
                </button>
            </div>
        </div>

        <!-- TABLE SECTION -->
        <div
            class="lg:bg-white bg-transparent rounded-[2rem] lg:shadow-[0_4px_20px_rgb(0,0,0,0.03)] lg:border lg:border-secondary-600 overflow-hidden flex flex-col"
        >
            <!-- TABLE VIEW (HANYA MUNCUL DI TABLET & LAPTOP/PC) -->
            <div class="hidden md:block overflow-x-auto">
                <table
                    class="w-full text-left whitespace-nowrap border-collapse"
                >
                    <!-- ... THEAD TETAP SAMA SEPERTI SEBELUMNYA ... -->
                    <thead>
                        <tr
                            class="text-[13px] font-extrabold text-gray-800 uppercase tracking-widest border-b border-secondary-600/60"
                        >
                            <th class="px-8 py-6 font-extrabold">No. Resi</th>
                            <th class="px-6 py-6 font-extrabold">Tipe</th>
                            <th class="px-6 py-6 font-extrabold">Nominal</th>
                            <th class="px-6 py-6 font-extrabold">
                                Status Pembayaran
                            </th>
                            <th class="px-6 py-6 font-extrabold">
                                Status Paket
                            </th>
                            <th class="px-6 py-6 font-extrabold">Waktu Tiba</th>
                            <th class="px-8 py-6 font-extrabold text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-secondary-600/40">
                        <tr
                            v-for="pkg in packages.data || packages"
                            :key="pkg.id"
                            class="hover:bg-[#fcfdff] transition-colors group"
                        >
                            <!-- ... ISI TD TABEL SAMA PERSIS SEPERTI SEBELUMNYA ... -->
                            <td class="px-8 py-6">
                                <span
                                    class="font-extrabold text-primary text-sm"
                                    >{{ pkg.tracking_number }}</span
                                >
                            </td>
                            <td class="px-6 py-6">
                                <span
                                    v-if="pkg.payment_type === 'prepaid'"
                                    class="bg-[#eef2f6] text-[#3c5f83] px-3 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase"
                                    >Prepaid</span
                                >
                                <span
                                    v-if="pkg.payment_type === 'cod'"
                                    class="bg-red-50 text-red-600 px-3 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase"
                                    >COD</span
                                >
                            </td>
                            <td class="px-6 py-6">
                                <span
                                    class="font-bold text-primary text-sm"
                                    v-if="pkg.payment_type === 'prepaid'"
                                    >Non-COD</span
                                >
                                <span
                                    class="font-bold text-primary text-sm"
                                    v-if="pkg.payment_type === 'cod'"
                                    >{{ formatRupiah(pkg.amount) }}</span
                                >
                            </td>
                            <td class="px-6 py-6">
                                <div
                                    v-if="pkg.payment_type === 'prepaid'"
                                    class="inline-flex items-center gap-2 bg-[#e8f2ff] text-blue-700 px-3 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase"
                                >
                                    <div
                                        class="w-1.5 h-1.5 rounded-full bg-blue-500"
                                    ></div>
                                    Paket Non-COD
                                </div>
                                <div v-if="pkg.payment_type === 'cod'">
                                    <div
                                        v-if="pkg.payment_status === 'taken'"
                                        class="inline-flex items-center gap-2 bg-[#e8f2ff] text-blue-700 px-3 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase"
                                    >
                                        <div
                                            class="w-1.5 h-1.5 rounded-full bg-blue-500"
                                        ></div>
                                        Telah Diambil
                                    </div>
                                    <div
                                        v-if="pkg.payment_status === 'pending'"
                                        class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase"
                                    >
                                        <div
                                            class="w-1.5 h-1.5 rounded-full bg-gray-400"
                                        ></div>
                                        Pending
                                    </div>
                                    <div
                                        v-if="
                                            pkg.payment_status === 'deposited'
                                        "
                                        class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase"
                                    >
                                        <div
                                            class="w-1.5 h-1.5 rounded-full bg-gray-400"
                                        ></div>
                                        Uang Tersimpan
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <span
                                    v-if="pkg.package_status === 'arrived'"
                                    class="bg-blue-100 text-blue-600 px-3 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase"
                                    >Sampai</span
                                >
                                <span
                                    v-if="pkg.package_status === 'waiting'"
                                    class="bg-[#eef2f6] text-[#577a9f] px-3 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase"
                                    >Menunggu</span
                                >
                                <div
                                    v-if="pkg.package_status === 'Diambil'"
                                    class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase shadow-sm"
                                >
                                    <Check class="w-3 h-3 text-gray-500" />
                                    Diambil
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <span
                                    class="bg-yellow-100 text-center text-yellow-600 px-3 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase"
                                    v-if="pkg.package_status === 'waiting'"
                                    >Belum Tiba</span
                                >
                                <div v-if="pkg.package_status === 'arrived'">
                                    <p
                                        class="text-sm font-bold text-primary mb-0.5"
                                    >
                                        {{ pkg.arrived_at }}
                                    </p>
                                    <p
                                        class="text-[10px] text-gray-400 font-medium"
                                    >
                                        {{ pkg.time_sub }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div
                                    class="flex items-center justify-center gap-2"
                                >
                                    <Link
                                        :href="`/user/packages/${pkg.id}`"
                                        class="bg-green-200 hover:bg-emerald-500 flex text-green-800 items-center justify-center space-x-2 px-2 py-1 rounded-md text-xs font-semibold hover:text-white transition-colors"
                                        title="Pembayaran"
                                        :hidden="
                                            pkg.package_status === 'arrived'
                                        "
                                        ><Banknote class="w-4 h-4" /><span
                                            >Pembayaran</span
                                        ></Link
                                    >
                                    <Link
                                        :href="`/user/packages/${pkg.id}/edit`"
                                        class="bg-orange-200 hover:bg-orange-800 font-medium flex items-center justify-center space-x-2 px-2 py-1 rounded-md font-semibold text-xs text-orange-600 hover:text-white transition-colors"
                                        title="Edit Paket"
                                        :hidden="
                                            pkg.package_status === 'arrived'
                                        "
                                        ><SquarePen class="w-3 h-3" /><span
                                            >Edit Paket</span
                                        ></Link
                                    >
                                    <Link
                                        :href="`/user/packages/${pkg.id}/arrive`"
                                        class="bg-primary hover:bg-primary-100 flex items-center justify-center space-x-2 px-4 py-2 rounded-xl text-xs font-semibold text-secondary-600 hover:text-white transition-colors"
                                        title="Detail Pesanan"
                                        :hidden="
                                            pkg.package_status === 'waiting'
                                        "
                                        ><Banknote class="w-4 h-4" /><span
                                            >Detail Paket</span
                                        ></Link
                                    >
                                    <button
                                        @click="confirmDelete(pkg.id)"
                                        class="bg-red-200 hover:bg-red-500 flex text-red-800 items-center justify-center space-x-1 px-2 py-1 rounded-md text-xs font-semibold hover:text-white transition-colors"
                                        :hidden="
                                            pkg.package_status === 'waiting' &&
                                            pkg.total_received > 0
                                        "
                                    >
                                        <Trash2 class="w-3 h-3" />
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- CARD VIEW (HANYA MUNCUL DI MOBILE / HP) -->
            <div class="block md:hidden flex flex-col gap-4 p-4 bg-gray-50/50">
                <div
                    v-for="pkg in packages.data || packages"
                    :key="pkg.id"
                    class="bg-white p-5 rounded-2xl shadow-sm border border-secondary-600/50 flex flex-col gap-4"
                >
                    <!-- Card Header: Resi & Status Paket -->
                    <div class="flex justify-between items-start">
                        <div>
                            <p
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1"
                            >
                                Nomor Resi
                            </p>
                            <h3
                                class="font-extrabold text-primary text-lg leading-none"
                            >
                                {{ pkg.tracking_number }}
                            </h3>
                        </div>
                        <div>
                            <span
                                v-if="pkg.package_status === 'arrived'"
                                class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-[10px] font-extrabold tracking-widest uppercase"
                                >Telah Tiba</span
                            >
                            <span
                                v-if="pkg.package_status === 'waiting'"
                                class="bg-[#eef2f6] text-[#577a9f] px-3 py-1 rounded-full text-[10px] font-extrabold tracking-widest uppercase"
                                >Dalam Perjalanan</span
                            >
                        </div>
                    </div>

                    <!-- Card Body: Grid Info -->
                    <div class="flex px-3 pt-2 space-x-3">
                        <div
                            class="flex items-center justify-center bg-tertiary-700 p-2 rounded-full"
                        >
                            <Truck class="w-6 h-6" />
                        </div>
                        <div>
                            <p
                                class="text-[11px] font-bold text-gray-600 tracking-widest"
                            >
                                Waktu Kedatangan
                            </p>
                            <span
                                class="text-sm font-semibold text-yellow-600 uppercase"
                                v-if="pkg.package_status === 'waiting'"
                                >-</span
                            >
                            <div v-if="pkg.package_status === 'arrived'">
                                <p class="text-sm font-bold text-primary">
                                    {{ pkg.arrived_at }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="grid grid-cols-2 gap-4 bg-gray-50 p-3 rounded-xl"
                    >
                        <!-- Info 1: Tipe & Nominal -->
                        <div>
                            <p
                                class="text-xs font-bold text-gray-600 uppercase tracking-widest mb-1"
                            >
                                Pembayaran
                            </p>
                            <div class="flex items-center gap-1.5 mb-1">
                                <span
                                    v-if="pkg.payment_type === 'prepaid'"
                                    class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-[11px] font-bold uppercase"
                                    >Non - COD</span
                                >
                                <span
                                    v-if="pkg.payment_type === 'cod'"
                                    class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-[11px] font-bold uppercase"
                                    >COD</span
                                >
                            </div>
                        </div>

                        <!-- Info 2: Waktu Tiba -->
                        <div :hidden="pkg.payment_type === 'prepaid'">
                            <p
                                class="text-xs font-bold text-gray-600 uppercase tracking-widest mb-1"
                            >
                                Nominal
                            </p>
                            <p class="font-bold text-sm text-primary">
                                {{ formatRupiah(pkg.amount) }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Footer: Aksi -->
                    <div
                        class="flex flex-wrap gap-2 pt-2 border-t border-gray-100"
                    >
                        <Link
                            :href="`/user/packages/${pkg.id}`"
                            class="flex-1 bg-green-100 hover:bg-green-200 text-green-700 flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-xs font-bold transition"
                            :hidden="pkg.package_status === 'arrived'"
                            ><Banknote class="w-3.5 h-3.5" /> Pembayaran</Link
                        >
                        <Link
                            :href="`/user/packages/${pkg.id}/edit`"
                            class="flex-1 bg-orange-100 hover:bg-orange-200 text-orange-700 flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-xs font-bold transition"
                            :hidden="pkg.package_status === 'arrived'"
                            ><SquarePen class="w-3.5 h-3.5" /> Edit</Link
                        >
                        <Link
                            :href="`/user/packages/${pkg.id}/arrive`"
                            class="flex-1 bg-primary hover:bg-primary-100 text-white flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-xs font-bold transition"
                            :hidden="pkg.package_status === 'waiting'"
                            ><Banknote class="w-3.5 h-3.5" /> Detail Paket</Link
                        >
                        <button
                            @click="confirmDelete(pkg.id)"
                            class="bg-red-100 hover:bg-red-200 text-red-600 flex items-center justify-center p-2.5 rounded-xl transition"
                            :hidden="
                                pkg.package_status === 'waiting' &&
                                pkg.total_received > 0
                            "
                        >
                            <Trash2 class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <!-- Empty State Mobile -->
                <div
                    v-if="(packages.data || packages).length === 0"
                    class="bg-white p-8 rounded-2xl text-center shadow-sm border border-gray-100"
                >
                    <Search class="w-8 h-8 text-gray-300 mx-auto mb-2" />
                    <p class="text-secondary-200 text-sm">
                        Tidak ada paket yang ditemukan.
                    </p>
                </div>
            </div>

            <!-- PAGINATION -->
            <div
                class="bg-[#f8faff] border-t border-secondary-600 px-8 py-5 flex items-center justify-between"
            >
                <span class="text-xs font-medium text-secondary-200">
                    Menampilkan total
                    {{ packages.total || (packages.data || packages).length }}
                    paket
                </span>

                <!-- Gunakan link pagination dari Laravel jika menggunakan paginate(), jika tidak ini disembunyikan -->
                <div v-if="packages.links" class="flex items-center gap-2">
                    <template
                        v-for="(link, index) in packages.links"
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
                                'w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold transition',
                                link.active
                                    ? 'bg-primary text-white shadow-md'
                                    : 'bg-white text-secondary-200 hover:bg-gray-100',
                            ]"
                        />
                        <span
                            v-else
                            v-html="
                                link.label
                                    .replace('Previous', '')
                                    .replace('Next', '')
                            "
                            class="w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold bg-transparent text-gray-300 cursor-not-allowed"
                        ></span>
                    </template>
                </div>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import UserLayout from "@/Layouts/UserLayout.vue";
import Swal from "sweetalert2";
import {
    Filter,
    Plus,
    Check,
    SquarePen,
    ChevronLeft,
    ChevronRight,
    Banknote,
    Trash2,
    Search,
    X,
    Truck,
} from "@lucide/vue";

const props = defineProps({
    packages: {
        type: [Array, Object], // Menerima baik Array biasa maupun Object Pagination dari Laravel
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: "", payment_type: "", package_status: "" }),
    },
});

// State untuk Filter & Search
const search = ref(props.filters?.search || "");
const paymentType = ref(props.filters?.payment_type || "");
const packageStatus = ref(props.filters?.package_status || "");
const showFilters = ref(false);

const hasActiveFilters = computed(() => {
    return paymentType.value !== "" || packageStatus.value !== "";
});

const resetFilters = () => {
    search.value = "";
    paymentType.value = "";
    packageStatus.value = "";
};

// Auto Filter menggunakan Watcher
let timeout = null;
watch(
    [search, paymentType, packageStatus],
    ([newSearch, newPayment, newStatus]) => {
        clearTimeout(timeout);

        timeout = setTimeout(() => {
            router.get(
                "/user/packages",
                {
                    search: newSearch,
                    payment_type: newPayment,
                    package_status: newStatus,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                },
            );
        }, 300);
    },
);

const formatRupiah = (angka) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(angka);
};

const confirmDelete = (id) => {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data paket yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6", // Sesuaikan warna dengan tema primary
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/user/packages/${id}`, {
                onSuccess: () => {
                    Swal.fire(
                        "Terhapus!",
                        "Paket berhasil dihapus.",
                        "success",
                    );
                },
                onError: () => {
                    Swal.fire(
                        "Gagal!",
                        "Terjadi kesalahan saat menghapus paket.",
                        "error",
                    );
                },
            });
        }
    });
};
</script>
