<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    users: Array
});

const currentUser = usePage().props.auth.user;

const getFormHtml = (user = null) => {
    return `
        <div class="flex flex-col gap-4 text-left p-2">
            <div>
                <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                <input id="swal-name" type="text" class="swal2-input !w-full !m-0 mt-1 bg-white/60 border-gray-300 rounded-lg" placeholder="Contoh: Budi Kasir" value="${user ? user.name : ''}">
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Email Login</label>
                <input id="swal-email" type="email" class="swal2-input !w-full !m-0 mt-1 bg-white/60 border-gray-300 rounded-lg" placeholder="budi@toko.com" value="${user ? user.email : ''}">
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-700">Password ${user ? '<span class="text-xs text-gray-500 font-normal">(Kosongkan jika tidak ingin diubah)</span>' : ''}</label>
                <input id="swal-password" type="password" class="swal2-input !w-full !m-0 mt-1 bg-white/60 border-gray-300 rounded-lg" placeholder="Minimal 8 karakter">
            </div>
        </div>
    `;
};

const getFormData = (isEdit = false) => {
    const name = document.getElementById('swal-name').value;
    const email = document.getElementById('swal-email').value;
    const password = document.getElementById('swal-password').value;

    if (!name || !email) {
        Swal.showValidationMessage('Nama dan Email wajib diisi!');
        return false;
    }
    if (!isEdit && !password) {
        Swal.showValidationMessage('Password wajib diisi untuk akun baru!');
        return false;
    }
    if (password && password.length < 8) {
        Swal.showValidationMessage('Password minimal 8 karakter!');
        return false;
    }

    return { name, email, password };
};

const addUser = () => {
    Swal.fire({
        title: 'Tambah Pengguna Baru',
        html: getFormHtml(),
        showCancelButton: true,
        confirmButtonColor: '#8b5cf6',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Simpan',
        background: 'rgba(255, 255, 255, 0.9)',
        customClass: { popup: 'backdrop-blur-md rounded-2xl w-full max-w-md shadow-2xl' },
        preConfirm: () => getFormData(false)
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route('users.store'), result.value, {
                onSuccess: () => Swal.fire({ icon: 'success', title: 'Tersimpan!', timer: 1500, showConfirmButton: false }),
                onError: (err) => Swal.fire('Gagal', err.email || 'Terjadi kesalahan sistem.', 'error')
            });
        }
    });
};

const editUser = (user) => {
    Swal.fire({
        title: 'Edit Pengguna',
        html: getFormHtml(user),
        showCancelButton: true,
        confirmButtonColor: '#8b5cf6',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Perbarui',
        background: 'rgba(255, 255, 255, 0.9)',
        customClass: { popup: 'backdrop-blur-md rounded-2xl w-full max-w-md shadow-2xl' },
        preConfirm: () => getFormData(true)
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(route('users.update', user.id), result.value, {
                onSuccess: () => Swal.fire({ icon: 'success', title: 'Diperbarui!', timer: 1500, showConfirmButton: false }),
                onError: (err) => Swal.fire('Gagal', err.email || 'Terjadi kesalahan sistem.', 'error')
            });
        }
    });
};

const deleteUser = (user) => {
    if (user.id === currentUser.id) {
        return Swal.fire('Ditolak', 'Anda tidak bisa menghapus akun yang sedang digunakan!', 'warning');
    }
    
    Swal.fire({
        title: 'Hapus Akun?',
        text: `Cabut akses login untuk ${user.name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('users.destroy', user.id), {
                onSuccess: () => Swal.fire({ icon: 'success', title: 'Terhapus!', timer: 1500, showConfirmButton: false })
            });
        }
    });
};
</script>

<template>
    <Head title="Manajemen Pengguna" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-wide drop-shadow-sm">
                Manajemen Pengguna
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/40 backdrop-blur-xl border border-white/50 shadow-xl rounded-3xl p-6 md:p-8 relative z-10">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Daftar Akun Kasir & Admin</h3>
                        <button @click="addUser" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition duration-200 hover:-translate-y-1">
                            + Tambah Akun
                        </button>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-white/50 shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/50 border-b border-white/50">
                                    <th class="p-4 font-bold text-gray-700 w-12 text-center">No</th>
                                    <th class="p-4 font-bold text-gray-700">Nama Lengkap</th>
                                    <th class="p-4 font-bold text-gray-700">Email Login</th>
                                    <th class="p-4 font-bold text-gray-700 text-center w-40">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, index) in users" :key="user.id" class="border-b border-white/30 hover:bg-white/50 transition">
                                    <td class="p-4 text-gray-800 text-center font-medium">{{ index + 1 }}</td>
                                    <td class="p-4 text-gray-800 font-bold">
                                        {{ user.name }}
                                        <span v-if="user.id === currentUser.id" class="ml-2 text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">Anda</span>
                                    </td>
                                    <td class="p-4 text-gray-600 font-medium">{{ user.email }}</td>
                                    <td class="p-4 flex justify-center gap-2">
                                        <button @click="editUser(user)" class="bg-indigo-100 text-indigo-600 hover:bg-indigo-200 py-1.5 px-3 rounded-lg font-semibold text-sm">Edit</button>
                                        <button @click="deleteUser(user)" class="bg-red-100 text-red-600 hover:bg-red-200 py-1.5 px-3 rounded-lg font-semibold text-sm">Hapus</button>
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