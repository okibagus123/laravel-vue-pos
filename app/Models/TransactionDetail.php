<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    // 1. Mengizinkan semua kolom diisi secara otomatis
    protected $guarded = [];

    // 2. Relasi: Detail barang ini adalah bagian dari satu Transaksi
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // 3. Relasi: Detail barang ini merujuk pada satu Produk di katalog
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}