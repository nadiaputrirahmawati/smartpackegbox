<script setup>
import UserLayout from "@/Layouts/UserLayout.vue";
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import {
  User,
  Camera,
  RotateCcw,
  Package,
  Key,
  ShieldCheck,
} from "@lucide/vue";

// Mengimpor SweetAlert2
import Swal from 'sweetalert2';

// Membuat konfigurasi dasar untuk Toast Notification
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end', // Muncul di pojok kanan atas
  showConfirmButton: false,
  timer: 3000, // Hilang otomatis setelah 3 detik
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
  }
});

const props = defineProps({
  user: Object,
  activities: Array,
});

// ====================================================================
// 1. LOGIKA AVATAR
// ====================================================================
const avatarInput = ref(null);
const avatarPreview = ref(null);

const avatarForm = useForm({ avatar: null });

const triggerAvatarUpload = () => {
  if (avatarInput.value) avatarInput.value.click();
};

const handleAvatarChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    avatarForm.avatar = file;
    avatarPreview.value = URL.createObjectURL(file);
  }
};

const cancelAvatar = () => {
  avatarForm.reset();
  avatarForm.clearErrors();
  avatarPreview.value = null;
  if (avatarInput.value) avatarInput.value.value = null;
};

const submitAvatar = () => {
  avatarForm.post("/user/setting/profile/avatar", {
    preserveScroll: true,
    onSuccess: () => {
      avatarPreview.value = null;
      // Memanggil Custom Alert (Sukses)
      Toast.fire({
        icon: 'success',
        title: 'Foto profil berhasil diperbarui!'
      });
    },
    onError: (errors) => {
      // Memanggil Custom Alert (Gagal)
      Toast.fire({
        icon: 'error',
        title: errors.avatar || 'Gagal mengupload foto.'
      });
    },
  });
};

// ====================================================================
// 2. LOGIKA DATA PERSONAL
// ====================================================================
const profileForm = useForm({
  name: props.user.name,
  username: props.user.username,
  email: props.user.email,
  phone_number: props.user.phone_number,
});

const userInitial = computed(() => {
  return props.user.name ? props.user.name.charAt(0).toUpperCase() : "U";
});

const submitProfile = () => {
  profileForm.post("/user/setting/profile", {
    preserveScroll: true,
    onSuccess: () => {
      Toast.fire({
        icon: 'success',
        title: 'Data personal diperbarui!'
      });
    },
  });
};

// ====================================================================
// 3. LOGIKA KATA SANDI
// ====================================================================
const passwordForm = useForm({
  current_password: "",
  password: "",
  password_confirmation: "",
});

const submitPassword = () => {
  passwordForm.put("/user/setting/profile/password", {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset();
      Toast.fire({
        icon: 'success',
        title: 'Kata sandi berhasil diubah!'
      });
    },
    onError: () => {
       Toast.fire({
        icon: 'error',
        title: 'Gagal mengubah kata sandi. Periksa kembali form Anda.'
      });
    }
  });
};
</script>

<template>
    <UserLayout title="Profil Saya">
        <div class="px-6 bg-tertiary-800 min-h-screen">
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8"
            >
                <div>
                    <h1
                        class="text-3xl md:text-4xl font-extrabold text-primary tracking-tight mb-2"
                    >
                        Setting Profile
                    </h1>
                    <p class="text-secondary-200 font-medium text-md">
                        Update Data diri Anda
                    </p>
                </div>               
            </div>
            <div
                class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8"
            >
                <div class="lg:col-span-4 space-y-6">
                    <div
                        class="bg-white rounded-[1.5rem] p-8 flex flex-col mt-16 items-center shadow-sm"
                    >
                        <div class="relative mb-6">
                            <input
                                type="file"
                                ref="avatarInput"
                                class="hidden"
                                accept="image/jpeg, image/png, image/webp, image/jpg"
                                @change="handleAvatarChange"
                            />

                            <div
                                class="lg:w-44 lg:h-44 w-36 h-36 shadow-xl rounded-full overflow-hidden -mt-28 relative group"
                            >
                                <img
                                    v-if="avatarPreview"
                                    :src="avatarPreview"
                                    class="w-full h-full rounded-full object-cover shadow-xl bg-gray-100 opacity-80"
                                />

                                <img
                                    v-else-if="user.avatar"
                                    :src="user.avatar"
                                    class="w-full h-full rounded-full object-cover bg-gray-100"
                                />

                                <div
                                    v-else
                                    class="w-full h-full rounded-full flex items-center justify-center bg-primary text-white text-5xl font-bold uppercase"
                                >
                                    {{ userInitial }}
                                </div>
                            </div>

                            <button
                                v-if="!avatarPreview"
                                @click="triggerAvatarUpload"
                                class="absolute bottom-1 right-1 bg-primary text-white p-2 rounded-full border-4 border-white hover:bg-primary-100 transition"
                            >
                                <Camera class="w-4 h-4" />
                            </button>
                        </div>

                        <h2 class="text-2xl font-bold text-primary">
                            {{ user.name }}
                        </h2>
                        <p class="text-sm text-secondary-200 mb-5">
                            Kotak Penyimpanan <strong>Smartbox - {{ user.letter_box }}</strong>
                        </p>

                        <div class="w-full flex gap-3">
                            <button
                                v-if="!avatarPreview"
                                @click="triggerAvatarUpload"
                                class="w-full py-3 bg-tertiary-700 text-primary font-bold rounded-xl hover:bg-tertiary-600 transition"
                            >
                                Ubah Foto Profil
                            </button>

                            <template v-else>
                                <button
                                    @click="cancelAvatar"
                                    :disabled="avatarForm.processing"
                                    class="w-1/3 py-3 bg-neutral-200 text-tertiary-200 font-bold rounded-xl hover:bg-neutral-300 transition disabled:opacity-50"
                                >
                                    Batal
                                </button>
                                <button
                                    @click="submitAvatar"
                                    :disabled="avatarForm.processing"
                                    class="w-2/3 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-100 transition disabled:opacity-50"
                                >
                                    {{
                                        avatarForm.processing
                                            ? "Menyimpan..."
                                            : "Simpan Foto"
                                    }}
                                </button>
                            </template>
                        </div>
                                            <div
                        class="bg-primary rounded-[2rem] p-8 mt-4 flex flex-col justify-between items-start gap-6"
                    >
                        <div>
                            <h4 class="text-lg font-bold text-white mb-2">
                                Keamanan Perangkat
                            </h4>
                            <p
                                class="text-sm text-secondary-500 leading-relaxed"
                            >
                                Kelola PIN  Anda untuk akses  ke
                                unit SmartBox Anda. Pastikan PIN bersifat
                                rahasia.
                            </p>
                        </div>
                        <a 
                            href="/user/setting/pin"
                            class="w-full bg-white text-primary px-6 py-3 rounded-xl font-bold flex justify-center items-center gap-2 hover:bg-neutral-100 transition"
                        >
                            <RotateCcw class="w-4 h-4" /> Reset PIN Akses
                      </a>
                    </div>
                    </div>


                </div>

                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-xl font-bold text-primary">
                                Data Personal
                            </h3>
                            <User class="text-primary w-6 h-6" />
                        </div>
                        <form
                            @submit.prevent="submitProfile"
                            class="grid grid-cols-1 md:grid-cols-2 gap-6"
                        >
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-bold text-primary uppercase ml-1"
                                    >Nama Lengkap</label
                                >
                                <input
                                    v-model="profileForm.name"
                                    type="text"
                                    class="w-full bg-tertiary-800 border-none rounded-xl p-3 focus:ring-2 focus:ring-primary text-sm"
                                />
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-bold text-primary uppercase ml-1"
                                    >Username</label
                                >
                                <input
                                    v-model="profileForm.username"
                                    type="text"
                                    class="w-full bg-tertiary-800 border-none rounded-xl p-3 focus:ring-2 focus:ring-primary text-sm"
                                />
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-bold text-primary uppercase ml-1"
                                    >Email</label
                                >
                                <input
                                    v-model="profileForm.email"
                                    type="email"
                                    class="w-full bg-tertiary-800 border-none rounded-xl p-3 focus:ring-2 focus:ring-primary text-sm"
                                />
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-bold text-primary uppercase ml-1"
                                    >Nomor Telepon</label
                                >
                                <input
                                    v-model="profileForm.phone_number"
                                    type="text"
                                    class="w-full bg-tertiary-800 border-none rounded-xl p-3 focus:ring-2 focus:ring-primary text-sm"
                                />
                            </div>
                            <div class="md:col-span-2 pt-4">
                                <button
                                    type="submit"
                                    :disabled="profileForm.processing"
                                    class="bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-primary-100 transition"
                                >
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                        <h3 class="text-xl font-bold text-primary mb-8">
                            Ubah Kata Sandi
                        </h3>
                        <form
                            @submit.prevent="submitPassword"
                            class="space-y-6"
                        >
                            <div class="space-y-2">
                                <label
                                    class="text-sm font-bold text-primary uppercase ml-1"
                                    >Kata Sandi Lama</label
                                >
                                <input
                                    v-model="passwordForm.current_password"
                                    type="password"
                                    class="w-full bg-tertiary-800 border-none rounded-xl p-3 focus:ring-2 focus:ring-primary text-sm"
                                />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label
                                        class="text-sm font-bold text-primary uppercase ml-1"
                                        >Kata Sandi Baru</label
                                    >
                                    <input
                                        v-model="passwordForm.password"
                                        type="password"
                                        placeholder="Minimal 8 karakter"
                                        class="w-full bg-tertiary-800 border-none rounded-xl p-3 focus:ring-2 focus:ring-primary text-sm"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-sm font-bold text-primary uppercase ml-1"
                                        >Konfirmasi Kata Sandi Baru</label
                                    >
                                    <input
                                        v-model="
                                            passwordForm.password_confirmation
                                        "
                                        type="password"
                                        class="w-full bg-tertiary-800 border-none rounded-xl p-3 focus:ring-2 focus:ring-primary text-sm"
                                    />
                                </div>
                            </div>
                            <div
                                class="flex justify-between items-center pt-4 mt-2"
                            >
                            <div></div>
                                <button
                                    type="submit"
                                    :disabled="passwordForm.processing"
                                    class="bg-tertiary-100 text-white px-8 py-3 rounded-xl font-bold hover:bg-tertiary-200 transition"
                                >
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
