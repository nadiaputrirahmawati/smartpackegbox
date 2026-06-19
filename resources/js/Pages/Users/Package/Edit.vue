<template>
    <UserLayout>
        <div class="mb-8">
            <div
                class="flex items-center gap-2 text-sm font-bold text-gray-500 mb-2"
            >
                <Link
                    href="/user/packages"
                    class="hover:text-primary transition-colors"
                    >Daftar Paket</Link
                >
                <ChevronRight class="w-4 h-4" />
                <span class="text-primary">{{ package.tracking_number }}</span>
            </div>
            <h1
                class="text-3xl md:text-4xl font-extrabold text-primary tracking-tight"
            >
                Detail Pembayaran Paket
            </h1>
             <p class="text-secondary-200 font-medium text-sm">
                Perbarui detail paket dan manajemen metode pembayaran.
            </p>
        </div>

        <form
            @submit.prevent="submitPackage"
            class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start pb-8"
        >
            <div
                class="xl:col-span-8 bg-white rounded-[2rem] p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-secondary-600"
            >
                <div class="mb-8">
                    <label
                        class="block text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-2"
                        >Nomor Resi (Tracking Number)</label
                    >
                    <div class="relative flex items-center">
                        <div class="absolute left-5 text-secondary-200">
                            <Box class="w-5 h-5" />
                        </div>
                        <input
                            v-model="form.tracking_number"
                            type="text"
                            class="w-full bg-[#f4f7fb] border-none rounded-xl pl-14 pr-5 py-4 text-sm font-bold text-primary focus:ring-2 focus:ring-tertiary-500 outline-none transition-all uppercase"
                        />
                    </div>
                    <p
                        v-if="form.errors.tracking_number"
                        class="text-danger text-xs mt-1"
                    >
                        {{ form.errors.tracking_number }}
                    </p>
                </div>

                <div class="mb-8">
                    <label
                        class="block text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-2"
                        >Metode Pembayaran</label
                    >
                    <div class="flex gap-4">
                        <button
                            type="button"
                            @click="setPaymentType('prepaid')"
                            :disabled="!canSwitchToPrepaid"
                            class="flex-1 py-4 rounded-xl font-bold text-sm transition-all flex justify-center items-center gap-2 border-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="
                                form.payment_type === 'prepaid'
                                    ? 'bg-[#f4f7fb] border-primary text-primary'
                                    : 'bg-transparent border-transparent text-gray-400 hover:bg-gray-50'
                            "
                        >
                            <CreditCard class="w-5 h-5" /> Pembayaran Non-COD
                        </button>
                        <button
                            type="button"
                            @click="setPaymentType('cod')"
                            class="flex-1 py-4 rounded-xl font-bold text-sm transition-all flex justify-center items-center gap-2 border-2"
                            :class="
                                form.payment_type === 'cod'
                                    ? 'bg-primary border-primary text-white shadow-lg shadow-primary/20'
                                    : 'bg-[#f4f7fb] border-transparent text-secondary-200 hover:bg-gray-100'
                            "
                        >
                            <Wallet class="w-5 h-5" /> Pembayaran COD (Bayar di Tempat)
                        </button>
                    </div>
                    <p
                        v-if="
                            !canSwitchToPrepaid && form.payment_type === 'cod'
                        "
                        class="text-[10px] text-red-500 mt-2 font-bold flex items-center gap-1"
                    >
                        <AlertCircle class="w-3 h-3" /> Paket tidak dapat diubah
                        ke Prepaid karena pembayaran sudah diproses.
                    </p>
                </div>

                <transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="opacity-0 -translate-y-4"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-4"
                >
                    <div
                        v-if="form.payment_type === 'cod'"
                        class="space-y-8 border-t border-gray-100 pt-8"
                    >
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <label
                                    class="block text-[10px] font-extrabold text-gray-400 tracking-widest uppercase"
                                    >Pilih Slot Uang</label
                                >
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <button
                                    v-for="slot in slots"
                                    :key="slot.id"
                                    type="button"
                                    @click="selectSlot(slot)"
                                    :disabled="isSlotDisabled(slot)"
                                    class="py-4 rounded-xl font-bold text-xs flex flex-col items-center justify-center gap-1 border-2 transition-all disabled:cursor-not-allowed"
                                    :class="getSlotClass(slot)"
                                >
                                    <Lock
                                        v-if="isSlotDisabled(slot)"
                                        class="w-4 h-4 mb-1"
                                    />
                                    <CheckCircle2
                                        v-else-if="form.slot_id === slot.id"
                                        class="w-4 h-4 mb-1"
                                    />
                                    <Wallet v-else class="w-4 h-4 mb-1" />

                                    <span>SLOT {{ slot.slot_number }}</span>
                                    <span
                                        class="text-[8px] uppercase tracking-widest"
                                        :class="
                                            isSlotDisabled(slot)
                                                ? 'text-red-400'
                                                : 'opacity-70'
                                        "
                                    >
                                        {{
                                            isSlotDisabled(slot)
                                                ? "FULL"
                                                : "READY"
                                        }}
                                    </span>
                                </button>
                            </div>
                            <p
                                v-if="form.errors.slot_id"
                                class="text-danger text-xs mt-2"
                            >
                                {{ form.errors.slot_id }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-extrabold text-gray-400 tracking-widest uppercase mb-2"
                                >Nominal Uang (Amount)</label
                            >
                            <div class="relative flex items-center">
                                <div
                                    class="absolute left-5 font-extrabold text-secondary-200"
                                >
                                    Rp
                                </div>
                                <input
                                    v-model="form.amount"
                                    type="number"
                                    min="1"
                                    :disabled="
                                        props.package.payment_status !==
                                        'pending'
                                    "
                                    class="w-full bg-[#f4f7fb] border-none rounded-xl pl-12 pr-5 py-4 text-sm font-bold text-primary focus:ring-2 focus:ring-tertiary-500 outline-none transition-all"
                                />
                            </div>
                            <p
                                v-if="form.errors.amount"
                                class="text-danger text-xs mt-1"
                            >
                                {{ form.errors.amount }}
                            </p>
                        </div>
                    </div>
                </transition>
            </div>

            <div class="xl:col-span-4 space-y-6">
                <div
                    class="bg-primary rounded-3xl p-8 text-white shadow-xl relative overflow-hidden"
                >
                    <div
                        class="flex justify-between items-start mb-6 relative z-10"
                    >
                        <Box class="w-8 h-8 text-tertiary-200" />
                        <span
                            class="bg-white/10 px-3 py-1 rounded-full text-[9px] font-extrabold tracking-widest uppercase backdrop-blur-sm"
                        >
                            Edit Paket
                        </span>
                    </div>

                    <p
                        class="text-sm font-extrabold text-tertiary-300 tracking-widest uppercase mb-1"
                    >
                        Konfirmasi Pembayaran
                    </p>
                    <h2 class="text-3xl font-extrabold mb-8 relative z-10">
                        {{
                            form.payment_type === "cod"
                                ? "COD (Bayar di Tempat)"
                                : "Pembayaran Non-COD"
                        }}
                    </h2>

                    <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium"
                                >Status Slot</span
                            >
                            <span class="font-bold text-white">
                                {{
                                    form.payment_type === "prepaid"
                                        ? "Tidak Dibutuhkan"
                                        : selectedSlotName || "Belum Dipilih"
                                }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium"
                                >Payment Status</span
                            >
                            <span class="font-bold capitalize text-white">{{
                                package.payment_status === "deposited" ? "Lunas" : "Belum Lunas"
                            }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 flex lg:flex-row flex-col space-x-3">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-[#3c5f83] hover:bg-[#2a4563] text-white py-4 rounded-full font-bold text-sm transition-all flex justify-center items-center gap-2 shadow-lg shadow-[#3c5f83]/20 disabled:opacity-50"
                    >
                        <Loader2
                            v-if="form.processing"
                            class="w-4 h-4 animate-spin"
                        />
                        Simpan Perubahan
                    </button>
                    <Link
                        href="/user/packages"
                        class="w-full h-13 shadow-md  bg-tertiary-700 hover:bg-[#e0e7f1] text-primary  rounded-full font-bold text-lg transition-all flex justify-center items-center"
                    >
                        Batal
                    </Link>
                </div>
            </div>
        </form>
    </UserLayout>
</template>

<script setup>
import { computed } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import UserLayout from "@/Layouts/UserLayout.vue";
import {
    Box,
    CreditCard,
    Wallet,
    Lock,
    CheckCircle2,
    ShieldCheck,
    Loader2,
    AlertCircle,
    ChevronRight
} from "@lucide/vue";

const props = defineProps({
    package: {
        type: Object,
        required: true,
    },

    slots: {
        type: Array,
        required: true,
    },
});

console.log(props.package);

const form = useForm({
    tracking_number: props.package.tracking_number,
    payment_type: props.package.payment_type,
    slot_id: props.package.slot_id,
    amount: props.package.amount,
});

// LOGIKA 1: Boleh pindah ke Prepaid?
// Hanya di-disable jika asalnya COD dan status payment BUKAN pending (sudah ditaruh uangnya/diambil)
const canSwitchToPrepaid = computed(() => {
    if (
        props.package.payment_type === "cod" &&
        props.package.payment_status !== "pending"
    ) {
        return false;
    }
    return true;
});

const setPaymentType = (type) => {
    if (type === "prepaid" && !canSwitchToPrepaid.value) return; // Proteksi tambahan

    form.payment_type = type;

    if (type === "prepaid") {
        form.slot_id = null;
        form.amount = 0;
    }
};

// LOGIKA 2: Apakah slot di-disable?
// Slot disabled JIKA statusnya 'filled' DAN itu BUKAN slot milik paket ini
const isSlotDisabled = (slot) => {
    return (
        props.package?.payment_status !== "pending" ||
        (slot.status === "filled" && slot.id !== props.package?.slot_id)
    );
};

const selectSlot = (slot) => {
    if (!isSlotDisabled(slot)) {
        form.slot_id = slot.id;
    }
};

const selectedSlotName = computed(() => {
    if (!form.slot_id) return null;
    const slot = props.slots.find((s) => s.id === form.slot_id);
    return slot ? `Slot ${slot.slot_number} Active` : null;
});

const getSlotClass = (slot) => {
    if (isSlotDisabled(slot)) {
        return "bg-gray-50 border-gray-100 text-gray-300 border-dashed";
    }
    if (form.slot_id === slot.id) {
        return "bg-blue-50 border-blue-400 text-blue-600";
    }
    return "bg-[#f4f7fb] border-transparent text-secondary-200 hover:bg-gray-100";
};

const submitPackage = () => {
    // Karena update data, gunakan metode PUT
    form.put(`/user/packages/${props.package.id}`, {
        preserveScroll: true,
    });
};
</script>
