<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // 1. Mengizinkan semua kolom (seperti nama_kategori) diisi
    protected $guarded = [];

    // 2. Relasi: Satu Kategori memiliki banyak Produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}