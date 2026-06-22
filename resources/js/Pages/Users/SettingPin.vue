<template>
    <AppLayout>
        <div class="md:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="font-bold text-3xl text-gray-800 leading-tight">
                        Keamanan & Akses PIN
                    </h2>
                    <h2
                        class="font-medium text-lg text-gray-500 leading-tight mt-1"
                    >
                        Pengaturan PIN akses untuk membuka kotak penyimpanan
                        paket (Smart Box)
                    </h2>
                </div>
            </div>
            <div
                class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 mb-8"
            >
                <div
                    class="bg-tertiary-700 p-8 rounded-3xl shadow-lg border border-tertiary-800 flex flex-col justify-between text-white relative"
                >
                    <div>
                        <p
                            class="text-lg font-bold uppercase text-secondary-100 tracking-wider mb-2"
                        >
                            PIN Akses Smartbox
                        </p>

                        <div class="flex items-start justify-between mb-8">
                            <div
                                class="font-mono md:text-2xl lg:text-3xl xl:text-4xl text-2xl font-bold text-primary tracking-normal"
                            >
                                <div v-if="props.currentPin">
                                    <div
                                        v-for="(row, rowIndex) in displayGrid"
                                        :key="rowIndex"
                                        class="flex items-center gap-3 last:mt-2"
                                    >
                                        <div
                                            v-for="(char, charIndex) in row"
                                            :key="charIndex"
                                            class="w-[1.2ch] text-center"
                                            :class="{
                                                'text-secondary-300':
                                                    char === '•',
                                            }"
                                        >
                                            <span
                                                v-if="char === '•'"
                                                class="text-secondary-300 opacity-60"
                                            >
                                                •
                                            </span>
                                            <span v-else>
                                                {{ isShown ? char : "•" }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    v-else
                                    class="text-2xl text-secondary-900 tracking-normal mt-2"
                                >
                                    Belum Diatur
                                </div>
                            </div>

                            <button
                                v-if="props.currentPin"
                                @click="toggleShowPin"
                                class="text-secondary-100 hover:text-primary transition mt-2"
                            >
                                <EyeOff v-if="!isShown" class="w-7 h-7" />
                                <Eye v-else class="w-7 h-7" />
                            </button>
                        </div>

                        <p
                            class="text-xs text-white mt-2 bg-primary-100 p-3 rounded-xl flex items-start gap-2"
                        >
                            <Shield class="w-4 h-4 flex-shrink-0" />
                            PIN ini digunakan untuk akses fisik langsung pada
                            panel kontrol SmartBox.
                        </p>
                    </div>
                </div>

                <div
                    class="bg-primary-100 p-8 rounded-3xl shadow-lg flex flex-col justify-between text-white relative overflow-hidden"
                >
                    <Shield
                        class="absolute -right-12 -bottom-12 w-48 h-48 text-primary opacity-20"
                    />
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <Shield class="w-10 h-10 text-white" />
                            <h3 class="font-bold text-xl leading-tight">
                                Informasi PIN
                            </h3>
                        </div>
                        <p class="text-secondary-600/70 text-md mb-8 max-w-md">
                            Jangan membagikan PIN kepada siapa pun, termasuk
                            orang terdekat.
                            <strong
                                >Segala bentuk penyalahgunaan akibat kelalaian
                                pengguna bukan menjadi tanggung jawab
                                kami.</strong
                            >
                        </p>
                        <div class="flex space-x-2">
                            <span class="text-secondary-600">
                                Terimakasih
                            </span>
                            <Heart />
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="max-w-7xl mx-auto bg-white p-8 rounded-3xl shadow-lg border border-neutral-200 mb-8"
            >
                <div class="flex items-center gap-3 mb-8">
                    <RefreshCw class="w-10 h-10 text-primary" />
                    <h3 class="font-bold text-xl text-primary leading-tight">
                        Perbarui PIN Akses
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-4">
                    <div>
                        <p class="text-md font-semibold text-primary mb-2">
                            PIN BARU (5 DIGIT)
                        </p>
                        <div class="flex items-center gap-3">
                            <input
                                v-for="(n, i) in 5"
                                :key="'new' + i"
                                :id="'new-' + i"
                                v-model="form.newPin[i]"
                                @input="(e) => moveToNext(e, i, 'new')"
                                type="password"
                                inputmode="numeric"
                                maxlength="1"
                                class="md:w-11 md:h-11 lg:h-14 lg:w-14 h-11 w-11 text-center text-2xl font-bold rounded-xl bg-tertiary-700 focus:border-primary focus:ring-0 text-primary transition-all"
                            />
                        </div>
                    </div>

                    <div>
                        <p class="text-md font-semibold text-primary mb-2">
                            KONFIRMASI PIN BARU
                        </p>
                        <div class="flex items-center gap-3">
                            <input
                                v-for="(n, i) in 5"
                                :key="'conf' + i"
                                :id="'conf-' + i"
                                v-model="form.confirmPin[i]"
                                @input="(e) => moveToNext(e, i, 'conf')"
                                type="password"
                                inputmode="numeric"
                                maxlength="1"
                                class="md:w-11 md:h-11 lg:h-14 lg:w-14 h-11 w-11 text-center text-2xl font-bold rounded-xl bg-tertiary-700 focus:border-primary focus:ring-0 text-primary transition-all"
                            />
                        </div>
                    </div>
                </div>

                <p
                    class="text-xs text-secondary-800 mt-2 mb-8 p-3 rounded-lg flex items-center gap-2"
                >
                    <KeyRound class="w-4 h-4 flex-shrink-0" />
                    Gunakan angka yang unik dan sulit ditebak.
                </p>

                <div class="flex justify-end mt-8">
                    <button
                        @click="handleUpdatePin"
                        :disabled="form.processing"
                        class="px-8 py-4 bg-primary text-white rounded-2xl transition font-semibold bg text-lg hover:bg-primary-100 disabled:opacity-50"
                    >
                        Simpan Perubahan PIN
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import {
    Shield,
    RefreshCw,
    Undo2,
    Eye,
    EyeOff,
    KeyRound,
    Heart,
} from "@lucide/vue";
import AppLayout from "@/Layouts/UserLayout.vue"; // Layout utama SmartBox

import Swal from "sweetalert2";

// Membuat konfigurasi dasar untuk Toast Notification
const Toast = Swal.mixin({
    toast: true,
    position: "top-end", // Muncul di pojok kanan atas
    showConfirmButton: false,
    timer: 3000, // Hilang otomatis setelah 3 detik
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});

const props = defineProps({
    currentPin: String, // Prop PIN asli (misal: '12345') dari controller
});

// State form submit PIN baru
const form = useForm({
    newPin: ["", "", "", "", ""],
    confirmPin: ["", "", "", "", ""],
});

const isShown = ref(false);

const toggleShowPin = () => {
    isShown.value = !isShown.value;
};

// =========================================================================
// LOGIKA PEMBENTUKAN GRID TAMPILAN PIN (FIX MASKING)
// Sesuai contoh image_3.png (8 • 4 • 2 \n • 9 • 5)
// =========================================================================
const displayGrid = computed(() => {
    const pin = props.currentPin || "-----"; // Default jika belum di-set

    // Baris 1 Slots: Digit 0, Pemisah, Digit 1, Pemisah, Digit 2
    const row1 = [pin[0], "•", pin[1], "•", pin[2], "•", pin[3], "•", pin[4]];

    return [row1];
});

// Pindah fokus otomatis ke kotak input berikutnya
const moveToNext = (event, index, type) => {
    if (event.target.value.length === 1 && index < 4) {
        const nextInput = document.getElementById(`${type}-${index + 1}`);
        if (nextInput) nextInput.focus();
    }
};

const handleUpdatePin = () => {
    // Ganti route('settings.pin.update') menjadi '/settings/pin/update'
    form.post("/user/setting/pin/reset", {

        onSuccess: () => {
            form.reset();
            Toast.fire({
                icon: "success",
                title: "Pin Kotak berhasil diperbarui!",
            });
        },
    });
};
</script>
