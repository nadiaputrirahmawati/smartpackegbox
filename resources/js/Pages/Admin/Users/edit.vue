<template>
    <AdminLayout>
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8"
        >
            <div>
                <Link
                    href="/admin/users"
                    class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-[#1e233b] transition-colors mb-2"
                >
                    <ArrowLeft class="w-4 h-4" /> Kembali ke Daftar Pengguna
                </Link>
                <h1
                    class="text-3xl md:text-4xl font-extrabold text-[#1e233b] tracking-tight"
                >
                    Edit Profil Pengguna
                </h1>
                <p class="text-gray-500 font-medium text-sm mt-1">
                    Kelola kredensial keamanan dan akses box untuk
                    <strong class="text-[#1e233b]">{{ user.name }}</strong
                    >.
                </p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto mt-4 md:mt-0">
                <Link
                    href="/admin/users"
                    class="flex-1 md:flex-none text-center shadow-lg bg-tertiary-700 hover:bg-tertiary-600 text-[#395c80] px-8 py-3 rounded-full font-bold text-sm transition-colors"
                >
                    Batal
                </Link>
                <button
                    @click="submit"
                    :disabled="form.processing"
                    class="flex-1 md:flex-none bg-[#1e233b] hover:bg-[#2a314d] text-white px-8 py-3 rounded-full font-bold text-sm transition-all shadow-lg shadow-blue-900/20 disabled:opacity-50 flex items-center justify-center gap-2"
                >
                    <Loader2
                        v-if="form.processing"
                        class="w-4 h-4 animate-spin"
                    />
                    Simpan Perubahan
                </button>
            </div>
        </div>

        <form
            @submit.prevent="submit"
            class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start"
        >
            <div class="xl:col-span-8 space-y-6">
                <div
                    class="bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100"
                >
                    <h2
                        class="text-lg font-extrabold text-[#1e233b] flex items-center gap-3 mb-6"
                    >
                        <Contact class="w-5 h-5 text-[#395c80]" /> Identitas &
                        Kredensial
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 tracking-widest uppercase mb-2"
                                >Nama Lengkap</label
                            >
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full bg-tertiary-700 border-none rounded-2xl px-5 py-4 text-sm font-semibold text-[#1e233b] focus:ring-2 focus:ring-[#b2d5fe] outline-none transition-all placeholder-gray-400"
                            />
                            <p
                                v-if="form.errors.name"
                                class="text-red-500 text-sm mt-1 font-medium"
                            >
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 tracking-widest uppercase mb-2"
                                >Username
                                <span class="normal-case font-normal"
                                    >(Tidak dapat diubah)</span
                                ></label
                            >
                            <input
                                v-model="form.username"
                                type="text"
                                readonly
                                class="w-full bg-tertiary-700/60 border-none rounded-2xl px-5 py-4 text-sm font-semibold text-gray-400 outline-none cursor-not-allowed"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 tracking-widest uppercase mb-2"
                                >Email</label
                            >
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                class="w-full bg-tertiary-700 border-none rounded-2xl px-5 py-4 text-sm font-semibold text-[#1e233b] focus:ring-2 focus:ring-[#b2d5fe] outline-none transition-all placeholder-gray-400"
                            />
                            <p
                                v-if="form.errors.email"
                                class="text-red-500 text-sm mt-1 font-medium"
                            >
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 tracking-widest uppercase mb-2"
                                >Nomor Handphone</label
                            >
                            <input
                                v-model="form.phone_number"
                                type="tel"
                                class="w-full bg-tertiary-700 border-none rounded-2xl px-5 py-4 text-sm font-semibold text-[#1e233b] focus:ring-2 focus:ring-[#b2d5fe] outline-none transition-all placeholder-gray-400"
                            />
                            <p
                                v-if="form.errors.phone_number"
                                class="text-red-500 text-sm mt-1 font-medium"
                            >
                                {{ form.errors.phone_number }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 tracking-widest uppercase mb-3"
                            >Status Aktif</label
                        >
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input
                                    type="radio"
                                    v-model="form.status"
                                    value="active"
                                    name="status"
                                    class="peer sr-only"
                                />
                                <div
                                    class="flex items-center gap-3 p-4 rounded-2xl border-2 bg-white border-gray-100 peer-checked:border-[#1e233b] peer-checked:bg-[#f8faff] transition-all"  
                                >
                                    <CheckCircle2
                                        class="w-5 h-5 text-emerald-500"
                                    />
                                    <span class="font-bold text-[#1e233b]"
                                        >Aktif</span
                                    >
                                    <div
                                        class="ml-auto w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-[5px] peer-checked:border-[#1e233b]" :class="{ 'bg-[#1e233b]': form.status === 'active' }"
                                    ></div>
                                </div>
                            </label>

                            <label class="relative cursor-pointer group">
                                <input
                                    type="radio"
                                    v-model="form.status"
                                    value="inactive"
                                    name="status"
                                    class="peer sr-only"
                                />
                                <div
                                    class="flex items-center gap-3 p-4 rounded-2xl border-2 bg-white border-gray-100 peer-checked:border-[#1e233b] peer-checked:bg-[#f8faff] transition-all"
                                >
                                    <XCircle class="w-5 h-5 text-red-400" />
                                    <span class="font-bold text-[#1e233b]"
                                        >Nonaktif</span
                                    >
                                    <div
                                        class="ml-auto w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-[5px] peer-checked:border-[#1e233b]" :class="{ 'bg-[#1e233b]': form.status === 'inactive' }"
                                    ></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100"
                >
                    <h2
                        class="text-lg font-extrabold text-[#1e233b] flex items-center gap-3 mb-6"
                    >
                        <Box class="w-5 h-5 text-[#395c80]" /> Alokasi Nomor
                        Slot
                    </h2>

                    <div class="grid grid-cols-2 gap-4">
                        <label
                            class="relative"
                            :class="
                                !availableBoxes.includes('A')
                                    ? 'opacity-40 cursor-not-allowed'
                                    : 'cursor-pointer'
                            "
                        >
                            <input
                                type="radio"
                                v-model="form.letter_box"
                                value="A"
                                name="letter_box"
                                :disabled="!availableBoxes.includes('A')"
                                class="peer sr-only"
                            />
                            <div
                                class="p-6 rounded-2xl border-2 border-transparent bg-tertiary-700 text-center peer-checked:bg-[#1e233b] peer-checked:text-white transition-all"
                            >
                                <p
                                    class="text-[10px] font-bold uppercase tracking-widest mb-1 opacity-70"
                                >
                                    {{
                                        availableBoxes.includes("A")
                                            ? "Tersedia"
                                            : "Terpakai"
                                    }}
                                </p>
                                <h3 class="text-xl font-extrabold">Slot A</h3>
                            </div>
                        </label>

                        <label
                            class="relative"
                            :class="
                                !availableBoxes.includes('B')
                                    ? 'opacity-40 cursor-not-allowed'
                                    : 'cursor-pointer'
                            "
                        >
                            <input
                                type="radio"
                                v-model="form.letter_box"
                                value="B"
                                name="letter_box"
                                :disabled="!availableBoxes.includes('B')"
                                class="peer sr-only"
                            />
                            <div
                                class="p-6 rounded-2xl border-2 border-transparent bg-tertiary-700 text-center peer-checked:bg-[#1e233b] peer-checked:text-white transition-all"
                            >
                                <p
                                    class="text-[10px] font-bold uppercase tracking-widest mb-1 opacity-70"
                                >
                                    {{
                                        availableBoxes.includes("B")
                                            ? "Tersedia"
                                            : "Terpakai"
                                    }}
                                </p>
                                <h3 class="text-xl font-extrabold">Slot B</h3>
                            </div>
                        </label>
                    </div>
                    <p class="text-xs text-gray-400 mt-4 font-medium">
                        *Mengubah status ke Nonaktif akan otomatis mencabut slot
                        pengguna ini.
                    </p>
                </div>
            </div>

            <div class="xl:col-span-4 space-y-6 sticky top-8">
                <div
                    class="bg-[#1e233b] rounded-3xl p-6 text-white shadow-xl relative overflow-hidden"
                >
                    <h3 class="text-lg font-bold mb-2 relative z-10">
                        <CircleUserRound class="opacity-65" /> Profile Preview
                    </h3>
                    <div class="space-y-3 relative z-10">
                        <div class="flex items-center gap-3 text-sm"></div>
                        <div class="flex items-center gap-3 text-sm"></div>
                    </div>
                    <Lock
                        class="w-32 h-32 text-white/[0.05] absolute -bottom-6 -right-6 pointer-events-none"
                    />
                </div>
                <div
                    class="bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 flex flex-col items-center text-center"
                >
                    <div
                        class="relative w-24 h-24 rounded-full bg-[#1e233b] mb-4 shadow-lg flex items-center justify-center text-white text-3xl font-bold"
                    >
                        {{
                            form.name ? form.name.charAt(0).toUpperCase() : "?"
                        }}
                        <span
                            v-if="form.status === 'active'"
                            class="absolute -top-2 -right-6 bg-emerald-100 text-emerald-700 text-[9px] font-extrabold px-2 py-1 rounded-full uppercase tracking-widest border-2 border-white"
                            >Terverifikasi</span
                        >
                        <span
                            v-else
                            class="absolute -top-2 -right-6 bg-red-100 text-red-700 text-[9px] font-extrabold px-2 py-1 rounded-full uppercase tracking-widest border-2 border-white"
                            >Nonaktif</span
                        >
                    </div>

                    <h3 class="text-2xl font-extrabold text-[#1e233b] mb-1">
                        {{ form.name }}
                    </h3>
                    <p class="text-[#395c80] font-medium text-sm mb-6">
                        @{{ form.username }}
                    </p>

                    <div
                        class="w-full flex justify-between items-center border-t border-gray-100 pt-6"
                    >
                        <div class="text-left">
                            <p
                                class="text-[11px] font-extrabold text-gray-400 tracking-widest uppercase mb-1"
                            >
                                Slot Box
                            </p>
                            <p class="font-bold text-[#1e233b] text-sm">
                                {{
                                    form.letter_box
                                        ? "Slot " + form.letter_box
                                        : "-"
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    ArrowLeft,
    Loader2,
    Contact,
    Box,
    CheckCircle2,
    XCircle,
    ShieldCheck,
    Check,
    Lock,
} from "@lucide/vue";

// 1. Terima user dan kotak yang tersedia dari backend
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    availableBoxes: {
        type: Array,
        required: true,
    },
});

// 2. Inisialisasi useForm dengan data user yang sudah ada
const form = useForm({
    name: props.user.name,
    username: props.user.username,
    email: props.user.email,
    status: props.user.status,
    letter_box: props.user.letter_box || "",
    phone_number: props.user.phone_number || "",
});

// 3. Submit Update menggunakan .put()
const submit = () => {
    form.put(`/admin/users/${props.user.id}`, {
        preserveScroll: true,
    });
};
</script>
