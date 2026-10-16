<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    categories: Array
});

// Fitur Tambah Kategori
const addCategory = () => {
    Swal.fire({
        title: 'Tambah Kategori',
        input: 'text',
        inputPlaceholder: 'Contoh: Minuman, Makanan Ringan...',
        showCancelButton: true,
        confirmButtonColor: '#8b5cf6',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        background: 'rgba(255, 255, 255, 0.9)',
        backdrop: 'rgba(0,0,0,0.4)',
        customClass: { popup: 'backdrop-blur-md rounded-2xl border border-white/50 shadow-2xl' },
        preConfirm: (value) => {
            if (!value) Swal.showValidationMessage('Nama kategori wajib diisi!');
            return value;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route('categories.store'), { nama_kategori: result.value }, {
                onSuccess: () => {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Kategori ditambahkan.', timer: 1500, showConfirmButton: false });
                }
            });
        }
    });
};

// Fitur Edit Kategori
const editCategory = (category) => {
    Swal.fire({
        title: 'Edit Kategori',
        input: 'text',
        inputValue: category.nama_kategori,
        showCancelButton: true,
        confirmButtonColor: '#8b5cf6',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Perbarui',
        cancelButtonText: 'Batal',
        background: 'rgba(255, 255, 255, 0.9)',
        customClass: { popup: 'backdrop-blur-md rounded-2xl border border-white/50 shadow-2xl' },
        preConfirm: (value) => {
            if (!value) Swal.showValidationMessage('Nama kategori wajib diisi!');
            return value;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(route('categories.update', category.id), { nama_kategori: result.value }, {
                onSuccess: () => {
                    Swal.fire({ icon: 'success', title: 'Diperbarui!', text: 'Kategori berhasil diubah.', timer: 1500, showConfirmButton: false });
                }
            });
        }
    });
};

// Fitur Hapus Kategori
const deleteCategory = (id) => {
    Swal.fire({
        title: 'Hapus Kategori ini?',
        text: "Semua produk dalam kategori ini mungkin akan terdampak!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        background: 'rgba(255, 255, 255, 0.9)',
        customClass: { popup: 'backdrop-blur-md rounded-2xl shadow-2xl' }
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('categories.destroy', id), {
                onSuccess: () => {
                    Swal.fire({ icon: 'success', title: 'Terhapus!', timer: 1500, showConfirmButton: false });
                }
            });
        }
    });
};
</script>

<template>
    <Head title="Kategori Produk" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-wide drop-shadow-sm">
                Manajemen Kategori
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Panel Utama Glassmorphism -->
                <div class="bg-white/40 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 md:p-8 relative z-10">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-xl font-bold text-gray-800">Daftar Kategori</h3>
                        <button @click="addCategory" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transform transition duration-200 hover:-translate-y-1">
                            + Tambah Kategori
                        </button>
                    </div>

                    <!-- Tabel Data -->
                    <div class="overflow-x-auto rounded-xl border border-white/50 shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/50 border-b border-white/50">
                                    <th class="p-4 font-bold text-gray-700 w-16 text-center">No</th>
                                    <th class="p-4 font-bold text-gray-700">Nama Kategori</th>
                                    <th class="p-4 font-bold text-gray-700 text-center w-48">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(category, index) in categories" :key="category.id" class="border-b border-white/30 hover:bg-white/50 transition duration-150">
                                    <td class="p-4 text-gray-800 text-center font-medium">{{ index + 1 }}</td>
                                    <td class="p-4 text-gray-800 font-semibold">{{ category.nama_kategori }}</td>
                                    <td class="p-4 flex justify-center gap-3">
                                        <button @click="editCategory(category)" class="bg-indigo-100 text-indigo-600 hover:bg-indigo-200 hover:text-indigo-700 font-semibold py-1.5 px-4 rounded-lg transition duration-200 shadow-sm">Edit</button>
                                        <button @click="deleteCategory(category.id)" class="bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 font-semibold py-1.5 px-4 rounded-lg transition duration-200 shadow-sm">Hapus</button>
                                    </td>
                                </tr>
                                <tr v-if="categories.length === 0">
                                    <td colspan="3" class="p-8 text-center text-gray-500 font-medium">
                                        Belum ada data kategori. Klik tombol di atas untuk menambah.
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