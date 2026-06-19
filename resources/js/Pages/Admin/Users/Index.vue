<template>
    <AdminLayout>
        <header
            class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8"
        >
            <div>
                <h1
                    class="text-3xl md:text-[2.5rem] font-extrabold text-[#0a113a] tracking-tight leading-tight mb-2"
                >
                    Management Pengguna
                </h1>
                <p class="text-[#395c80] font-medium text-sm md:text-base">
                    Manajemen pengguna, hak akses, dan monitoring aktivitas Sistem Smart Box.
                </p>
            </div>
            <Link
                href="/admin/users/create"
                class="bg-[#0a113a] hover:bg-[#111840] text-white px-6 py-3 rounded-full font-bold text-sm md:text-base flex items-center gap-2 transition-colors shadow-lg shadow-blue-900/20 shrink-0"
            >
                <UserPlus class="w-5 h-5" />
                Tambah Data Pengguna
            </Link>
        </header>

        <div class="flex flex-col xl:flex-row gap-6 mb-6">
            <div
                class="bg-[#edf4ff] rounded-[2rem] p-8 w-full xl:w-72 shrink-0"
            >
                <p
                    class="text-sm font-extrabold text-[#395c80] tracking-widest uppercase mb-4"
                >
                    Pengguna Aktif
                </p>
                <h2
                    class="text-5xl md:text-6xl font-extrabold text-[#0a113a] tracking-tight mb-2"
                >
                    {{ count }}
                </h2>
                <p
                    class="text-[#395c80] text-sm font-bold flex items-center gap-1"
                >
                    <TrendingUp class="w-4 h-4 text-[#395c80]" />
                    Real-time status
                </p>
            </div>

            <div
                class="bg-white rounded-[2rem] p-8 flex-1 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col md:flex-row gap-8 items-start md:items-center justify-between"
            >
                <div class="flex-1 w-full">
                    <label
                        class="block text-sm font-extrabold text-gray-400 tracking-widest uppercase mb-3"
                        >Search Directory</label
                    >
                    <div class="relative">
                        <Search
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                        />
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search by name or username..."
                            class="bg-[#f7f9ff] border-none rounded-full pl-12 pr-4 py-3 w-full text-sm focus:ring-2 focus:ring-[#b2d5fe] outline-none text-[#021d31] font-medium placeholder-gray-400 transition-all"
                        />
                    </div>
                </div>

                <div class="shrink-0 w-full md:w-auto">
                    <label
                        class="block text-sm font-extrabold text-gray-400 tracking-widest uppercase mb-3"
                        >Role Filter</label
                    >
                    <div
                        class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0"
                    >
                        <button
                            @click="role = 'all'"
                            :class="
                                role === 'all'
                                    ? 'bg-[#0a113a] text-white'
                                    : 'bg-[#e2efff] text-[#395c80] hover:bg-[#d0e3ff]'
                            "
                            class="px-6 py-2.5 rounded-full text-sm font-bold transition-colors"
                        >
                            All
                        </button>
                        <button
                            @click="role = 'admin'"
                            :class="
                                role === 'admin'
                                    ? 'bg-[#0a113a] text-white'
                                    : 'bg-[#e2efff] text-[#395c80] hover:bg-[#d0e3ff]'
                            "
                            class="px-6 py-2.5 rounded-full text-sm font-bold transition-colors"
                        >
                            Admin
                        </button>
                        <button
                            @click="role = 'user'"
                            :class="
                                role === 'user'
                                    ? 'bg-[#0a113a] text-white'
                                    : 'bg-[#e2efff] text-[#395c80] hover:bg-[#d0e3ff]'
                            "
                            class="px-6 py-2.5 rounded-full text-sm font-bold transition-colors"
                        >
                            User
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] mb-8"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap min-w-[900px]">
                    <thead>
                        <tr
                            class="text-sm font-extrabold text-gray-700 uppercase tracking-widest border-b border-gray-100"
                        >
                            <th class="pb-5 pr-6 pl-2 ">Nama Lengkap</th>
                            <th class="pb-5 pr-6">Username</th>
                            <th class="pb-5 pr-6 text-center">Role</th>
                            <th class="pb-5 pr-6 text-center">Status</th>
                            <th class="pb-5 pr-6">No. Kotak</th>
                            <th class="pb-5 pr-6">Nomor Telepon</th>
                            <th class="pb-5 text-right pr-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="users.data.length === 0">
                            <td
                                colspan="7"
                                class="py-8 text-center text-gray-500 font-medium"
                            >
                                No users found.
                            </td>
                        </tr>
                        <tr
                            v-for="user in users.data"
                            :key="user.id"
                            class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors group"
                        >
                            <td class="py-5 pr-6 pl-2">
                                <div class="flex items-center gap-4">
                                    <div
                                        v-if="user.avatar"
                                        class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden shrink-0"
                                    >
                                        <img
                                            :src="user.avatar"
                                            alt="Avatar"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                    <div
                                        v-else
                                        class="w-12 h-12 rounded-full bg-[#e2efff] flex items-center justify-center text-[#0a113a] font-bold text-base shrink-0"
                                    >
                                        {{ user.name.charAt(0) }}
                                    </div>
                                    <span
                                        class="font-bold text-[#0a113a] text-base"
                                        >{{ user.name }}</span
                                    >
                                </div>
                            </td>

                            <td class="py-5 pr-6">
                                <span
                                    class="text-gray-500 font-medium text-sm md:text-base"
                                    >{{ user.username }}</span
                                >
                            </td>

                            <td class="py-5 pr-6 text-center">
                                <span
                                    v-if="user.role === 'admin'"
                                    class="inline-block bg-[#0a113a] text-white px-4 py-1.5 rounded-full text-sm font-bold tracking-widest uppercase"
                                    >Admin</span
                                >
                                <span
                                    v-else
                                    class="inline-block bg-[#e2efff] text-[#395c80] px-4 py-1.5 rounded-full text-sm font-bold tracking-widest uppercase"
                                    >User</span
                                >
                            </td>

                            <td class="py-5 pr-6 text-center">
                                <span
                                    v-if="user.status === 'active'"
                                    class="inline-block bg-emerald-100 text-emerald-700 px-4 py-1.5 rounded-full text-sm font-bold tracking-widest uppercase"
                                    >Active</span
                                >
                                <span
                                    v-else
                                    class="inline-block bg-gray-100 text-gray-500 px-4 py-1.5 rounded-full text-sm font-bold tracking-widest uppercase"
                                    >Inactive</span
                                >
                            </td>

                            <td class="py-5 pr-6">
                                <div
                                    class="flex items-center gap-2 text-[#0a113a] font-bold text-base"
                                >
                                    <Archive class="w-5 h-5 text-[#395c80]" />
                                    <span v-if="user.letter_box"
                                        >Slot {{ user.letter_box }}</span
                                    >
                                    <span
                                        v-else
                                        class="text-gray-400 font-medium text-sm"
                                        >Unassigned</span
                                    >
                                </div>
                            </td>

                            <td class="py-5 pr-6">
                                <span
                                    class="text-gray-500 font-medium text-sm md:text-base"
                                    >{{ user.phone_number || "-" }}</span
                                >
                            </td>

                            <td class="py-5 text-right pr-2  ">
                                <div
                                    class="flex items-center py-3 rounded-xl justify-center gap-5 bg-tertiary-700/50"
                                >
                                    <button
                                        @click="toggleStatus(user)"
                                        class="w-12 h-7 rounded-full relative transition-colors duration-200"
                                        :class="
                                            user.status === 'active'
                                                ? 'bg-[#0a113a]'
                                                : 'bg-neutral-100'
                                        "
                                        title="Toggle Status"
                                    >
                                        <span
                                            class="absolute top-1 left-1 bg-white w-5 h-5 rounded-full transition-transform duration-200 shadow-sm"
                                            :class="
                                                user.status === 'active'
                                                    ? 'translate-x-5'
                                                    : 'translate-x-0'
                                            "
                                        ></span>
                                    </button>

                                    <Link
                                        :href="`/admin/users/${user.id}`"
                                        class="text-secondary hover:text-[#0a113a] transition-colors"
                                        title="Edit Pengguna"
                                    >
                                         <Eye class="w-5 h-5" />
                                    </Link>
                                    <Link
                                        :href="`/admin/users/${user.id}/edit`"
                                        class="text-secondary hover:text-[#0a113a] transition-colors"
                                        title="Edit Pengguna"
                                    >
                                        <Pencil class="w-5 h-5" />
                                    </Link>

                                    <button 
                                        @click="confirmDelete(user)"
                                        class="text-danger hover:text-red-500 transition-colors"
                                        title="Hapus Pengguna"
                                        :disabled="user.role === 'admin'">
                                        <Trash2 class="w-5 h-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                class="flex flex-col sm:flex-row items-center justify-between mt-8 pt-6 border-t border-gray-50 gap-4"
            >
                <p class="text-sm font-medium text-gray-500">
                    Showing {{ users.from || 0 }} to {{ users.to || 0 }} of
                    {{ users.total }} users
                </p>
                <div class="flex items-center gap-3">
                    <Link
                        v-if="users.prev_page_url"
                        :href="users.prev_page_url"
                        class="px-6 py-2.5 rounded-full border border-gray-200 text-sm font-bold text-[#0a113a] hover:bg-gray-50 transition-colors"
                    >
                        Previous
                    </Link>
                    <button
                        v-else
                        disabled
                        class="px-6 py-2.5 rounded-full border border-gray-100 text-sm font-bold text-gray-300 cursor-not-allowed"
                    >
                        Previous
                    </button>

                    <Link
                        v-if="users.next_page_url"
                        :href="users.next_page_url"
                        class="px-6 py-2.5 rounded-full bg-[#0a113a] text-sm font-bold text-white hover:bg-[#111840] transition-colors"
                    >
                        Next
                    </Link>
                    <button
                        v-else
                        disabled
                        class="px-6 py-2.5 rounded-full bg-gray-200 text-sm font-bold text-gray-400 cursor-not-allowed"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
        <div v-if="isDeleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div @click="closeDeleteModal" class="absolute inset-0 bg-[#0a113a]/40 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl transform transition-all">
                
                <div class="flex items-start gap-5 mb-6">
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center shrink-0 border border-red-100">
                        <AlertTriangle class="w-6 h-6 text-red-500" />
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-[#1e233b] tracking-tight">Hapus Pengguna?</h3>
                        <p class="text-gray-500 font-medium text-sm mt-1">Tindakan ini permanen.</p>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Apakah Anda yakin ingin menghapus 
                        <strong class="text-[#1e233b]">{{ userToDelete?.name }}</strong>? 
                        Seluruh data kredensial, akses login, dan alokasi slot box orang ini akan dihapus dari sistem.
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
                        <svg v-if="isDeleting" class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span v-else>Ya, Hapus</span>
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, watch } from "vue";
import { router, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    UserPlus,
    TrendingUp,
    Search,
    Download,
    Archive,
    Eye,
    Pencil,
    Trash2,
    AlertTriangle
} from "@lucide/vue";

// 1. Terima Data Props dari Controller
const props = defineProps({
    users: Object,
    count: Number,
    filters: Object,
});

// 2. Setup State untuk Filter
const search = ref(props.filters?.search || "");
const role = ref(props.filters?.role || "all");

let searchTimeout = null;
watch([search, role], ([newSearch, newRole]) => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        router.get(
            "/admin/users",
            {
                search: newSearch,
                role: newRole,
            },
            {
                preserveState: true,
                preserveScroll: true, 
                replace: true, 
            },
        );
    }, 300); 
});

const toggleStatus = (user) => {

    router.patch(
        `/admin/users/${user.id}/toggle-status`,
        {},
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
};

const isDeleteModalOpen = ref(false);
const userToDelete = ref(null);
const isDeleting = ref(false);

const confirmDelete = (user) => {
    userToDelete.value = user;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    setTimeout(() => {
        userToDelete.value = null;
    }, 300); 
};

const executeDelete = () => {
    if (!userToDelete.value) return;
    
    isDeleting.value = true;

    router.delete(`/admin/users/${userToDelete.value.id}`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            closeDeleteModal();
            isDeleting.value = false;
        },
        onError: () => {
            isDeleting.value = false;
        }
    });
};
</script>
