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
                        <div ><Package class="text-white rounded-sm p-1" /></div>
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
                        href="/admin/dashboard"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold transition-all"
                        :class="
                            $page.url === '/admin/dashboard'
                                ? 'bg-primary text-white shadow-md'
                                : 'text-secondary-200 hover:bg-tertiary-800 hover:text-primary'
                        "
                    >
                        <LayoutGrid class="w-4 h-4" /> Dashboard
                    </Link>

                    <Link
                        href="/admin/users"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold transition-all"
                        :class="
                            $page.url.startsWith('/admin/users')
                                ? 'bg-primary text-white shadow-md'
                                : 'text-secondary-200 hover:bg-tertiary-800 hover:text-primary'
                        "
                    >
                        <Users class="w-4 h-4" /> Pengguna
                    </Link>
                    <Link
                        href="/admin/activity-log"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold transition-all"
                        :class="
                            $page.url.startsWith('/admin/activity-log')
                                ? 'bg-primary text-white shadow-md'
                                : 'text-secondary-200 hover:bg-tertiary-800 hover:text-primary'
                        "
                    >
                        <Users class="w-4 h-4" /> Aktivitas
                    </Link>
                </nav>

                <div class="flex items-center gap-4">
                    <div
                        class="flex items-center gap-3 pl-4 border-l border-secondary-600 relative"
                        ref="dropdownRef"
                    >
                        <div class="hidden lg:flex flex-col text-right mr-1">
                            <span
                                class="text-sm font-bold text-primary leading-tight"
                                >{{
                                    $page.props.auth?.user?.name ||
                                    "Administrator"
                                }}</span
                            >
                            <span
                                class="text-[9px] font-extrabold text-secondary-200 tracking-widest uppercase mt-0.5"
                            >
                                {{
                                    $page.props.auth?.user?.role === "admin"
                                        ? "Root Access"
                                        : "System User"
                                }}
                            </span>
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
                                    .toUpperCase() || "A"
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
                                            "Administrator"
                                        }}
                                    </p>
                                    <p
                                        class="text-xs font-medium text-secondary-200 truncate mt-0.5"
                                    >
                                        @{{
                                            $page.props.auth?.user?.username ||
                                            "admin_root"
                                        }}
                                    </p>
                                </div>

                                <div class="py-2">
                                    <Link
                                        href="/admin/profile"
                                        class="flex items-center gap-3 px-6 py-3 text-sm font-bold text-secondary-200 hover:text-primary hover:bg-tertiary-800 transition-colors"
                                    >
                                        <Settings class="w-4 h-4" /> Edit Profil
                                    </Link>
                                </div>

                                <div class="border-t border-secondary-600 py-2">
                                    <form @submit.prevent="confirmLogout">
                                        <button
                                            type="submit"
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
                    <div ><Package class="text-white rounded-sm p-1" /></div>
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
                        "A"
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
                                {{
                                    $page.props.auth?.user?.name ||
                                    "Administrator"
                                }}
                            </p>
                            <p
                                class="text-[10px] font-medium text-secondary-200 truncate mt-0.5"
                            >
                                @{{
                                    $page.props.auth?.user?.username ||
                                    "admin_root"
                                }}
                            </p>
                        </div>

                        <div class="py-2">
                            <Link
                                href="/admin/profile"
                                @click="isDropdownOpen = false"
                                class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-secondary-200 hover:text-primary hover:bg-tertiary-800 transition-colors"
                            >
                                <Settings class="w-4 h-4" /> Edit Profil
                            </Link>
                        </div>

                        <div class="border-t border-secondary-600 py-2">
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

        <footer class="hidden md:flex bg-transparent w-full mt-auto">
            <div
                class="flex justify-between items-center w-full px-12 py-6 max-w-[1920px] mx-auto border-t border-secondary-600"
            >
                <p class="text-xs font-bold text-secondary-200">
                    © 2024 Sovereign Core IoT. All rights reserved.
                </p>
                <div class="flex items-center gap-2">
                    <span
                        class="w-2 h-2 rounded-full bg-green-500 animate-pulse shadow-[0_0_8px_rgba(25,132,74,0.5)]"
                    ></span>
                    <span class="text-xs font-bold text-secondary-200"
                        >System Status: Online</span
                    >
                </div>
            </div>
        </footer>

        <div
            class="md:hidden fixed bottom-0 left-0 w-full bg-white rounded-t-[2rem] shadow-[0_-10px_40px_rgba(17,24,64,0.08)] z-50 px-6"
        >
            <div class="flex justify-around items-center relative h-20">
                <Link
                    href="/admin/dashboard"
                    class="relative w-16 h-full flex justify-center group"
                >
                    <div
                        v-if="$page.url === '/admin/dashboard'"
                        class="flex flex-col items-center w-full h-full"
                    >
                        <div
                            class="absolute -top-4 w-14 h-14 bg-primary text-white rounded-full flex items-center justify-center shadow-[0_8px_15px_rgba(17,24,64,0.3)] transition-all duration-300"
                        >
                            <LayoutGrid class="w-6 h-6" />
                        </div>

                        <span
                            class="absolute bottom-2 text-sm font-extrabold tracking-widest uppercase text-primary"
                        >
                            Home
                        </span>
                    </div>

                    <div
                        v-else
                        class="flex flex-col items-center justify-end pb-2 h-full text-secondary-300 group-hover:text-primary transition-colors"
                    >
                        <LayoutGrid class="w-5 h-5 mb-1" />
                        <span
                            class="text-[9px] font-extrabold tracking-widest uppercase"
                        >
                            Home
                        </span>
                    </div>
                </Link>

                <Link
                    href="/admin/users"
                    class="relative w-16 h-full flex justify-center group"
                >
                    <div
                        v-if="$page.url.startsWith('/admin/users')"
                        class="flex flex-col items-center w-full h-full"
                    >
                        <div
                            class="absolute -top-4 w-14 h-14 bg-primary text-white rounded-full flex items-center justify-center shadow-[0_8px_15px_rgba(17,24,64,0.3)] transition-all duration-300"
                        >
                            <Users class="w-6 h-6" />
                        </div>

                        <span
                            class="absolute bottom-2 text-sm font-extrabold tracking-widest uppercase text-primary"
                        >
                            Users
                        </span>
                    </div>

                    <div
                        v-else
                        class="flex flex-col items-center justify-end pb-2 h-full text-secondary-300 group-hover:text-primary transition-colors"
                    >
                        <Users class="w-5 h-5 mb-1" />
                        <span
                            class="text-[9px] font-extrabold tracking-widest uppercase"
                        >
                            Users
                        </span>
                    </div>
                </Link>

                <Link
                    href="/admin/users"
                    class="relative w-16 h-full flex justify-center group"
                >
                    <div
                        v-if="$page.url.startsWith('/admin/users')"
                        class="flex flex-col items-center w-full h-full"
                    >
                        <div
                            class="absolute -top-4 w-14 h-14 bg-primary text-white rounded-full flex items-center justify-center shadow-[0_8px_15px_rgba(17,24,64,0.3)] transition-all duration-300"
                        >
                            <Users class="w-6 h-6" />
                        </div>

                        <span
                            class="absolute bottom-2 text-sm font-extrabold tracking-widest uppercase text-primary"
                        >
                            Users
                        </span>
                    </div>

                    <div
                        v-else
                        class="flex flex-col items-center justify-end pb-2 h-full text-secondary-300 group-hover:text-primary transition-colors"
                    >
                        <Users class="w-5 h-5 mb-1" />
                        <span
                            class="text-[9px] font-extrabold tracking-widest uppercase"
                        >
                            Users
                        </span>
                    </div>
                </Link>

                <form
                    @submit.prevent="confirmLogout"
                    class="relative w-16 h-full flex justify-center group cursor-pointer"
                >
                    <button
                        type="submit"
                        class="flex flex-col items-center justify-end pb-2 w-full h-full text-secondary-300 hover:text-danger transition-colors"
                    >
                        <LogOut class="w-5 h-5 mb-1" />
                        <span
                            class="text-[9px] font-extrabold tracking-widest uppercase"
                            >Keluar</span
                        >
                    </button>
                </form>
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
import { ref, onMounted, onUnmounted } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { LayoutGrid, Users, Bell, LogOut, Settings, Loader2, Package } from "@lucide/vue"; // Tambahkan Settings

// --- LOGIKA DROPDOWN PROFIL ---
const isDropdownOpen = ref(false);
const dropdownRef = ref(null); // Untuk desktop
const mobileDropdownRef = ref(null); // Untuk mobile

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const closeDropdown = (e) => {
    // Cek apakah klik berada di dalam area dropdown desktop ATAU mobile
    const isClickInsideDesktop =
        dropdownRef.value && dropdownRef.value.contains(e.target);
    const isClickInsideMobile =
        mobileDropdownRef.value && mobileDropdownRef.value.contains(e.target);

    // Jika klik terjadi DI LUAR keduanya, maka tutup dropdown
    if (!isClickInsideDesktop && !isClickInsideMobile) {
        isDropdownOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", closeDropdown);
});

onUnmounted(() => {
    document.removeEventListener("click", closeDropdown);
});

// --- LOGIKA LOGOUT ---
const logout = () => {
    if (confirm("Yakin ingin keluar dari sesi?")) {
        router.post("/logout");
    }
};

// --- LOGIKA MODAL LOGOUT ---
const isLogoutModalOpen = ref(false);
const isLoggingOut = ref(false);

const confirmLogout = () => {
    isLogoutModalOpen.value = true;
};

const closeLogoutModal = () => {
    if (!isLoggingOut.value) {
        isLogoutModalOpen.value = false;
    }
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
