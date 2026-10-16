<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    pendapatanHariIni: Number,
    persentase: Number,
    jumlahTransaksi: Number,
    produkAktif: Number,
    stokMenipis: Number
});

const user = usePage().props.auth.user;

const formatRupiah = (angka) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka || 0);
};

// Menentukan warna dan panah persentase
const persentaseClass = computed(() => props.persentase >= 0 ? 'text-green-500' : 'text-red-500');
const persentaseIcon = computed(() => props.persentase >= 0 ? '↑' : '↓');
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-wide drop-shadow-sm">
                Dashboard POS
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Pesan Selamat Datang -->
                <div class="bg-white/40 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 md:p-8 mb-6 relative z-10">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang kembali, {{ user.name }}! 👋</h3>
                    <p class="text-gray-600 font-medium">Pantau performa penjualan, kelola inventaris, dan proses transaksi dengan cepat melalui sistem Kasir pintar Anda hari ini.</p>
                </div>

                <!-- Kartu Statistik -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Kartu 1: Pendapatan -->
                    <div class="bg-white/40 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 relative z-10 transition-transform duration-300 hover:-translate-y-2">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pendapatan Hari Ini</h4>
                            <div class="bg-green-100 text-green-600 p-2 rounded-xl"><i class="fas fa-money-bill-wave"></i></div>
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-800 mb-2">{{ formatRupiah(pendapatanHariIni) }}</h2>
                        <p class="text-sm font-semibold" :class="persentaseClass">
                            {{ persentaseIcon }} {{ Math.abs(persentase) }}% dari kemarin
                        </p>
                    </div>

                    <!-- Kartu 2: Transaksi -->
                    <div class="bg-white/40 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 relative z-10 transition-transform duration-300 hover:-translate-y-2">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Transaksi Harian</h4>
                            <div class="bg-blue-100 text-blue-600 p-2 rounded-xl"><i class="fas fa-receipt"></i></div>
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-800 mb-2">{{ jumlahTransaksi }} <span class="text-lg text-gray-500 font-medium">struk</span></h2>
                        <p class="text-sm font-semibold text-gray-500">
                            Berhasil dicetak hari ini
                        </p>
                    </div>

                    <!-- Kartu 3: Produk -->
                    <div class="bg-white/40 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 relative z-10 transition-transform duration-300 hover:-translate-y-2">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Produk Aktif</h4>
                            <div class="bg-purple-100 text-purple-600 p-2 rounded-xl"><i class="fas fa-box"></i></div>
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-800 mb-2">{{ produkAktif }} <span class="text-lg text-gray-500 font-medium">item</span></h2>
                        <p class="text-sm font-semibold" :class="stokMenipis > 0 ? 'text-red-500' : 'text-green-500'">
                            {{ stokMenipis > 0 ? `${stokMenipis} produk hampir habis!` : 'Semua stok aman.' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>