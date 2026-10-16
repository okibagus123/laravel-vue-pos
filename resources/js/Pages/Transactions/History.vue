<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    transactions: Array
});

const formatRupiah = (angka) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

// Fungsi membuka jendela struk di tab baru
const cetakStruk = (id) => {
    window.open(`/transaksi/${id}/cetak`, '_blank', 'width=400,height=600');
};
</script>

<template>
    <Head title="Riwayat Transaksi" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-wide drop-shadow-sm">
                Riwayat Transaksi
            </h2>
        </template>

       <div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white/40 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 md:p-8 relative z-10">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Daftar Penjualan</h3>
                
                <!-- TOMBOL EXPORT -->
                <a :href="route('transactions.export')" class="bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition duration-200 hover:-translate-y-1 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Export Excel (CSV)
                </a>
            </div>
            
            <!-- Kode tabel di bawahnya biarkan saja seperti semula -->
                    

                    <div class="overflow-x-auto rounded-xl border border-white/50 shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/50 border-b border-white/50">
                                    <th class="p-4 font-bold text-gray-700 w-12 text-center">No</th>
                                    <th class="p-4 font-bold text-gray-700">No. Struk</th>
                                    <th class="p-4 font-bold text-gray-700">Waktu</th>
                                    <th class="p-4 font-bold text-gray-700">Kasir</th>
                                    <th class="p-4 font-bold text-gray-700 text-right">Total Transaksi</th>
                                    <th class="p-4 font-bold text-gray-700 text-center w-32">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(trx, index) in transactions" :key="trx.id" class="border-b border-white/30 hover:bg-white/50 transition duration-150">
                                    <td class="p-4 text-gray-800 text-center font-medium">{{ index + 1 }}</td>
                                    <td class="p-4 text-gray-800 font-bold text-indigo-600">{{ trx.nomor_struk }}</td>
                                    <td class="p-4 text-gray-600 text-sm">{{ formatDate(trx.created_at) }}</td>
                                    <td class="p-4 text-gray-800 font-medium">{{ trx.user?.name || 'Admin' }}</td>
                                    <td class="p-4 text-gray-800 font-extrabold text-right">{{ formatRupiah(trx.total_harga) }}</td>
                                    <td class="p-4 text-center">
                                        <button @click="cetakStruk(trx.id)" class="bg-emerald-100 text-emerald-700 hover:bg-emerald-200 font-bold py-1.5 px-4 rounded-lg transition duration-200 shadow-sm text-sm w-full">
                                            🖨️ Cetak
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="transactions.length === 0">
                                    <td colspan="6" class="p-8 text-center text-gray-500 font-medium">
                                        Belum ada riwayat transaksi.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>