<template>
    <div
        class="min-h-screen flex items-center justify-center bg-[#f4f7fc] relative font-sans p-4 sm:p-6 lg:p-8 overflow-hidden"
    >
        <div
            class="bg-white rounded-[2rem] shadow-[0_20px_60px_rgba(10,17,58,0.08)] flex w-full max-w-[1000px] z-10 relative overflow-hidden min-h-[600px]"
        >
            <div
                class="hidden md:flex md:w-[45%] bg-[#0a113a] bg-gradient-to-b from-[#0a113a] to-[#04081c] p-12 flex-col justify-center relative overflow-hidden text-white"
            >
                <svg
                    class="absolute -bottom-16 -right-12 w-[400px] h-[400px] text-white opacity-[0.09] pointer-events-none"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"
                    ></path>
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 20.5c-4.5-1.5-6.5-5-6.5-8.5v-6l6.5-2.5 6.5 2.5v6c0 3.5-2 7-6.5 8.5z"
                        opacity="0.5"
                    ></path>
                </svg>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-16">
                        <div
                            class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-[#0a113a]"
                        >
                            <Package class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-bold tracking-tight"
                            >SmartBox</span
                        >
                    </div>

                    <h1
                        class="text-4xl font-semibold mb-6 leading-[1.3] font-headline text-[#f7f9ff]"
                    >
                        Keamanan <br /><span class="text-[#8ba4eb]"
                            >Package.</span
                        >
                    </h1>
                    <p
                        class="text-[#7b81af] text-[15px] leading-relaxed max-w-sm"
                    >
                        Sovereign Core memberikan kendali penuh atas ekosistem
                        pintar Anda dengan standar enkripsi militer.
                    </p>
                </div>
            </div>

            <div
                class="w-full md:w-[55%] p-8 sm:p-12 lg:p-16 flex flex-col justify-center bg-white relative"
            >
                <div
                    class="md:hidden flex items-center gap-3 mb-10 text-[#0a113a]"
                >
                    <div
                        class="w-10 h-10 bg-[#0a113a] rounded-xl flex items-center justify-center text-white shadow-lg"
                    >
                        <Package class="w-6 h-6" />
                    </div>
                    <span class="text-2xl font-bold tracking-tight"
                        >SmartBox</span
                    >
                </div>

                <div
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#e8f2ff] text-[#395c80] rounded-full text-[10px] font-bold tracking-widest uppercase mb-6 w-fit"
                >
                    <ShieldCheck class="w-3.5 h-3.5" />
                    Package Delivery
                </div>

                <h2
                    class="text-[28px] font-bold text-[#0a113a] mb-2 font-headline tracking-tight"
                >
                    Hallo, Selamat Datang
                </h2>
                <p class="text-[#46464e] text-sm mb-10">
                    Silahkan Login Untuk Masuk Ke Aplikasi.
                </p>

                <div
                    v-if="form.errors.username"
                    class="mb-6 bg-red-50/50 border border-red-100 rounded-lg p-4 flex items-start gap-3 transition-all duration-300"
                >
                    <div class="mt-0.5 text-red-500 bg-red-100 p-1 rounded-lg">
                        <AlertCircle class="w-4 h-4" />
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-red-800">
                            Akses Ditolak
                        </h4>
                        <p class="text-xs text-red-600 mt-0.5 font-medium">
                            {{ form.errors.username }}
                        </p>
                    </div>
                </div>

                <form @submit.prevent="submitLogin">
                    <div class="mb-5 relative">
                        <label
                            for="username"
                            class="block text-xs font-bold text-[#46464e] mb-2"
                            >Nama Pengguna</label
                        >
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"
                            >
                                <User class="w-5 h-5" />
                            </div>
                            <input
                                id="username"
                                type="text"
                                v-model="form.username"
                                class="w-full bg-[#f4f7fc] border-none rounded-xl pl-11 pr-4 py-3.5 text-sm focus:ring-2 focus:ring-[#b2d5fe] text-[#0a113a] font-medium transition-all outline-none placeholder-[#a0aabf]"
                                placeholder="Masukkan nama pengguna"
                                required
                            />
                        </div>
                    </div>

                    <div class="mb-6 relative">
                        <div class="flex justify-between items-end mb-2">
                            <label
                                for="password"
                                class="block text-xs font-bold text-[#46464e]"
                                >Kata Sandi</label
                            >
                            <a
                                href="#"
                                class="text-xs font-bold text-[#395c80] hover:text-[#0a113a] transition-colors"
                                >Lupa Kata Sandi?</a
                            >
                        </div>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400"
                            >
                                <Lock class="w-5 h-5" />
                            </div>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                class="w-full bg-[#f4f7fc] border-none rounded-xl pl-11 pr-12 py-3.5 text-sm focus:ring-2 focus:ring-[#b2d5fe] text-[#0a113a] font-medium transition-all outline-none placeholder-[#a0aabf] tracking-widest"
                                placeholder="••••••••"
                                required
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#0a113a] transition-colors"
                            >
                                <EyeOff v-if="!showPassword" class="w-5 h-5" />
                                <Eye v-else class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    <div class="mb-8 flex items-center">
                        <input
                            id="remember"
                            type="checkbox"
                            v-model="form.remember"
                            class="w-4 h-4 text-[#24496c] bg-[#f4f7fc] border-gray-300 rounded focus:ring-[#b2d5fe]"
                        />
                        <label
                            for="remember"
                            class="ml-2 text-sm font-medium text-[#46464e] select-none cursor-pointer"
                            >Ingat perangkat ini</label
                        >
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-[#24496c] bg-gradient-to-r from-[#1a3247] to-[#24496c] hover:from-[#0a113a] hover:to-[#1a3247] text-white font-bold py-3.5 rounded-xl shadow-[0_8px_20px_rgba(36,73,108,0.3)] transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center"
                    >
                        <span
                            v-if="form.processing"
                            class="flex items-center gap-2"
                        >
                            <Loader2 class="animate-spin h-5 w-5 text-white" />
                            Memproses...
                        </span>
                        <span v-else>Masuk ke Dashboard</span>
                    </button>
                </form>

                <p class="text-center text-xs text-[#77767f] font-medium mt-8">
                    Belum memiliki akun?
                    <a
                        href="#"
                        class="text-[#395c80] font-bold hover:text-[#0a113a] transition-colors ml-1"
                        >Daftar Sekarang</a
                    >
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
// Import Shield dihilangkan karena kita pakai SVG asli, sisanya tetap Lucide
import {
    Package,
    ShieldCheck,
    User,
    Lock,
    Eye,
    EyeOff,
    Loader2,
    AlertCircle,
} from "@lucide/vue";

const showPassword = ref(false);

const form = useForm({
    username: "",
    password: "",
    remember: false,
});

const submitLogin = () => {
    form.post("/login", {
        onFinish: () => form.reset("password"),
    });
};
</script>
