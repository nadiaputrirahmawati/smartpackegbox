<template>
    <AdminLayout>
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <Link href="/admin/users" class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-[#1e233b] transition-colors mb-2">
                    <ArrowLeft class="w-4 h-4" /> Kembali ke Daftar Pengguna
                </Link>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#1e233b] tracking-tight">Tambah Pengguna Baru</h1>
                <p class="text-gray-500 font-medium text-sm mt-1">
                    Kelola kredensial keamanan dan akses box untuk pengguna baru.
                </p>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto mt-4 md:mt-0">
                <Link href="/admin/users" class="flex-1 md:flex-none text-center shadow-md bg-tertiary-700 hover:bg-tertiary-600 text-[#395c80] px-8 py-3 rounded-full font-bold text-sm transition-colors">
                    Batal
                </Link>
                <button @click="submit" :disabled="form.processing || availableBoxes.length === 0" 
                    class="flex-1 md:flex-none bg-primary hover:bg-[#2a314d] text-white px-8 py-3 rounded-full font-bold text-sm transition-all shadow-lg shadow-blue-900/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                    Simpan Perubahan
                </button>
            </div>
        </div>

        <div v-if="availableBoxes.length === 0" class="mb-8 bg-red-50 border border-red-100 rounded-2xl p-6 flex items-start gap-4 shadow-sm">
            <AlertTriangle class="w-6 h-6 text-red-500 shrink-0 mt-0.5" />
            <div>
                <h4 class="text-red-700 font-bold text-base mb-1">Kapasitas Sistem Penuh</h4>
                <p class="text-red-600 text-sm font-medium">Semua slot Smartbox saat ini sedang digunakan. Anda tidak dapat mendaftarkan pengguna baru hingga ada slot yang tersedia.</p>
            </div>
        </div>

        <form @submit.prevent="submit" class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
            
            <div class="xl:col-span-8 space-y-6">
                
                <div class="bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100">
                    <h2 class="text-lg font-extrabold text-[#1e233b] flex items-center gap-3 mb-6">
                        <Contact class="w-5 h-5 text-[#395c80]" /> Identitas & Kredensial
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 tracking-widest uppercase mb-2">Nama Lengkap</label>
                            <input v-model="form.name" type="text" placeholder="Masukkan nama..." required :disabled="availableBoxes.length === 0"
                                class="w-full bg-tertiary-700 border-none rounded-2xl px-5 py-4 text-sm font-semibold text-[#1e233b] focus:ring-2 focus:ring-[#b2d5fe] outline-none transition-all placeholder-gray-400 disabled:opacity-50"
                            />
                            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1 font-medium">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 tracking-widest uppercase mb-2">Username <span class="normal-case font-normal">(Otomatis)</span></label>
                            <input v-model="form.username" type="text" readonly
                                class="w-full bg-tertiary-700 border-none rounded-2xl px-5 py-4 text-sm font-semibold text-gray-500 outline-none cursor-not-allowed"
                            />
                            <p v-if="form.errors.username" class="text-red-500 text-sm mt-1 font-medium">{{ form.errors.username }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 tracking-widest uppercase mb-2">Email</label>
                            <input v-model="form.email" type="email" placeholder="contoh@email.com" required :disabled="availableBoxes.length === 0"
                                class="w-full bg-tertiary-700 border-none rounded-2xl px-5 py-4 text-sm font-semibold text-[#1e233b] focus:ring-2 focus:ring-[#b2d5fe] outline-none transition-all placeholder-gray-400 disabled:opacity-50"
                            />
                            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1 font-medium">{{ form.errors.email }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 tracking-widest uppercase mb-2">Nomor Handphone</label>
                            <input v-model="form.phone_number" type="tel" placeholder="0812xxxxxx" :disabled="availableBoxes.length === 0"
                                class="w-full bg-tertiary-700 border-none rounded-2xl px-5 py-4 text-sm font-semibold text-[#1e233b] focus:ring-2 focus:ring-[#b2d5fe] outline-none transition-all placeholder-gray-400 disabled:opacity-50"
                            />
                            <p v-if="form.errors.phone_number" class="text-red-500 text-sm mt-1 font-medium">{{ form.errors.phone_number }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100">
                    <h2 class="text-lg font-extrabold text-[#1e233b] flex items-center gap-3 mb-6">
                        <Box class="w-5 h-5 text-[#395c80]" /> Alokasi Nomor Slot
                    </h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative" :class="!availableBoxes.includes('A') ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer'">
                            <input type="radio" v-model="form.letter_box" value="A" name="letter_box" :disabled="!availableBoxes.includes('A')" class="peer sr-only" />
                            <div class="p-6 rounded-2xl border-2 border-transparent bg-tertiary-700 text-center peer-checked:bg-[#1e233b] peer-checked:text-white transition-all">
                                <p class="text-[10px] font-bold uppercase tracking-widest mb-1 opacity-70">
                                    {{ availableBoxes.includes('A') ? 'Tersedia' : 'Terpakai' }}
                                </p>
                                <h3 class="text-xl font-extrabold">Slot A</h3>
                            </div>
                        </label>
                        
                        <label class="relative" :class="!availableBoxes.includes('B') ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer'">
                            <input type="radio" v-model="form.letter_box" value="B" name="letter_box" :disabled="!availableBoxes.includes('B')" class="peer sr-only" />
                            <div class="p-6 rounded-2xl border-2 border-transparent bg-tertiary-700 text-center peer-checked:bg-[#1e233b] peer-checked:text-white transition-all">
                                <p class="text-[10px] font-bold uppercase tracking-widest mb-1 opacity-70">
                                    {{ availableBoxes.includes('B') ? 'Tersedia' : 'Terpakai' }}
                                </p>
                                <h3 class="text-xl font-extrabold">Slot B</h3>
                            </div>
                        </label>
                    </div>
                    <p v-if="form.errors.letter_box" class="text-red-500 text-sm mt-4 font-medium">{{ form.errors.letter_box }}</p>
                </div>
            </div>

            <div class="xl:col-span-4 space-y-6 sticky top-8">
                <div class="bg-primary rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
                    <h3 class="text-lg font-bold mb-2 relative z-10">  <CircleUserRound class="opacity-65"/> Profile Preview</h3>
                    <div class="space-y-3 relative z-10">
                        <div class="flex items-center gap-3 text-sm">
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                        </div>
                    </div>
                    <Lock class="w-32 h-32 text-white/[0.05] absolute -bottom-6 -right-6 pointer-events-none" />
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 flex flex-col items-center text-center">
                    <div class="relative w-24 h-24 rounded-full bg-primary mb-4 shadow-lg flex items-center justify-center text-white text-3xl font-bold">
                        {{ form.name ? form.name.charAt(0).toUpperCase() : '?' }}
                    </div>
                    
                    <h3 class="text-2xl font-extrabold text-primary mb-1">{{ form.name || 'Nama Pengguna' }}</h3>
                    <p class="text-[#395c80] font-medium text-sm mb-6">@{{ form.username || 'username' }}</p>

                    <div class="w-full bg-[#f4f7fb] rounded-2xl p-4 text-left mb-6 group relative">
                        <p class="text-[9px] font-extrabold text-gray-400 tracking-widest uppercase mb-1">Generated Password / PIN</p>
                        <p class="font-bold text-primary text-sm font-mono truncate pr-6">{{ form.password || '******' }}</p>
                        <button type="button" @click="copyCredentials" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors" title="Copy to clipboard">
                            <Copy class="w-4 h-4" />
                        </button>
                    </div>

                    <div class="w-full flex justify-between items-center border-t border-gray-100 pt-6">
                        <div class="text-left">
                            <p class="text-[9px] font-extrabold text-gray-400 tracking-widest uppercase mb-1">Slot Box</p>
                            <p class="font-bold text-[#1e233b] text-sm">{{ form.letter_box ? 'Slot ' + form.letter_box : '-' }}</p>
                        </div>
                    </div>
                </div>



            </div>
        </form>

    </AdminLayout>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
    ArrowLeft, AlertTriangle, Loader2, Contact, Box,
    CheckCircle2, XCircle, Copy, ShieldCheck, Check, Lock, CircleUserRound
} from '@lucide/vue';

const props = defineProps({
    availableBoxes: {
        type: Array,
        required: true,
        default: () => []
    }
});

const randomSuffix = ref('');

// Generate 3 digit acak untuk Username dan 5 digit acak untuk PIN saat komponen dimuat
onMounted(() => {
    randomSuffix.value = Math.floor(100 + Math.random() * 900).toString();
});

const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    status: 'active', // Setel default ke aktif sesuai desain baru
    letter_box: '',
    phone_number: '',
});

// Watcher untuk Generate Username & Password berdasarkan Nama
watch(() => form.name, (newName) => {
    if (newName) {
        const cleanPrefix = newName.replace(/[^a-zA-Z]/g, '').substring(0, 5).toLowerCase();
        const generatedString = `${cleanPrefix}_${randomSuffix.value}`;
        
        form.username = generatedString;
        form.password = generatedString; // Password sama persis dengan username
    } else {
        form.username = '';
        form.password = '';
    }
});

// Fitur Copy Kredensial untuk Admin
const copyCredentials = () => {
    const textToCopy = `Username/Password: ${form.username}\nPIN: ${form.pin}`;
    navigator.clipboard.writeText(textToCopy).then(() => {
        alert('Kredensial berhasil disalin!');
    });
};

const submit = () => {
    form.post('/admin/users', {
        preserveScroll: true,
    });
};
</script>