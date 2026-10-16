# 🛒 Point of Sale (POS) System — Laravel 12 & Vue 3 (Inertia.js)

Aplikasi kasir (*Point of Sale*) modern berbasis *Single Page Application* (SPA) yang dibangun menggunakan **Laravel 12**, **Inertia.js**, **Vue 3**, dan **Tailwind CSS**. Sistem ini dirancang untuk membantu operasional ritel atau toko dalam mengelola kategori barang, inventaris produk, hingga pencatatan transaksi penjualan secara cepat dan responsif tanpa *reload* halaman.

---

## 🚀 Fitur Utama

* **Dashboard Interaktif:** Ringkasan aktivitas toko dan navigasi cepat antar modul menggunakan antarmuka SPA yang mulus.
* **Manajemen Kategori:** Pengelolaan kategori produk (*Create, Read, Update, Delete*) untuk memudahkan pengelompokan barang.
* **Manajemen Produk & Stok:** Pencatatan data barang meliputi nama produk, kategori, harga beli/jual, dan pemantauan jumlah stok secara *real-time*.
* **Modul Transaksi Kasir (POS):** Antarmuka kasir yang cepat dan intuitif untuk memilih produk ke keranjang, menghitung total belanja otomatis, memproses pembayaran, serta mengurangi stok produk secara otomatis saat transaksi selesai.
* **Autentikasi Pengguna:** Sistem keamanan *login* untuk kasir/admin sebelum mengakses sistem operasional.

---

## 🛠️ Tech Stack

* **Backend Framework:** Laravel 12 (PHP)
* **Frontend Framework:** Vue 3 (Composition API)
* **Full-Stack Adapter:** Inertia.js (Monolith SPA Architecture)
* **UI & Styling:** Tailwind CSS
* **Database:** MySQL (`kasir_pos`)
* **Build Tool:** Vite

---

## ⚙️ Cara Instalasi & Menjalankan Secara Lokal

1. **Clone repositori ini ke komputer Anda:**
   ```bash
   git clone [https://github.com/okibagus123/laravel-vue-pos.git](https://github.com/okibagus123/laravel-vue-pos.git)
   cd laravel-vue-pos
