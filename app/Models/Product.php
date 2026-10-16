<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Mengizinkan kolom ini diisi
    protected $fillable = [
        'category_id', 'kode_barcode', 'nama_produk', 
        'harga_beli', 'harga_jual', 'stok'
    ];

    // Relasi: Satu produk memiliki satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}