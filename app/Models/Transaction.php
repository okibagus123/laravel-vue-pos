<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // 1. Mengizinkan semua kolom diisi (mass-assignment)
    protected $guarded = [];

    // 2. Relasi: Transaksi ini dilayani oleh satu User (Kasir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 3. Relasi: Satu transaksi memiliki banyak detail barang
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}