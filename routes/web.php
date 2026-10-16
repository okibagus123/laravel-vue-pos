<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;


// 1. Ubah Halaman Utama: Langsung arahkan ke Login (Hapus halaman Welcome bawaan)
Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    $today = Carbon::today();
    $yesterday = Carbon::yesterday();

    // 1. Menghitung Pendapatan (Asumsi nama kolom di tabel transactions adalah 'total_tagihan')
    $pendapatanHariIni = Transaction::whereDate('created_at', $today)->sum('total_harga');
$pendapatanKemarin = Transaction::whereDate('created_at', $yesterday)->sum('total_harga');

    // Menghitung persentase kenaikan/penurunan
    $persentase = 0;
    if ($pendapatanKemarin > 0) {
        $persentase = (($pendapatanHariIni - $pendapatanKemarin) / $pendapatanKemarin) * 100;
    } elseif ($pendapatanHariIni > 0) {
        $persentase = 100; // Jika kemarin 0 tapi hari ini ada pendapatan
    }

    // 2. Menghitung Jumlah Transaksi Hari Ini
    $jumlahTransaksi = Transaction::whereDate('created_at', $today)->count();

    // 3. Menghitung Produk Aktif & Stok Menipis (Stok di bawah 5)
    $produkAktif = Product::count();
    $stokMenipis = Product::where('stok', '<=', 5)->count();

    return Inertia::render('Dashboard', [
        'pendapatanHariIni' => $pendapatanHariIni,
        'persentase' => round($persentase, 1),
        'jumlahTransaksi' => $jumlahTransaksi,
        'produkAktif' => $produkAktif,
        'stokMenipis' => $stokMenipis
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. Kumpulan Rute Utama (Hanya bisa diakses jika sudah login)
Route::middleware('auth')->group(function () {
    
    // Rute Aplikasi POS Kasir
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('transactions', TransactionController::class);

    // Rute tambahan untuk Riwayat, Export Laporan, dan Cetak Struk
    Route::get('/transaksi/export', [TransactionController::class, 'export'])->name('transactions.export'); // <-- RUTE EXPORT DITAMBAHKAN DI SINI
    Route::get('/riwayat-transaksi', [TransactionController::class, 'history'])->name('transactions.history');
    Route::get('/transaksi/{transaction}/cetak', [TransactionController::class, 'print'])->name('transactions.print');
    
    // Rute Manajemen Pengguna
    Route::resource('users', \App\Http\Controllers\UserController::class);
    
    // Rute Manajemen Profil (Cukup ditulis 1 kali saja)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// 4. File rute bawaan otentikasi (Login, Logout, Register)
require __DIR__.'/auth.php';