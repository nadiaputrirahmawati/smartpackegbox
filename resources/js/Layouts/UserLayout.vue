<template>
    <div class="min-h-screen flex flex-col bg-tertiary-800 font-sans">
        <header
            class="hidden md:block bg-tertiary-800/90 backdrop-blur-xl top-0 sticky z-50 border-b border-secondary-600"
        >
            <div
                class="flex justify-between items-center w-full px-8 py-4 max-w-[1920px] mx-auto"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center shadow-md"
                    >
                        <div class="w-3 h-3 bg-white rounded-sm"></div>
                    </div>
                    <span
                        class="text-xl font-extrabold tracking-tight text-primary"
                        >SmartBox</span
                    >
                </div>

                <nav
                    class="flex items-center gap-1 bg-white p-1.5 rounded-full shadow-[0_2px_15px_rgb(0,0,0,0.03)] border border-secondary-600"
                >
                    <Link
                        href="/user/dashboard"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold transition-all"
                        :class="
                            $page.url === '/user/dashboard'
                                ? 'bg-primary text-white shadow-md'
                                : 'text-secondary-200 hover:bg-tertiary-800 hover:text-primary'
                        "
                    >
                        <LayoutGrid class="w-4 h-4" /> Dashboard
                    </Link>
                    <Link
                        href="/user/packages"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold transition-all"
                        :class="
                            $page.url.startsWith('/user/packages')
                                ? 'bg-primary text-white shadow-md'
                                : 'text-secondary-200 hover:bg-tertiary-800 hover:text-primary'
                        "
                    >
                        <Package class="w-4 h-4" /> Deliveries
                    </Link>
                    <Link
                        href="/user/history/log"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold transition-all"
                        :class="
                            $page.url.startsWith('/user/history/log')
                                ? 'bg-primary text-white shadow-md'
                                : 'text-secondary-200 hover:bg-tertiary-800 hover:text-primary'
                        "
                    >
                        <History class="w-4 h-4" /> History
                    </Link>
                    <Link
                        href="/user/setting/pin"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold transition-all"
                        :class="
                            $page.url.startsWith('/user/setting/pin')
                                ? 'bg-primary text-white shadow-md'
                                : 'text-secondary-200 hover:bg-tertiary-800 hover:text-primary'
                        "
                    >
                        <Settings class="w-4 h-4" /> Settings
                    </Link>
                </nav>

                <div class="flex items-center gap-4">
                    <button
                        class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-secondary-200 hover:text-primary hover:bg-secondary-600 transition-colors border border-secondary-600 shadow-sm"
                    >
                        <Bell class="w-4 h-4" />
                    </button>

                    <div
                        class="flex items-center gap-3 pl-4 border-l border-secondary-600 relative"
                        ref="dropdownRef"
                    >
                        <div class="hidden lg:flex flex-col text-right mr-1">
                            <span
                                class="text-sm font-bold text-primary leading-tight"
                                >{{
                                    $page.props.auth?.user?.name || "User"
                                }}</span
                            >
                            <span
                                class="text-[9px] font-extrabold text-secondary-200 tracking-widest uppercase mt-0.5"
                                >Personal Node</span
                            >
                        </div>
                        <button
                            @click="toggleDropdown"
                            class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold shadow-md overflow-hidden border-2 transition-all focus:outline-none"
                            :class="
                                isDropdownOpen
                                    ? 'border-secondary-300 ring-2 ring-primary/20'
                                    : 'border-white hover:border-secondary-600'
                            "
                        >
                            <img
                                v-if="$page.props.auth?.user?.avatar"
                                :src="$page.props.auth.user.avatar"
                                class="w-full h-full object-cover"
                            />
                            <span v-else>{{
                                $page.props.auth?.user?.name
                                    ?.charAt(0)
                                    .toUpperCase() || "U"
                            }}</span>
                        </button>

                        <transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="transform opacity-0 scale-95 translate-y-2"
                            enter-to-class="transform opacity-100 scale-100 translate-y-0"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="transform opacity-100 scale-100 translate-y-0"
                            leave-to-class="transform opacity-0 scale-95 translate-y-2"
                        >
                            <div
                                v-if="isDropdownOpen"
                                class="absolute top-full right-0 mt-4 w-64 bg-white rounded-3xl shadow-[0_15px_40px_rgba(17,24,64,0.12)] border border-secondary-600 overflow-hidden z-50"
                            >
                                <div
                                    class="px-6 py-4 bg-tertiary-800/50 border-b border-secondary-600"
                                >
                                    <p
                                        class="text-sm font-extrabold text-primary truncate"
                                    >
                                        {{
                                            $page.props.auth?.user?.name ||
                                            "User"
                                        }}
                                    </p>
                                    <p
                                        class="text-xs font-medium text-secondary-200 truncate mt-0.5"
                                    >
                                        @{{
                                            $page.props.auth?.user?.username ||
                                            "user"
                                        }}
                                    </p>
                                </div>
                                <div class="py-2">
                                    <Link
                                        href="/user/setting/profile"
                                        @click="isDropdownOpen = false"
                                        class="flex items-center gap-3 px-6 py-3 text-sm font-bold text-secondary-200 hover:text-primary hover:bg-tertiary-800 transition-colors"
                                    >
                                        <Settings class="w-4 h-4" /> Pengaturan Akun
                                    </Link>
                                </div>
                                <div class="border-t border-secondary-600 py-2">
                                    <form @submit.prevent="confirmLogout">
                                        <button
                                            type="submit"
                                            @click="isDropdownOpen = false"
                                            class="w-full flex items-center gap-3 px-6 py-3 text-sm font-bold text-danger/80 hover:text-danger hover:bg-danger/5 transition-colors text-left"
                                        >
                                            <LogOut class="w-4 h-4" /> Keluar
                                            Sesi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </header>

        <header
            class="md:hidden bg-tertiary-800/90 backdrop-blur-md top-0 sticky z-40 px-6 py-4 flex justify-between items-center border-b border-secondary-600"
        >
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center shadow-md"
                >
                    <div class="w-2.5 h-2.5 bg-white rounded-sm"></div>
                </div>
                <span class="text-lg font-extrabold tracking-tight text-primary"
                    >SmartBox</span
                >
            </div>
            <div class="relative" ref="mobileDropdownRef">
                <button
                    @click="toggleDropdown"
                    class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold text-xs shadow-md border-2 focus:outline-none transition-all"
                    :class="
                        isDropdownOpen
                            ? 'border-secondary-300 ring-2 ring-primary/20'
                            : 'border-white hover:border-secondary-600'
                    "
                >
                    <img
                        v-if="$page.props.auth?.user?.avatar"
                        :src="$page.props.auth.user.avatar"
                        class="w-full h-full object-cover rounded-full"
                    />
                    <span v-else>{{
                        $page.props.auth?.user?.name?.charAt(0).toUpperCase() ||
                        "U"
                    }}</span>
                </button>
                <transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="transform opacity-0 scale-95 translate-y-2"
                    enter-to-class="transform opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="transform opacity-100 scale-100 translate-y-0"
                    leave-to-class="transform opacity-0 scale-95 translate-y-2"
                >
                    <div
                        v-if="isDropdownOpen"
                        class="absolute top-full right-0 mt-4 w-56 bg-white rounded-3xl shadow-[0_15px_40px_rgba(17,24,64,0.12)] border border-secondary-600 overflow-hidden z-50"
                    >
                        <div
                            class="px-5 py-4 bg-tertiary-800/50 border-b border-secondary-600"
                        >
                            <p
                                class="text-sm font-extrabold text-primary truncate"
                            >
                                {{ $page.props.auth?.user?.name || "User" }}
                            </p>
                        </div>
                        <div class="py-2">
                            <form @submit.prevent="confirmLogout">
                                <button
                                    type="submit"
                                    @click="isDropdownOpen = false"
                                    class="w-full flex items-center gap-3 px-5 py-3 text-sm font-bold text-danger/80 hover:text-danger hover:bg-danger/5 transition-colors text-left"
                                >
                                    <LogOut class="w-4 h-4" /> Keluar Sesi
                                </button>
                            </form>
                        </div>
                    </div>
                </transition>
            </div>
        </header>

        <main
            class="max-w-[1920px] mx-auto px-4 sm:px-8 py-6 md:py-10 flex-1 w-full pb-28 md:pb-10"
        >
            <slot />
        </main>

        <div
            class="md:hidden fixed bottom-0 left-0 w-full bg-white rounded-t-[2rem] shadow-[0_-10px_40px_rgba(17,24,64,0.08)] z-50 px-4"
        >
            <div class="flex justify-between items-center relative h-20">
                <Link
                    href="/user/dashboard"
                    class="relative w-16 h-full flex justify-center group"
                >
                    <div
                        v-if="$page.url === '/user/dashboard'"
                        class="flex flex-col items-center w-full h-full"
                    >
                        <div
                            class="absolute -top-5 w-13 h-13 bg-primary text-white rounded-full flex items-center justify-center shadow-lg  transition-all duration-300"
                        >
                            <LayoutGrid class="w-5 h-5" />
                        </div>
                        <span
                            class="absolute bottom-2 text-sm font-extrabold tracking-widest uppercase text-primary"
                            >Home</span
                        >
                    </div>
                    <div
                        v-else
                        class="flex flex-col items-center justify-end pb-2 h-full text-secondary-300 group-hover:text-primary transition-colors"
                    >
                        <LayoutGrid class="w-5 h-5 mb-1" />
                        <span
                            class="text-[9px] font-extrabold tracking-widest uppercase"
                            >Home</span
                        >
                    </div>
                </Link>

                <Link
                    href="/user/packages"
                    class="relative w-16 h-full flex justify-center group"
                >
                    <div
                        v-if="$page.url.startsWith('/user/packages')"
                        class="flex flex-col items-center w-full h-full"
                    >
                        <div
                            class="absolute -top-5 w-13 h-13 bg-primary text-white rounded-full flex items-center justify-center shadow-lg  transition-all duration-300"
                        >
                            <Package class="w-5 h-5" />
                        </div>
                        <span
                            class="absolute bottom-2 text-sm font-extrabold tracking-widest uppercase text-primary"
                            >Parcels</span
                        >
                    </div>
                    <div
                        v-else
                        class="flex flex-col items-center justify-end pb-2 h-full text-secondary-300 group-hover:text-primary transition-colors"
                    >
                        <Package class="w-5 h-5 mb-1" />
                        <span
                            class="text-[9px] font-extrabold tracking-widest uppercase"
                            >Parcels</span
                        >
                    </div>
                </Link>

                <Link
                    href="/user/history/log"
                    class="relative w-16 h-full flex justify-center group"
                >
                    <div
                        v-if="$page.url.startsWith('/user/history/log')"
                        class="flex flex-col items-center w-full h-full"
                    >
                        <div
                            class="absolute -top-5 w-13 h-13 bg-primary text-white rounded-full flex items-center justify-center shadow-lg  transition-all duration-300"
                        >
                            <History class="w-5 h-5" />
                        </div>
                        <span
                            class="absolute bottom-2 text-sm font-extrabold tracking-widest uppercase text-primary"
                            >History</span
                        >
                    </div>
                    <div
                        v-else
                        class="flex flex-col items-center justify-end pb-2 h-full text-secondary-300 group-hover:text-primary transition-colors"
                    >
                        <History class="w-5 h-5 mb-1" />
                        <span
                            class="text-[9px] font-extrabold tracking-widest uppercase"
                            >History</span
                        >
                    </div>
                </Link>

                <Link
                    href="/user/setting/pin"
                    class="relative w-16 h-full flex justify-center group"
                >
                    <div
                        v-if="$page.url.startsWith('/user/setting/pin')"
                        class="flex flex-col items-center w-full h-full"
                    >
                        <div
                            class="absolute -top-5 w-13 h-13 bg-primary text-white rounded-full flex items-center justify-center shadow-lg  transition-all duration-300"
                        >
                            <Settings class="w-5 h-5" />
                        </div>
                        <span
                            class="absolute bottom-2 text-[9px] font-extrabold tracking-widest uppercase text-primary"
                            >Settings</span
                        >
                    </div>
                    <div
                        v-else
                        class="flex flex-col items-center justify-end pb-2 h-full text-secondary-300 group-hover:text-primary transition-colors"
                    >
                        <Settings class="w-5 h-5 mb-1" />
                        <span
                            class="text-[9px] font-extrabold tracking-widest uppercase"
                            >Settings</span
                        >
                    </div>
                </Link>
            </div>
        </div>
        <div
            v-if="isLogoutModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center px-4"
        >
            <div
                @click="closeLogoutModal"
                class="absolute inset-0 bg-primary/40 backdrop-blur-sm transition-opacity"
            ></div>

            <div
                class="relative bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl transform transition-all"
            >
                <div class="flex items-start gap-5 mb-6">
                    <div
                        class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center shrink-0 border border-red-100"
                    >
                        <LogOut class="w-6 h-6 text-red-500" />
                    </div>
                    <div>
                        <h3
                            class="text-xl font-extrabold text-primary tracking-tight"
                        >
                            Keluar Sesi?
                        </h3>
                        <p class="text-secondary-200 font-medium text-sm mt-1">
                            Anda akan keluar dari sistem.
                        </p>
                    </div>
                </div>
                <div class="mb-8">
                    <p class="text-secondary-200 text-sm leading-relaxed">
                        Apakah Anda yakin ingin keluar dari
                        <strong class="text-primary">SmartBox Admin</strong>?
                        Anda harus login kembali menggunakan kredensial Anda
                        untuk mengakses halaman ini.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        @click="closeLogoutModal"
                        :disabled="isLoggingOut"
                        class="flex-1 bg-tertiary-800 hover:bg-secondary-600 text-secondary-200 hover:text-primary font-bold py-3.5 px-4 rounded-full transition-colors text-sm disabled:opacity-50"
                    >
                        Batal
                    </button>
                    <button
                        @click="executeLogout"
                        :disabled="isLoggingOut"
                        class="flex-1 bg-danger hover:bg-danger/90 text-white font-bold py-3.5 px-4 rounded-full transition-colors text-sm disabled:opacity-50 flex items-center justify-center gap-2"
                    >
                        <Loader2
                            v-if="isLoggingOut"
                            class="w-4 h-4 animate-spin text-white"
                        />
                        <span v-else>Ya, Keluar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import {
    LayoutGrid,
    Package,
    History,
    Settings,
    Bell,
    LogOut,
    Loader2,
    Check,
} from "@lucide/vue";

// Logika Dropdown dan Logout sama persis dengan AdminLayout
const isDropdownOpen = ref(false);
const dropdownRef = ref(null);
const mobileDropdownRef = ref(null);

const toggleDropdown = () => (isDropdownOpen.value = !isDropdownOpen.value);
const closeDropdown = (e) => {
    if (
        (!dropdownRef.value || !dropdownRef.value.contains(e.target)) &&
        (!mobileDropdownRef.value ||
            !mobileDropdownRef.value.contains(e.target))
    ) {
        isDropdownOpen.value = false;
    }
};
onMounted(() => document.addEventListener("click", closeDropdown));
onUnmounted(() => document.removeEventListener("click", closeDropdown));

const isLogoutModalOpen = ref(false);
const isLoggingOut = ref(false);
const confirmLogout = () => (isLogoutModalOpen.value = true);
const closeLogoutModal = () => {
    if (!isLoggingOut.value) isLogoutModalOpen.value = false;
};
const executeLogout = () => {
    isLoggingOut.value = true;
    router.post(
        "/logout",
        {},
        {
            onFinish: () => {
                isLoggingOut.value = false;
            },
        },
    );
};
</script>
