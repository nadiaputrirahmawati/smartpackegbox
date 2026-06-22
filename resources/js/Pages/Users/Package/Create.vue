<template>
    <UserLayout>
        
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-primary tracking-tight mb-2">Tambah Paket</h1>
            <p class="text-secondary-200 font-medium text-sm">
                Daftarkan paket baru ke dalam sistem Sovereign Core Anda.
            </p>
        </div>

        <form @submit.prevent="submitPackage" class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start pb-8">
            
            <div class="xl:col-span-8 bg-white rounded-[2rem] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-secondary-600">
                
                <div class="mb-8">
                    <label class="block text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-2">Nomor Resi (Tracking Number)</label>
                    <div class="relative flex items-center">
                        <div class="absolute left-5 text-secondary-200">
                            <Box class="w-5 h-5" />
                        </div>
                        <input v-model="form.tracking_number" type="text" required placeholder="Contoh: JX123456789"
                            class="w-full bg-[#f4f7fb] border-none rounded-xl pl-14 pr-5 py-4 text-sm font-bold text-primary focus:ring-2 focus:ring-tertiary-500 outline-none transition-all uppercase"
                        />
                    </div>
                    <p v-if="form.errors.tracking_number" class="text-danger text-xs mt-1">{{ form.errors.tracking_number }}</p>
                </div>

                <div class="mb-8 ">
                    <label class="block text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-2">Metode Pembayaran</label>
                    <div class="flex gap-4">
                        <button type="button" @click="setPaymentType('prepaid')"
                            class="flex-1 py-4 rounded-xl font-bold text-sm transition-all flex justify-center items-center gap-2 border-2"
                            :class="form.payment_type === 'prepaid' ? 'bg-[#f4f7fb] border-primary text-primary' : 'bg-transparent border-transparent text-gray-400 hover:bg-gray-50'">
                            <CreditCard class="w-5 h-5" /> Prepaid
                        </button>
                        <button type="button" @click="setPaymentType('cod')"
                            class="flex-1 py-4 rounded-xl font-bold text-sm transition-all flex justify-center items-center gap-2 border-2"
                            :class="form.payment_type === 'cod' ? 'bg-primary border-primary text-white shadow-lg shadow-primary/20' : 'bg-[#f4f7fb] border-transparent text-secondary-200 hover:bg-gray-100'">
                            <Wallet class="w-5 h-5" /> COD
                        </button>
                    </div>
                </div>

                <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 -translate-y-4" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-4">
                    <div v-if="form.payment_type === 'cod'" class="space-y-8 border-t border-gray-100 pt-8">
                        
                        <div>
                            <label class="block text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-3">Pilih Slot Uang (Money Slots)</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <button v-for="slot in slots" :key="slot.id" type="button" 
                                    @click="selectSlot(slot)"
                                    :disabled="slot.status === 'filled'"
                                    class="py-4 rounded-xl font-bold text-xs flex flex-col items-center justify-center gap-1 border-2 transition-all disabled:cursor-not-allowed"
                                    :class="getSlotClass(slot)">
                                    
                                    <Lock v-if="slot.status === 'filled'" class="w-4 h-4 mb-1" />
                                    <CheckCircle2 v-else-if="form.slot_id === slot.id" class="w-4 h-4 mb-1" />
                                    <Wallet v-else class="w-4 h-4 mb-1" />
                                    
                                    <span>SLOT {{ slot.slot_number }}</span>
                                    <span class="text-[8px] uppercase tracking-widest" :class="slot.status === 'filled' ? 'text-red-400' : 'opacity-70'">
                                        {{ slot.status === 'filled' ? 'FULL' : 'READY' }}
                                    </span>
                                </button>
                            </div>
                            <p v-if="form.errors.slot_id" class="text-danger text-xs mt-2">{{ form.errors.slot_id }}</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-2">Nominal Uang (Amount)</label>
                            <div class="relative flex items-center">
                                <div class="absolute left-5 font-extrabold text-secondary-200">Rp</div>
                                <input v-model="form.amount" type="number" min="1" required
                                    class="w-full bg-[#f4f7fb] border-none rounded-xl pl-12 pr-5 py-4 text-sm font-bold text-primary focus:ring-2 focus:ring-tertiary-500 outline-none transition-all"
                                />
                            </div>
                            <p v-if="form.errors.amount" class="text-danger text-xs mt-1">{{ form.errors.amount }}</p>
                        </div>
                    </div>
                </transition>

            </div>

            <div class="xl:col-span-4 space-y-6 ">
                
                <div class="bg-primary rounded-3xl p-8 text-white shadow-xl relative overflow-hidden hidden lg:block">
                    <div class="flex justify-between items-start mb-6 relative z-10">
                        <Box class="w-6 h-6 text-tertiary-200" />
                        <span class="bg-white/10 px-3 py-1 rounded-full text-[9px] font-extrabold tracking-widest uppercase backdrop-blur-sm">
                            Draft Mode
                        </span>
                    </div>
                    
                    <p class="text-[9px] font-extrabold text-tertiary-300 tracking-widest uppercase mb-1">
                        Konfirmasi Pembayaran
                    </p>
                    <h2 class="text-3xl font-extrabold mb-8 relative z-10">
                        {{ form.payment_type === 'cod' ? 'COD Entry' : 'Prepaid Entry' }}
                    </h2>

                    <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium">Status Slot</span>
                            <span class="font-bold text-white">
                                {{ form.payment_type === 'prepaid' ? 'Tidak Dibutuhkan' : (selectedSlotName || 'Belum Dipilih') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium">Sistem Keamanan</span>
                            <div class="flex items-center gap-1 font-bold text-white">
                                <ShieldCheck class="w-4 h-4 text-emerald-400" /> Encrypted
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 flex space-x-3">
                    <button @click="submitPackage" :disabled="form.processing" class="w-full bg-[#3c5f83] hover:bg-[#2a4563] text-white py-4 rounded-full font-bold text-sm transition-all flex justify-center items-center gap-2 shadow-lg shadow-[#3c5f83]/20 disabled:opacity-50">
                        <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                        Simpan Paket
                    </button>
                    <Link href="/deliveries" class="w-full bg-tertiary-600 h-14 hover:bg-tertiary-500 text-primary py-4 rounded-full font-bold text-sm transition-all flex justify-center items-center">
                        Batal
                    </Link>
                </div>

            </div>
        </form>

    </UserLayout>
</template>

<script setup>
import { computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import { 
    Box, CreditCard, Wallet, Lock, CheckCircle2, 
    ShieldCheck, Shield, Loader2 
} from '@lucide/vue';

// Data slot dikirim dari Controller
const props = defineProps({
    slots: {
        type: Array,
        required: true,
        default: () => []
    }
});

const form = useForm({
    tracking_number: '',
    payment_type: 'prepaid', // Default Prepaid
    slot_id: null,
    amount: 0,
});

// Fungsi untuk mengganti tipe pembayaran
const setPaymentType = (type) => {
    form.payment_type = type;
    // Jika ganti ke prepaid, reset nilai COD
    if (type === 'prepaid') {
        form.slot_id = null;
        form.amount = 0;
    }
};

// Fungsi untuk memilih slot uang (hanya untuk COD)
const selectSlot = (slot) => {
    if (slot.status !== 'filled') {
        form.slot_id = slot.id;
    }
};

// Mendapatkan nama slot untuk ditampilkan di Summary Card
const selectedSlotName = computed(() => {
    if (!form.slot_id) return null;
    const slot = props.slots.find(s => s.id === form.slot_id);
    return slot ? `Slot ${slot.slot_number} Active` : null;
});

// Dynamic styling untuk tombol Slot
const getSlotClass = (slot) => {
    if (slot.status === 'filled') {
        return 'bg-gray-50 border-gray-100 text-gray-300 border-dashed';
    }
    if (form.slot_id === slot.id) {
        return 'bg-blue-50 border-blue-400 text-blue-600';
    }
    return 'bg-[#f4f7fb] border-transparent text-secondary-200 hover:bg-gray-100';
};

const submitPackage = () => {
    form.post('/user/packages', {
        preserveScroll: true,
    });
};
</script>