<template>
    <AdminLayout>
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8"
        >
            <div>
                <div
                    class="flex items-center gap-2 text-sm font-bold text-gray-500 mb-2"
                >
                    <Link
                        href="/admin/users"
                        class="hover:text-[#1e233b] transition-colors"
                        >Pengguna</Link
                    >
                    <ChevronRight class="w-4 h-4" />
                    <span class="text-[#1e233b]">Detail Profil</span>
                </div>
                <h1
                    class="text-3xl md:text-4xl font-extrabold text-[#1e233b] tracking-tight"
                >
                    Profil Pengguna
                </h1>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto mt-4 md:mt-0">
                <Link
                    :href="`/admin/users/${user.id}/edit`"
                    class="flex-1 md:flex-none flex items-center justify-center gap-2 shadow-md bg-inverse-on-surface hover:bg-[#e0e7f1] text-[#395c80] px-6 py-3 rounded-full font-bold text-sm transition-colors"
                >
                    <Pencil class="w-4 h-4" /> Edit Profil
                </Link>

                <button
                    @click="confirmDelete"
                    class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-[#1e233b] hover:bg-[#2a314d] text-white px-6 py-3 rounded-full font-bold text-sm transition-all shadow-lg shadow-blue-900/20"
                >
                    <Trash2 class="w-4 h-4" /> Hapus Pengguna
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div
                class="lg:col-span-4 bg-white rounded-[2rem] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 flex flex-col items-center relative overflow-hidden"
            >
                <div
                    class="absolute -top-8 -right-8 w-24 h-24 bg-gray-50 rounded-full"
                ></div>

                <div
                    class="relative w-32 h-32 rounded-full bg-[#1e233b] mb-6 shadow-xl flex items-center justify-center text-white text-5xl font-bold mt-4"
                >
                    <img
                        v-if="user.avatar"
                        :src="user.avatar"
                        alt="Avatar"
                        class="w-full h-full object-cover rounded-full border-4 border-white"
                    />
                    <span v-else>{{ user.name.charAt(0).toUpperCase() }}</span>

                    <div
                        v-if="user.status === 'active'"
                        class="absolute bottom-1 right-1 bg-white rounded-full p-1 shadow-sm"
                    >
                        <div class="bg-blue-500 rounded-full p-1 text-white">
                            <ShieldCheck class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                <h2
                    class="text-2xl font-extrabold text-[#1e233b] text-center mb-1"
                >
                    {{ user.name }}
                </h2>
                <p class="text-[#395c80] font-medium text-sm text-center mb-5">
                    @{{ user.username }}
                </p>

                <div
                    v-if="user.status === 'active'"
                    class="bg-[#eef2f6] text-[#395c80] px-4 py-1.5 rounded-full text-xs font-bold flex items-center gap-2 mb-8"
                >
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    Aktif & Terverifikasi
                </div>
                <div
                    v-else
                    class="bg-red-50 text-red-600 px-4 py-1.5 rounded-full text-xs font-bold flex items-center gap-2 mb-8"
                >
                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                    Nonaktif
                </div>

                <div class="w-full border-t border-gray-100 mb-6"></div>

                <div class="w-full space-y-5">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-[#f4f7fb] flex items-center justify-center text-[#395c80] shrink-0"
                        >
                            <Mail class="w-4 h-4" />
                        </div>
                        <div class="overflow-hidden">
                            <p
                                class="text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-0.5"
                            >
                                Email Address
                            </p>
                            <p
                                class="font-bold text-[#1e233b] text-sm truncate"
                            >
                                {{ user.email || "Belum diatur" }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-[#f4f7fb] flex items-center justify-center text-[#395c80] shrink-0"
                        >
                            <Phone class="w-4 h-4" />
                        </div>
                        <div class="overflow-hidden">
                            <p
                                class="text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-0.5"
                            >
                                Phone Number
                            </p>
                            <p
                                class="font-bold text-[#1e233b] text-sm truncate"
                            >
                                {{ user.phone_number || "Belum diatur" }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 space-y-6 ">
                <div class="bg-inverse-on-surface p-5 rounded-3xl">
                    <div class="flex items-center gap-3 mb-2 ml-2">
                        <LayoutGrid class="w-5 h-5 text-[#395c80]" />
                        <h2 class="text-lg font-extrabold text-[#1e233b]">
                            Alokasi Sistem
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div
                            class="bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 flex flex-col justify-center"
                        >
                            <div class="flex justify-between items-start mb-4">
                                <p
                                    class="text-[10px] font-extrabold text-gray-400 tracking-widest uppercase"
                                >
                                    Allocated Box Slot
                                </p>
                                <Info class="w-4 h-4 text-gray-300" />
                            </div>
                            <h3
                                class="text-3xl font-extrabold text-[#1e233b] mb-1"
                            >
                                {{
                                    user.letter_box
                                        ? "Slot " + user.letter_box
                                        : "Tidak Ada"
                                }}
                            </h3>
                            <p class="text-[#395c80] font-medium text-sm">
                                Utama
                            </p>
                        </div>

                        <div
                            class="bg-[#1e233b] rounded-3xl p-8 shadow-xl flex flex-col justify-center text-white relative overflow-hidden"
                        >
                            <div
                                class="flex justify-between items-start mb-4 relative z-10"
                            >
                                <p
                                    class="text-[10px] font-extrabold text-gray-400 tracking-widest uppercase"
                                >
                                    Access Status
                                </p>
                                <Lock class="w-4 h-4 text-gray-400" />
                            </div>

                            <div v-if="user.status === 'active'">
                                <h3
                                    class="text-3xl font-extrabold mb-1 relative z-10"
                                >
                                    Terhubung
                                </h3>
                               
                            </div>
                            <div v-else>
                                <h3
                                    class="text-3xl font-extrabold mb-1 relative z-10 text-red-400"
                                >
                                    Terputus
                                </h3>
                                <p
                                    class="text-gray-400 font-medium text-sm relative z-10"
                                >
                                    Akses ditangguhkan
                                </p>
                            </div>

                            <Wifi
                                class="absolute -bottom-4 -right-4 w-32 h-32 text-white/5 pointer-events-none"
                            />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="md:col-span-2 bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100"
                    >
                        <p
                            class="text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-4"
                        >
                            Catatan Administrator
                        </p>
                        <p
                            class="text-gray-600 text-sm leading-relaxed font-medium"
                        >
                            Pengguna terdaftar dengan akses ke sistem. Terdaftar sejak
                            <strong>{{ formatDate(user.created_at) }}</strong>
                            dengan data yang telah terverifikasi oleh administrator.
                        </p>
                    </div>

                    <div
                        class="md:col-span-1 bg-[#f4f7fb] rounded-3xl p-8 flex flex-col items-center justify-center text-center"
                    >
                        <History class="w-6 h-6 text-[#395c80] mb-3" />
                        <h3 class="text-2xl font-extrabold text-[#1e233b] mb-1">
                            -
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="isDeleteModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center px-4"
        >
            <div
                @click="closeDeleteModal"
                class="absolute inset-0 bg-[#0a113a]/40 backdrop-blur-sm transition-opacity"
            ></div>

            <div
                class="relative bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl transform transition-all"
            >
                <div class="flex items-start gap-5 mb-6">
                    <div
                        class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center shrink-0 border border-red-100"
                    >
                        <AlertTriangle class="w-6 h-6 text-red-500" />
                    </div>
                    <div>
                        <h3
                            class="text-xl font-extrabold text-[#1e233b] tracking-tight"
                        >
                            Hapus Pengguna?
                        </h3>
                        <p class="text-gray-500 font-medium text-sm mt-1">
                            Tindakan ini permanen.
                        </p>
                    </div>
                </div>
                <div class="mb-8">
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Apakah Anda yakin ingin menghapus profil
                        <strong class="text-[#1e233b]">{{ user.name }}</strong
                        >? Seluruh data kredensial, akses login, dan alokasi
                        slot box orang ini akan dihapus dari sistem.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        @click="closeDeleteModal"
                        :disabled="isDeleting"
                        class="flex-1 bg-[#eef2f6] hover:bg-[#e0e7f1] text-[#395c80] font-bold py-3.5 px-4 rounded-full transition-colors text-sm disabled:opacity-50"
                    >
                        Batal
                    </button>
                    <button
                        @click="executeDelete"
                        :disabled="isDeleting"
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3.5 px-4 rounded-full transition-colors text-sm disabled:opacity-50 flex items-center justify-center gap-2"
                    >
                        <Loader2
                            v-if="isDeleting"
                            class="w-4 h-4 animate-spin text-white"
                        />
                        <span v-else>Ya, Hapus</span>
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    ChevronRight,
    Pencil,
    Trash2,
    Mail,
    Phone,
    ShieldCheck,
    LayoutGrid,
    Info,
    Lock,
    History,
    Wifi,
    AlertTriangle,
    Loader2,
} from "@lucide/vue";

// 1. Terima data User
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

// Format Tanggal untuk Catatan Admin
const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("id-ID", options);
};

// --- LOGIKA MODAL HAPUS ---
const isDeleteModalOpen = ref(false);
const isDeleting = ref(false);

const confirmDelete = () => {
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
};

const executeDelete = () => {
    isDeleting.value = true;
    router.delete(`/admin/users/${props.user.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            isDeleting.value = false;
        },
        onError: () => {
            isDeleting.value = false;
        },
    });
};
</script>
