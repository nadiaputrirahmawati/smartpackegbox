<template>
    <AdminLayout>
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8"
        >
            <div>
                <p
                    class="text-[10px] font-extrabold text-secondary-200 tracking-widest uppercase mb-1"
                >
                    Pengaturan Sistem
                </p>
                <h1
                    class="text-3xl md:text-4xl font-extrabold text-primary tracking-tight mb-2"
                >
                    Profil Pengguna
                </h1>
                <p class="text-secondary-200 font-medium text-sm">
                    Kelola informasi identitas Anda dan amankan akses akun
                    administrator Anda.
                </p>
            </div>

            <div
                class="bg-blue-50 text-blue-600 px-4 py-2 rounded-full text-xs font-bold flex items-center gap-2 shadow-sm border border-blue-100"
            >
                <div
                    class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"
                ></div>
                Status: Aktif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start pb-8">
            <div class="lg:col-span-4 space-y-6">
                <form
                    @submit.prevent="submitProfile"
                    class="bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-secondary-600 flex flex-col items-center text-center"
                >
                    <div
                        class="relative w-32 h-32 rounded-full mb-6 group cursor-pointer"
                        @click="$refs.avatarInput.click()"
                    >
                        <div
                            class="w-full h-full rounded-full bg-primary overflow-hidden shadow-xl border-4 border-white flex items-center justify-center text-white text-5xl font-bold"
                        >
                            <img
                                v-if="avatarPreview || user.avatar"
                                :src="avatarPreview || user.avatar"
                                class="w-full h-full object-cover"
                            />
                            <span v-else>{{
                                user.name.charAt(0).toUpperCase()
                            }}</span>
                        </div>

                        <div
                            class="absolute bottom-0 right-0 w-10 h-10 bg-primary rounded-full border-4 border-white flex items-center justify-center text-white shadow-lg hover:scale-110 transition-transform"
                        >
                            <Pencil class="w-4 h-4" />
                        </div>
                        <input
                            type="file"
                            ref="avatarInput"
                            class="hidden"
                            accept="image/*"
                            @change="handleAvatarChange"
                        />
                    </div>

                    <h2 class="text-xl font-extrabold text-primary mb-1">
                        {{ profileForm.name || "Admin" }}
                    </h2>
                    <p class="text-secondary-200 font-medium text-xs mb-6">
                        Super Admin System
                    </p>

                    <button
                        type="button"
                        @click="$refs.avatarInput.click()"
                        class="w-full bg-tertiary-700 hover:bg-secondary-500 text-secondary-100 py-3 rounded-full font-bold text-sm transition-colors mb-2"
                    >
                        Ganti Foto
                    </button>
                    <p
                        v-if="profileForm.errors.avatar"
                        class="text-danger text-xs"
                    >
                        {{ profileForm.errors.avatar }}
                    </p>
                </form>

                <div
                    class="bg-primary rounded-3xl p-8 text-white shadow-xl relative overflow-hidden"
                >
                    <div
                        class="flex justify-between items-center mb-8 relative z-10"
                    >
                        <h3 class="text-lg font-bold">Status Akun</h3>
                        <ShieldCheck class="w-5 h-5 text-blue-400" />
                    </div>
                    <div
                        class="flex justify-between items-center mb-6 relative z-10"
                    >
                        <div>
                            <p
                                class="text-[9px] font-extrabold text-gray-400 tracking-widest uppercase mb-1"
                            >
                                Status Saat Ini
                            </p>
                            <h4 class="text-xl font-extrabold">Aktif</h4>
                        </div>
                        <div
                            class="w-12 h-7 rounded-full bg-blue-500 relative cursor-not-allowed opacity-80"
                            title="Akun Anda harus selalu aktif"
                        >
                            <span
                                class="absolute top-1 left-6 bg-white w-5 h-5 rounded-full shadow-sm"
                            ></span>
                        </div>
                    </div>
                    <p
                        class="text-gray-400 text-xs leading-relaxed relative z-10"
                    >
                        Menonaktifkan akun akan segera mencabut semua akses
                        administratif ke sistem ini.
                    </p>
                    <Lock
                        class="w-32 h-32 text-white/[0.03] absolute -bottom-6 -right-6 pointer-events-none"
                    />
                </div>
            </div>

            <div class="lg:col-span-8 space-y-6">
                <transition
                    enter-active-class="transition  ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                >
                    <div
                        v-if="profileForm.recentlySuccessful"
                        class="bg-green-50 text-green-700 px-4 py-3 rounded-xl border border-green-200 text-sm font-bold flex items-center gap-2"
                    >
                        <ShieldCheck class="w-5 h-5" /> Update Data Berhasil
                    </div>
                </transition>
                <form
                    @submit.prevent="submitProfile"
                    class="bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-secondary-600"
                >
                    <h2
                        class="text-lg font-extrabold text-primary flex items-center gap-3 mb-6"
                    >
                        <Contact class="w-5 h-5 text-secondary-100" /> Informasi
                        Pribadi
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label
                                class="block text-xs font-bold text-secondary-200 tracking-widest uppercase mb-2"
                                >Nama Lengkap</label
                            >
                            <input
                                v-model="profileForm.name"
                                type="text"
                                required
                                class="w-full bg-tertiary-800/50 border border-secondary-600 rounded-xl px-5 py-4 text-sm font-semibold text-primary focus:ring-2 focus:ring-tertiary-500 outline-none transition-all"
                            />
                            <p
                                v-if="profileForm.errors.name"
                                class="text-danger text-sm mt-1 font-medium"
                            >
                                {{ profileForm.errors.name }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-secondary-200 tracking-widest uppercase mb-2"
                                >Username</label
                            >
                            <input
                                v-model="profileForm.username"
                                type="text"
                                required
                                class="w-full bg-tertiary-800/50 border border-secondary-600 rounded-xl px-5 py-4 text-sm font-semibold text-primary focus:ring-2 focus:ring-tertiary-500 outline-none transition-all"
                            />
                            <p
                                v-if="profileForm.errors.username"
                                class="text-danger text-sm mt-1 font-medium"
                            >
                                {{ profileForm.errors.username }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label
                            class="block text-xs font-bold text-secondary-200 tracking-widest uppercase mb-2"
                            >Nomor Telepon</label
                        >
                        <div class="flex gap-3">
                            <div
                                class="bg-tertiary-800/50 border border-secondary-600 rounded-xl px-5 py-4 flex items-center justify-center font-bold text-secondary-200 shrink-0"
                            >
                                +62
                            </div>
                            <input
                                v-model="profileForm.phone_number"
                                type="tel"
                                class="w-full bg-tertiary-800/50 border border-secondary-600 rounded-xl px-5 py-4 text-sm font-semibold text-primary focus:ring-2 focus:ring-tertiary-500 outline-none transition-all" 
                            />
                        </div>
                        <p
                            v-if="profileForm.errors.phone_number"
                            class="text-danger text-sm mt-1 font-medium"
                        >
                            {{ profileForm.errors.phone_number }}
                        </p>
                    </div>

                    <div class="flex items-center justify-end">
                        <button
                            type="submit"
                            :disabled="profileForm.processing"
                            class="bg-primary hover:bg-primary-100 text-white px-8 py-3.5 rounded-full font-bold text-sm transition-all shadow-lg shadow-primary/20 disabled:opacity-50 flex items-center gap-2"
                        >
                            <Loader2
                                v-if="profileForm.processing"
                                class="w-4 h-4 animate-spin"
                            />
                            Simpan Profil
                        </button>
                    </div>
                </form>

                <form
                    @submit.prevent="updatePassword"
                    class="bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-secondary-600"
                >
                    <div class="flex items-center gap-3 mb-8">
                        <div
                            class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-danger shrink-0"
                        >
                            <Lock class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold text-primary">
                                Keamanan Akun
                            </h2>
                            <p class="text-xs font-medium text-secondary-200">
                                Ubah kata sandi Anda untuk menjaga keamanan.
                            </p>
                        </div>
                    </div>

                    <div class="max-w-xl space-y-6">
                        <div>
                            <label
                                class="block text-xs font-bold text-secondary-200 tracking-widest uppercase mb-2"
                                >Password Saat Ini</label
                            >
                            <div class="relative">
                                <input
                                    v-model="passwordForm.current_password"
                                    :type="
                                        showCurrentPassword
                                            ? 'text'
                                            : 'password'
                                    "
                                    required
                                    class="w-full bg-tertiary-800/50 border border-secondary-600 rounded-xl pl-5 pr-12 py-4 text-sm font-semibold text-primary focus:ring-2 focus:ring-tertiary-500 outline-none transition-all"
                                />
                                <button
                                    type="button"
                                    @click="
                                        showCurrentPassword =
                                            !showCurrentPassword
                                    "
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-secondary-200 hover:text-primary transition-colors"
                                >
                                    <EyeOff
                                        v-if="showCurrentPassword"
                                        class="w-5 h-5"
                                    />
                                    <Eye v-else class="w-5 h-5" />
                                </button>
                            </div>
                            <p
                                v-if="passwordForm.errors.current_password"
                                class="text-danger text-sm mt-1 font-medium"
                            >
                                {{ passwordForm.errors.current_password }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-secondary-200 tracking-widest uppercase mb-2"
                                    >Password Baru</label
                                >
                                <div class="relative">
                                    <input
                                        v-model="passwordForm.password"
                                        :type="
                                            showNewPassword
                                                ? 'text'
                                                : 'password'
                                        "
                                        required
                                        placeholder="Min. 8 Karakter"
                                        class="w-full bg-tertiary-800/50 border border-secondary-600 rounded-xl pl-5 pr-12 py-4 text-sm font-semibold text-primary focus:ring-2 focus:ring-tertiary-500 outline-none transition-all"
                                    />
                                    <button
                                        type="button"
                                        @click="
                                            showNewPassword = !showNewPassword
                                        "
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-secondary-200 hover:text-primary transition-colors"
                                    >
                                        <EyeOff
                                            v-if="showNewPassword"
                                            class="w-5 h-5"
                                        />
                                        <Eye v-else class="w-5 h-5" />
                                    </button>
                                </div>
                                <p
                                    v-if="passwordForm.errors.password"
                                    class="text-danger text-sm mt-1 font-medium"
                                >
                                    {{ passwordForm.errors.password }}
                                </p>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-secondary-200 tracking-widest uppercase mb-2"
                                    >Konfirmasi Password</label
                                >
                                <div class="relative">
                                    <input
                                        v-model="
                                            passwordForm.password_confirmation
                                        "
                                        type="password"
                                        required
                                        placeholder="Ulangi Password"
                                        class="w-full bg-tertiary-800/50 border border-secondary-600 rounded-xl px-5 py-4 text-sm font-semibold text-primary focus:ring-2 focus:ring-tertiary-500 outline-none transition-all"
                                    />
                                </div>
                                <p
                                    v-if="
                                        passwordForm.errors
                                            .password_confirmation
                                    "
                                    class="text-danger text-sm mt-1 font-medium"
                                >
                                    {{
                                        passwordForm.errors
                                            .password_confirmation
                                    }}
                                </p>
                            </div>
                        </div>

                        <transition
                            enter-active-class="transition duration-20000 ease-out"
                            enter-from-class="opacity-0 -translate-y-2"
                            enter-to-class="opacity-100 translate-y-0"
                        >
                            <div
                                v-if="passwordForm.recentlySuccessful"
                                class="bg-green-50 text-green-700 px-4 py-3 rounded-xl border border-green-200 text-sm font-bold flex items-center gap-2"
                            >
                                <ShieldCheck class="w-5 h-5" /> Password
                                berhasil diganti!
                            </div>
                        </transition>

                        <div
                            class="flex items-center justify-end pt-4 border-t border-secondary-600"
                        >
                            <button
                                type="submit"
                                :disabled="passwordForm.processing"
                                class="bg-primary hover:bg-primary-100 text-white px-8 py-3.5 rounded-full font-bold text-sm transition-all shadow-lg shadow-primary/20 disabled:opacity-50 flex items-center gap-2"
                            >
                                <Loader2
                                    v-if="passwordForm.processing"
                                    class="w-4 h-4 animate-spin"
                                />
                                Perbarui Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    Pencil,
    ShieldCheck,
    Lock,
    Contact,
    Eye,
    EyeOff,
    Loader2,
} from "@lucide/vue";

const page = usePage();

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

// Toggle Visibilitas Password
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);

const avatarInput = ref(null);
const avatarPreview = ref(null);

// ==========================================
// FORM 1: PROFIL DASAR
// ==========================================
const profileForm = useForm({
    name: props.user.name,
    username: props.user.username,
    phone_number: props.user.phone_number || "",
    avatar: null,
});

const handleAvatarChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        profileForm.avatar = file;
        avatarPreview.value = URL.createObjectURL(file);
    }
};

const submitProfile = () => {
    profileForm.post("/admin/profile", {
        preserveScroll: true,
    });
};

// ==========================================
// FORM 2: UPDATE PASSWORD
// ==========================================
const passwordForm = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updatePassword = () => {
    passwordForm.put("/admin/profile/password", {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            // passwordForm.recentlySuccessful otomatis menjadi 'true' selama 2 detik dari Inertia
        },
    });
};
</script>
