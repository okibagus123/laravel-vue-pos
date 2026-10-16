<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    products: Array,
    categories: Array
});

const formatRupiah = (angka) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
};

// Form HTML dinamis menyesuaikan kolom Model
const getFormHtml = (product = null) => {
    const categoriesOptions = props.categories.map(c => 
        `<option value="${c.id}" ${product && product.category_id === c.id ? 'selected' : ''}>${c.nama_kategori}</option>`
    ).join('');

    return `
        <div class="flex flex-col gap-4 text-left p-2">
            <div>
                <label class="text-sm font-semibold text-gray-700">Kategori</label>
                <select id="swal-kategori" class="swal2-input !w-full !m-0 mt-1 bg-white/60 border-gray-300 rounded-lg">
                    <option value="">Pilih Kategori</option>
                    ${categoriesOptions}
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Nama Produk</label>
                <input id="swal-nama" class="swal2-input !w-full !m-0 mt-1 bg-white/60 border-gray-300 rounded-lg" placeholder="Contoh: Kopi Susu" value="${product ? product.nama_produk : ''}">
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Kode Barcode (Opsional)</label>
                <input id="swal-barcode" class="swal2-input !w-full !m-0 mt-1 bg-white/60 border-gray-300 rounded-lg" placeholder="Scan/ketik kode" value="${product && product.kode_barcode ? product.kode_barcode : ''}">
            </div>
            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="text-sm font-semibold text-gray-700">Harga Beli</label>
                    <input type="number" id="swal-beli" class="swal2-input !w-full !m-0 mt-1 bg-white/60 border-gray-300 rounded-lg" value="${product ? product.harga_beli : ''}">
                </div>
                <div class="w-1/2">
                    <label class="text-sm font-semibold text-gray-700">Harga Jual</label>
                    <input type="number" id="swal-jual" class="swal2-input !w-full !m-0 mt-1 bg-white/60 border-gray-300 rounded-lg" value="${product ? product.harga_jual : ''}">
                </div>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Stok Awal</label>
                <input type="number" id="swal-stok" class="swal2-input !w-full !m-0 mt-1 bg-white/60 border-gray-300 rounded-lg" value="${product ? product.stok : ''}">
            </div>
        </div>
    `;
};

const getFormData = () => {
    const category_id = document.getElementById('swal-kategori').value;
    const nama_produk = document.getElementById('swal-nama').value;
    const harga_beli = document.getElementById('swal-beli').value;
    const harga_jual = document.getElementById('swal-jual').value;
    const stok = document.getElementById('swal-stok').value;

    if (!category_id || !nama_produk || !harga_beli || !harga_jual || !stok) {
        Swal.showValidationMessage('Semua kolom (kecuali barcode) wajib diisi!');
        return false;
    }

    return {
        category_id,
        kode_barcode: document.getElementById('swal-barcode').value || null,
        nama_produk,
        harga_beli,
        harga_jual,
        stok
    };
};

const addProduct = () => {
    if (props.categories.length === 0) {
        Swal.fire('Perhatian', 'Silakan tambah Kategori terlebih dahulu!', 'warning');
        return;
    }

    Swal.fire({
        title: 'Tambah Produk',
        html: getFormHtml(),
        showCancelButton: true,
        confirmButtonColor: '#8b5cf6',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        background: 'rgba(255, 255, 255, 0.9)',
        backdrop: 'rgba(0,0,0,0.4)',
        customClass: { popup: 'backdrop-blur-md rounded-2xl border border-white/50 shadow-2xl w-full max-w-lg' },
        preConfirm: getFormData
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route('products.store'), result.value, {
                onSuccess: () => Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Produk ditambahkan.', timer: 1500, showConfirmButton: false })
            });
        }
    });
};

const editProduct = (product) => {
    Swal.fire({
        title: 'Edit Produk',
        html: getFormHtml(product),
        showCancelButton: true,
        confirmButtonColor: '#8b5cf6',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Perbarui',
        cancelButtonText: 'Batal',
        background: 'rgba(255, 255, 255, 0.9)',
        customClass: { popup: 'backdrop-blur-md rounded-2xl border border-white/50 shadow-2xl w-full max-w-lg' },
        preConfirm: getFormData
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(route('products.update', product.id), result.value, {
                onSuccess: () => Swal.fire({ icon: 'success', title: 'Diperbarui!', text: 'Produk berhasil diubah.', timer: 1500, showConfirmButton: false })
            });
        }
    });
};

const deleteProduct = (id) => {
    Swal.fire({
        title: 'Hapus Produk?',
        text: "Data ini tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Ya, Hapus!',
        background: 'rgba(255, 255, 255, 0.9)',
        customClass: { popup: 'backdrop-blur-md rounded-2xl shadow-2xl' }
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('products.destroy', id), {
                onSuccess: () => Swal.fire({ icon: 'success', title: 'Terhapus!', timer: 1500, showConfirmButton: false })
            });
        }
    });
};
</script>

<template>
    <Head title="Produk" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-wide drop-shadow-sm">
                Manajemen Produk
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/40 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 md:p-8 relative z-10">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-bold text-gray-800">Daftar Produk</h3>
                        <button @click="addProduct" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transform transition duration-200 hover:-translate-y-1">
                            + Tambah Produk
                        </button>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-white/50 shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/50 border-b border-white/50">
                                    <th class="p-4 font-bold text-gray-700 w-12 text-center">No</th>
                                    <th class="p-4 font-bold text-gray-700">Nama Produk</th>
                                    <th class="p-4 font-bold text-gray-700">Kategori</th>
                                    <th class="p-4 font-bold text-gray-700 text-right">Harga Jual</th>
                                    <th class="p-4 font-bold text-gray-700 text-center">Stok</th>
                                    <th class="p-4 font-bold text-gray-700 text-center w-40">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in products" :key="product.id" class="border-b border-white/30 hover:bg-white/50 transition duration-150">
                                    <td class="p-4 text-gray-800 text-center font-medium">{{ index + 1 }}</td>
                                    <td class="p-4 text-gray-800">
                                        <div class="font-semibold">{{ product.nama_produk }}</div>
                                        <div class="text-xs text-gray-500">{{ product.kode_barcode || 'Tanpa Barcode' }}</div>
                                    </td>
                                    <td class="p-4 text-gray-800 font-medium">{{ product.category?.nama_kategori }}</td>
                                    <td class="p-4 text-gray-800 font-semibold text-right">{{ formatRupiah(product.harga_jual) }}</td>
                                    <td class="p-4 text-center">
                                        <span :class="product.stok < 10 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'" class="px-3 py-1 rounded-full text-sm font-bold shadow-sm">
                                            {{ product.stok }}
                                        </span>
                                    </td>
                                    <td class="p-4 flex justify-center gap-2">
                                        <button @click="editProduct(product)" class="bg-indigo-100 text-indigo-600 hover:bg-indigo-200 hover:text-indigo-700 font-semibold py-1.5 px-3 rounded-lg transition duration-200 shadow-sm text-sm">Edit</button>
                                        <button @click="deleteProduct(product.id)" class="bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 font-semibold py-1.5 px-3 rounded-lg transition duration-200 shadow-sm text-sm">Hapus</button>
                                    </td>
                                </tr>
                                <tr v-if="products.length === 0">
                                    <td colspan="6" class="p-8 text-center text-gray-500 font-medium">
                                        Belum ada data produk. Klik tombol di atas untuk menambah.
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