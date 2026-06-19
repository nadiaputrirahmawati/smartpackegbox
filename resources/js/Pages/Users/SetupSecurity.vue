<template>
    <div class="min-h-screen bg-[#f4f7fb] font-sans selection:bg-tertiary-500">
        <AdminLayout>
            <main
                class="max-w-5xl mx-auto px-4 py-12 flex flex-col items-center"
            >
                <div class="text-center mb-12">
                    <span
                        class="bg-blue-100 text-blue-700 px-4 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase mb-4 inline-block"
                    >
                        Setup Keamanan
                    </span>
                    <h1
                        class="text-3xl md:text-5xl font-extrabold text-primary mb-4 tracking-tight"
                    >
                        Keamanan Akun Pertama Kali
                    </h1>
                    <p
                        class="text-secondary-200 font-medium text-sm max-w-lg mx-auto leading-relaxed"
                    >
                        Langkah krusial untuk memastikan ekosistem IoT Anda
                        terlindungi secara maksimal sejak awal penggunaan.
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 lg:grid-cols-12 gap-8 w-full items-start"
                >
                    <div class="lg:col-span-4 space-y-6">
                        <div
                            class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 relative overflow-hidden"
                        >
                            <div
                                class="absolute -top-6 -right-6 w-24 h-24 bg-gray-50 rounded-full border border-gray-100 flex items-center justify-center"
                            >
                                <span
                                    class="text-xs font-extrabold text-gray-400 mt-4 mr-4"
                                    >33%</span
                                >
                            </div>

                            <h2
                                class="text-lg font-extrabold text-primary mb-6"
                            >
                                Status Keamanan
                            </h2>

                            <div
                                class="w-full bg-gray-100 rounded-full h-2 mb-8 relative z-10"
                            >
                                <div
                                    class="bg-blue-400 h-2 rounded-full w-1/3"
                                ></div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-5 h-5 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center"
                                    >
                                        <Check class="w-3 h-3 stroke-[3]" />
                                    </div>
                                    <span class="text-xs font-bold text-primary"
                                        >Verifikasi Identitas</span
                                    >
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-5 h-5 rounded-full bg-gray-100 border border-gray-200"
                                    ></div>
                                    <span
                                        class="text-xs font-bold text-gray-400"
                                        >Pengaturan PIN 5-Digit</span
                                    >
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-5 h-5 rounded-full bg-gray-100 border border-gray-200"
                                    ></div>
                                    <span
                                        class="text-xs font-bold text-gray-400"
                                        >Kata Sandi Utama</span
                                    >
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-primary rounded-3xl p-8 text-white shadow-xl relative overflow-hidden"
                        >
                            <Lock
                                class="w-5 h-5 text-blue-300 mb-4 relative z-10"
                            />
                            <h3 class="text-lg font-bold mb-2 relative z-10">
                                Mengapa PIN?
                            </h3>
                            <p
                                class="text-gray-400 text-xs leading-relaxed relative z-10"
                            >
                                PIN memberikan akses cepat namun aman untuk
                                kontrol fisik perangkat SmartBox Anda tanpa
                                perlu mengetik sandi panjang setiap saat.
                            </p>
                            <Box
                                class="absolute -bottom-8 -right-8 w-40 h-40 text-white/5 pointer-events-none"
                            />
                        </div>
                    </div>

                    <div class="lg:col-span-8">
                        <form
                            @submit.prevent="submitSetup"
                            class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-sm border border-gray-100"
                        >
                            <div class="mb-12">
                                <div class="flex items-center gap-4 mb-4">
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 font-extrabold text-sm flex items-center justify-center shrink-0"
                                    >
                                        1
                                    </div>
                                    <h2
                                        class="text-xl font-extrabold text-primary"
                                    >
                                        Atur PIN 5-Digit
                                    </h2>
                                </div>
                                <p class="text-sm text-gray-500 mb-6 pl-12">
                                    Gunakan angka yang mudah diingat namun sulit
                                    ditebak. Hindari kombinasi seperti '12345'.
                                </p>

                                <div class="flex gap-4 pl-12">
                                    <input
                                        v-for="(digit, index) in 5"
                                        :key="index"
                                        type="password"
                                        inputmode="numeric"
                                        maxlength="1"
                                        v-model="pinArray[index]"
                                        @input="onPinInput(index, $event)"
                                        @keydown.delete="
                                            onPinDelete(index, $event)
                                        "
                                        :ref="(el) => (pinRefs[index] = el)"
                                        class="w-12 h-12 md:w-14 md:h-14 bg-blue-50/50 border-none rounded-full text-center text-xl font-extrabold text-primary focus:ring-2 focus:ring-blue-400 outline-none transition-all shadow-inner"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.pin"
                                    class="text-danger text-xs mt-3 pl-12 font-medium"
                                >
                                    {{ form.errors.pin }}
                                </p>
                            </div>

                            <div
                                class="w-full border-t border-gray-100 mb-10 pl-12"
                            ></div>

                            <div class="mb-12">
                                <div class="flex items-center gap-4 mb-4">
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 font-extrabold text-sm flex items-center justify-center shrink-0"
                                    >
                                        2
                                    </div>
                                    <h2
                                        class="text-xl font-extrabold text-primary"
                                    >
                                        Kata Sandi Akun
                                    </h2>
                                </div>
                                <p class="text-sm text-gray-500 mb-8 pl-12">
                                    Kata sandi ini digunakan untuk masuk ke
                                    aplikasi dan mengelola pengaturan tingkat
                                    lanjut.
                                </p>

                                <div class="space-y-6 pl-12 max-w-md">
                                    <div>
                                        <label
                                            class="block text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-2"
                                            >Kata Sandi Baru</label
                                        >
                                        <div class="relative">
                                            <input
                                                v-model="form.password"
                                                :type="
                                                    showPassword
                                                        ? 'text'
                                                        : 'password'
                                                "
                                                required
                                                placeholder="Masukkan sandi minimal 8 karakter"
                                                class="w-full bg-[#f4f7fb] border-none rounded-xl pl-5 pr-12 py-4 text-sm font-bold text-primary focus:ring-2 focus:ring-blue-400 outline-none transition-all"
                                            />
                                            <button
                                                type="button"
                                                @click="
                                                    showPassword = !showPassword
                                                "
                                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors"
                                            >
                                                <EyeOff
                                                    v-if="showPassword"
                                                    class="w-4 h-4"
                                                />
                                                <Eye v-else class="w-4 h-4" />
                                            </button>
                                        </div>
                                        <p
                                            v-if="form.errors.password"
                                            class="text-danger text-xs mt-1 font-medium"
                                        >
                                            {{ form.errors.password }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-2"
                                            >Konfirmasi Kata Sandi</label
                                        >
                                        <input
                                            v-model="form.password_confirmation"
                                            type="password"
                                            required
                                            placeholder="Ulangi sandi baru Anda"
                                            class="w-full bg-[#f4f7fb] border-none rounded-xl px-5 py-4 text-sm font-bold text-primary focus:ring-2 focus:ring-blue-400 outline-none transition-all"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-6 pl-12">
                                <button
                                    type="submit"
                                    :disabled="
                                        form.processing || !isPinComplete
                                    "
                                    class="bg-primary hover:bg-primary-100 text-white px-8 py-4 rounded-full font-bold text-sm transition-all shadow-lg shadow-primary/20 disabled:opacity-50 flex items-center gap-2"
                                >
                                    <Loader2
                                        v-if="form.processing"
                                        class="w-4 h-4 animate-spin"
                                    />
                                    Selesaikan Setup
                                    <ArrowRight class="w-4 h-4 ml-1" />
                                </button>
                                <form @submit.prevent="logout">
                                    <button
                                        type="submit"
                                        class="text-sm font-bold text-secondary-200 hover:text-primary transition-colors"
                                    >
                                        Batal & Keluar
                                    </button>
                                </form>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full mt-10">
                    <div
                        class="bg-blue-50/50 border border-blue-100 rounded-full px-8 py-5 flex items-center gap-4"
                    >
                        <div
                            class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-primary shadow-sm"
                        >
                            <ShieldCheck class="w-4 h-4" />
                        </div>
                        <div>
                            <h4
                                class="font-extrabold text-primary text-sm mb-0.5"
                            >
                                Enkripsi End-to-End
                            </h4>
                            <p class="text-[10px] text-gray-500 font-medium">
                                Data keamanan Anda dienkripsi secara lokal
                                sebelum dikirim ke cloud.
                            </p>
                        </div>
                    </div>
                    <div
                        class="bg-blue-50/50 border border-blue-100 rounded-full px-8 py-5 flex items-center gap-4"
                    >
                        <div
                            class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-primary shadow-sm"
                        >
                            <Bell class="w-4 h-4" />
                        </div>
                        <div>
                            <h4
                                class="font-extrabold text-primary text-sm mb-0.5"
                            >
                                Lansiran Keamanan
                            </h4>
                            <p class="text-[10px] text-gray-500 font-medium">
                                Kami akan memberi tahu Anda jika ada aktivitas
                                login yang mencurigakan.
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </AdminLayout>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    Check,
    Lock,
    Box,
    Eye,
    EyeOff,
    ArrowRight,
    Loader2,
    ShieldCheck,
    Bell,
} from "@lucide/vue";

const showPassword = ref(false);

// PIN Input Logic (Array of 5)
const pinArray = ref(["", "", "", "", ""]);
const pinRefs = ref([]);

const isPinComplete = computed(() => pinArray.value.every((val) => val !== ""));

const onPinInput = (index, event) => {
    // Hanya izinkan angka
    const value = event.target.value.replace(/[^0-9]/g, "");
    pinArray.value[index] = value;

    // Auto focus ke next input jika terisi
    if (value && index < 4) {
        pinRefs.value[index + 1].focus();
    }
};

const onPinDelete = (index, event) => {
    // Auto focus ke previous input saat backspace dan kotak kosong
    if (!pinArray.value[index] && index > 0) {
        pinRefs.value[index - 1].focus();
    }
};

// Form Setup
const form = useForm({
    pin: "",
    password: "",
    password_confirmation: "",
});

const submitSetup = () => {
    // Gabungkan array PIN menjadi 1 string
    form.pin = pinArray.value.join("");

    form.post("/user/setup-security", {
        preserveScroll: true,
    });
};

const logout = () => {
    router.post("/logout");
};
</script>
