<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <!-- Latar Belakang Gradien Utama (Dipertegas agar efek kaca lebih menonjol) -->
        <div class="min-h-screen bg-gradient-to-br from-indigo-300 via-purple-300 to-pink-300 relative overflow-hidden">
            
            <!-- Elemen Bercak Warna (Blob) Animasi di Latar Belakang -->
            <div class="absolute top-0 -left-10 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob pointer-events-none z-0"></div>
            <div class="absolute top-0 -right-10 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000 pointer-events-none z-0"></div>
            <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-4000 pointer-events-none z-0"></div>

            <!-- Animasi Gelembung Kaca (Glass Bubbles) Melayang -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
                <div class="bubble w-32 h-32 left-[10%] animation-delay-0"></div>
                <div class="bubble w-48 h-48 left-[30%] animation-delay-2000"></div>
                <div class="bubble w-24 h-24 left-[60%] animation-delay-4000"></div>
                <div class="bubble w-40 h-40 left-[80%] animation-delay-1500"></div>
                <div class="bubble w-56 h-56 left-[45%] animation-delay-3000"></div>
            </div>

            <!-- Navbar Glassmorphism -->
            <nav class="bg-white/30 backdrop-blur-xl border-b border-white/40 relative z-20 shadow-[0_4px_30px_rgba(0,0,0,0.1)] transition-all duration-300 hover:bg-white/40">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-20 justify-between items-center">
                        <div class="flex items-center">
                            <div class="flex shrink-0 items-center mr-8 transition-transform duration-300 hover:scale-105">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo class="block h-10 w-auto fill-current text-indigo-700 drop-shadow-md"/>
                                </Link>
                            </div>

                            <!-- Menu Navigasi Desktop -->
                            <div class="hidden space-x-8 sm:-my-px sm:flex text-lg font-medium">
                                <NavLink :active="route().current('dashboard')" :href="route('dashboard')">Dashboard</NavLink>
                                <NavLink :active="route().current('categories.*')" :href="route('categories.index')">Kategori</NavLink>
                                <NavLink :active="route().current('products.*')" :href="route('products.index')">Produk</NavLink>
                                <NavLink :active="route().current('transactions.*')" :href="route('transactions.index')">Kasir</NavLink>
                                <NavLink :active="route().current('transactions.history')" :href="route('transactions.history')">Riwayat</NavLink>
                                <NavLink :active="route().current('users.*')" :href="route('users.index')">Pengguna</NavLink>
                            </div>
                        </div>

                        <!-- Dropdown Profil -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-xl bg-white/40 px-4 py-2.5 text-base font-bold text-gray-800 backdrop-blur-md border border-white/50 shadow-sm transition duration-200 hover:bg-white/60 hover:-translate-y-0.5 hover:shadow-md focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}
                                                <svg class="-me-0.5 ms-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                    <template #content>
                                        <div class="bg-white/80 backdrop-blur-xl border border-white/50 shadow-xl rounded-xl">
                                            <DropdownLink :href="route('profile.edit')">Profil</DropdownLink>
                                            <DropdownLink :href="route('logout')" as="button" method="post">Log Out</DropdownLink>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Menu Hamburger Mobile -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-xl p-2 text-gray-700 bg-white/40 border border-white/50 backdrop-blur-md transition hover:bg-white/60 focus:outline-none"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Navigasi Mobile -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden bg-white/40 backdrop-blur-xl border-t border-white/40 absolute w-full z-30 shadow-lg">
                    <div class="space-y-1 pb-3 pt-2 px-2">
                        <ResponsiveNavLink :active="route().current('dashboard')" :href="route('dashboard')">Dashboard</ResponsiveNavLink>
                        <ResponsiveNavLink :active="route().current('categories.*')" :href="route('categories.index')">Kategori</ResponsiveNavLink>
                        <ResponsiveNavLink :active="route().current('products.*')" :href="route('products.index')">Produk</ResponsiveNavLink>
                        <ResponsiveNavLink :active="route().current('transactions.*')" :href="route('transactions.index')">Kasir</ResponsiveNavLink>
                        <!-- Tambahan Menu yang terlewat -->
                        <ResponsiveNavLink :active="route().current('transactions.history')" :href="route('transactions.history')">Riwayat</ResponsiveNavLink>
                        <ResponsiveNavLink :active="route().current('users.*')" :href="route('users.index')">Pengguna</ResponsiveNavLink>
                    </div>
                    <div class="border-t border-white/50 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-bold text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-gray-600">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <div class="mt-3 space-y-1 px-2">
                            <ResponsiveNavLink :href="route('profile.edit')">Profil</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" as="button" method="post">Log Out</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Judul Halaman Transparan -->
            <header class="bg-white/20 backdrop-blur-lg shadow-sm border-b border-white/30 relative z-10" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Konten Halaman dengan Transisi -->
            <main class="relative z-10">
                <Transition appear mode="out-in" name="page">
                    <div :key="$page.url">
                        <slot />
                    </div>
                </Transition>
            </main>

        </div>
    </div>
</template>

<!-- TAMBAHKAN CSS INI DI BAGIAN PALING BAWAH FILE -->
<style>
/* Animasi Gelembung Kaca (Glass Bubbles) */
.bubble {
    position: absolute;
    bottom: -20vh;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0.05) 100%);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
    animation: floatUp 15s infinite ease-in-out;
}

@keyframes floatUp {
    0% { transform: translateY(0) scale(0.8) translateX(0) rotate(0deg); opacity: 0; }
    20% { opacity: 1; }
    50% { transform: translateY(-50vh) scale(1.1) translateX(30px) rotate(180deg); }
    100% { transform: translateY(-120vh) scale(0.8) translateX(-30px) rotate(360deg); opacity: 0; }
}

/* Pengatur Waktu Mulai Animasi */
.animation-delay-0 { animation-delay: 0s; }
.animation-delay-1500 { animation-delay: 1.5s; }
.animation-delay-2000 { animation-delay: 2s; }
.animation-delay-3000 { animation-delay: 3s; }
.animation-delay-4000 { animation-delay: 4s; }

/* Animasi Bercak Warna (Blob) yang Membesar/Mengecil */
.animate-blob {
    animation: blob 7s infinite alternate;
}

@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}

/* Transisi Halus Saat Pindah Halaman */
.page-enter-active, .page-leave-active {
    transition: opacity 0.4s ease, transform 0.4s ease;
}
.page-enter-from, .page-leave-to {
    opacity: 0;
    transform: translateY(10px);
}
</style>