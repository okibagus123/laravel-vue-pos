<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        // Menarik data produk beserta relasi kategorinya
        $products = Product::with('category')->latest()->get();
        $categories = Category::orderBy('nama_kategori')->get();
        
        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        // Validasi presisi sesuai dengan $fillable di model Anda
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'kode_barcode' => 'nullable|string|unique:products,kode_barcode',
            'nama_produk' => 'required|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->back();
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'kode_barcode' => 'nullable|string|unique:products,kode_barcode,' . $product->id,
            'nama_produk' => 'required|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->back();
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back();
    }
}