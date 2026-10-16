<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    products: Array,
});

// State untuk keranjang belanja
const cart = ref([]);

// Format mata uang Rupiah
const formatRupiah = (angka) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
};

// Tambah ke keranjang
const addToCart = (product) => {
    const item = cart.value.find(i => i.id === product.id);
    if (item) {
        if (item.qty < product.stok) {
            item.qty++;
        } else {
            Swal.fire({ icon: 'error', title: 'Stok Habis', text: 'Kuantitas melebihi stok yang ada.', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
        }
    } else {
        cart.value.push({ ...product, qty: 1 });
    }
};

// Hapus dari keranjang
const removeFromCart = (index) => {
    cart.value.splice(index, 1);
};

// Hitung total belanja
const totalTagihan = computed(() => {
    return cart.value.reduce((sum, item) => sum + (item.harga_jual * item.qty), 0);
});

// Proses Pembayaran dengan Error Handling
const checkout = () => {
    if (cart.value.length === 0) return;

    Swal.fire({
        title: 'Pembayaran',
        html: `
            <div class="mb-4 text-left">
                <p class="text-sm text-gray-500 font-bold uppercase">Total Tagihan</p>
                <p class="text-3xl font-extrabold text-gray-800">${formatRupiah(totalTagihan.value)}</p>
            </div>
            <input type="number" id="uang_dibayar" class="swal2-input bg-white/60 border-gray-300 rounded-lg focus:ring-purple-500 w-[80%] mx-auto" placeholder="Nominal Uang Pelanggan (Rp)">
        `,
        showCancelButton: true,
        confirmButtonColor: '#8b5cf6',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Bayar',
        cancelButtonText: 'Batal',
        background: 'rgba(255, 255, 255, 0.9)',
        backdrop: 'rgba(0,0,0,0.4)',
        customClass: { popup: 'backdrop-blur-md rounded-2xl border border-white/50 shadow-2xl' },
        preConfirm: () => {
            const uangDibayar = Swal.getPopup().querySelector('#uang_dibayar').value;
            if (!uangDibayar) {
                Swal.showValidationMessage(`Harap masukkan nominal uang!`);
                return false;
            } else if (parseInt(uangDibayar) < totalTagihan.value) {
                Swal.showValidationMessage(`Uang tidak cukup! Minimal ${formatRupiah(totalTagihan.value)}`);
                return false;
            }
            return { uangDibayar: parseInt(uangDibayar) }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const uangDibayar = result.value.uangDibayar;
            const kembalian = uangDibayar - totalTagihan.value;
            
            // Mengirim data ke server
            router.post(route('transactions.store'), {
                cart: cart.value,
                total_tagihan: totalTagihan.value,
                uang_dibayar: uangDibayar,
                kembalian: kembalian
            }, {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    Swal.fire({
                        title: 'Transaksi Berhasil!',
                        html: `
                            <p class="text-gray-600 mb-2">Uang Kembalian:</p>
                            <p class="text-4xl font-extrabold text-green-500">${formatRupiah(kembalian)}</p>
                        `,
                        icon: 'success',
                        confirmButtonColor: '#8b5cf6',
                        background: 'rgba(255, 255, 255, 0.9)',
                        customClass: { popup: 'backdrop-blur-md rounded-2xl shadow-2xl' }
                    });
                    cart.value = []; // Kosongkan keranjang
                },
                onError: (errors) => {
                    // Menangkap dan memunculkan error dari Laravel
                    let errorMessages = '';
                    for (let key in errors) {
                        errorMessages += errors[key] + '<br>';
                    }
                    Swal.fire({
                        title: 'Gagal Memproses!',
                        html: errorMessages || 'Terjadi kesalahan pada struktur data atau server.',
                        icon: 'error'
                    });
                }
            });
        }
    });
};
</script>

<template>
    <Head title="Kasir POS" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-wide drop-shadow-sm">
                Mesin Kasir
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-6">
                    
                    <!-- KIRI: Daftar Produk -->
                    <div class="w-full lg:w-2/3 space-y-4">
                        <div class="bg-white/40 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 relative z-10">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Pilih Produk</h3>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <div 
                                    v-for="product in products" 
                                    :key="product.id" 
                                    @click="addToCart(product)"
                                    class="bg-white/60 border border-white/50 rounded-2xl p-4 cursor-pointer transform transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:bg-white/80 flex flex-col justify-between h-32"
                                >
                                    <h4 class="font-bold text-gray-700 leading-tight">{{ product.nama_produk }}</h4>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Stok: {{ product.stok }}</p>
                                        <p class="font-extrabold text-purple-600">{{ formatRupiah(product.harga_jual) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-if="products.length === 0" class="text-center py-10 text-gray-500 font-medium">
                                Belum ada produk yang tersedia atau stok habis.
                            </div>
                        </div>
                    </div>

                    <!-- KANAN: Keranjang Belanja -->
                    <div class="w-full lg:w-1/3">
                        <div class="bg-white/40 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 relative z-10 sticky top-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-300/50 pb-2">Keranjang Belanja</h3>
                            
                            <div class="space-y-4 max-h-64 overflow-y-auto pr-2 mb-4">
                                <div v-for="(item, index) in cart" :key="index" class="flex justify-between items-center bg-white/50 rounded-xl p-3 shadow-sm border border-white/30">
                                    <div class="flex-1">
                                        <h5 class="font-bold text-gray-800 text-sm">{{ item.nama_produk }}</h5>
                                        <p class="text-xs text-gray-500">{{ formatRupiah(item.harga_jual) }} x {{ item.qty }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <p class="font-bold text-gray-800 text-sm">{{ formatRupiah(item.harga_jual * item.qty) }}</p>
                                        <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700 font-bold text-lg bg-red-100 rounded-full w-6 h-6 flex items-center justify-center transition">&times;</button>
                                    </div>
                                </div>

                                <div v-if="cart.length === 0" class="text-center py-8 text-gray-400 text-sm">
                                    Keranjang masih kosong.
                                </div>
                            </div>

                            <div class="border-t border-gray-300/50 pt-4">
                                <div class="flex justify-between items-center mb-6">
                                    <span class="font-bold text-gray-600">Total Tagihan</span>
                                    <span class="font-extrabold text-2xl text-gray-900">{{ formatRupiah(totalTagihan) }}</span>
                                </div>
                                
                                <button 
                                    @click="checkout" 
                                    :disabled="cart.length === 0"
                                    class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-4 rounded-2xl shadow-lg transform transition duration-200 hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                >
                                    Bayar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>