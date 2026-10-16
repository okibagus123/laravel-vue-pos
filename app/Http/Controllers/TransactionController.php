<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction; 
use App\Models\TransactionDetail; 
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $products = Product::where('stok', '>', 0)->latest()->get();
        return Inertia::render('Transactions/Index', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'total_tagihan' => 'required|numeric',
            'uang_dibayar' => 'required|numeric|gte:total_tagihan', // Uang harus >= tagihan
            'kembalian' => 'required|numeric'
        ]);

        DB::transaction(function () use ($request) {
            
            // 1. Simpan ke tabel transactions sesuai struktur migration Anda
            $transaction = Transaction::create([
                'user_id' => Auth::id(), // Mengambil ID user/kasir yang sedang login
                'nomor_struk' => 'TRX-' . strtoupper(uniqid()), // Membuat nomor struk acak unik
                'total_harga' => $request->total_tagihan,
                'uang_dibayar' => $request->uang_dibayar,
                'kembalian' => $request->kembalian,
            ]);

            // 2. Looping isi keranjang untuk disimpan ke detail dan kurangi stok
            foreach ($request->cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'kuantitas' => $item['qty'], // Menggunakan 'kuantitas' sesuai migration
                    'harga_satuan' => $item['harga_jual'], // Menambahkan harga_satuan sesuai migration
                    'subtotal' => $item['harga_jual'] * $item['qty'],
                ]);

                // 3. Kurangi stok produk
                Product::find($item['id'])->decrement('stok', $item['qty']);
            }
        });

        return redirect()->back();
    }

    // ==========================================
    // TAMBAHAN UNTUK FITUR RIWAYAT & CETAK STRUK
    // ==========================================

    public function history()
    {
        // Mengambil transaksi dari yang terbaru, beserta data kasirnya
        $transactions = Transaction::with('user')->latest()->get();
        
        return Inertia::render('Transactions/History', [
            'transactions' => $transactions
        ]);
    }

    public function print(Transaction $transaction)
    {
        // Memuat detail barang dan produknya untuk ditampilkan di struk
        $transaction->load(['user', 'details.product']);
        
        // Kita gunakan Blade biasa (bukan Vue) agar format cetak printer thermal lebih rapi
        return view('print.struk', compact('transaction'));
    }
    public function export()
    {
        // Ambil semua data transaksi beserta nama kasirnya
        $transactions = Transaction::with('user')->latest()->get();

        $filename = "laporan_penjualan_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'Nomor Struk', 'Nama Kasir', 'Total Transaksi', 'Waktu'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // Tulis header kolom

            $row = 1;
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $row++,
                    $transaction->nomor_struk,
                    $transaction->user ? $transaction->user->name : 'Admin',
                    $transaction->total_harga,
                    $transaction->created_at->format('d-m-Y H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}