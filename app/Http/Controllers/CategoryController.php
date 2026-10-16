<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        // Mengambil semua kategori diurutkan dari yang terbaru
        $categories = Category::latest()->get();
        
        return Inertia::render('Categories/Index', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori'
        ]);

        Category::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back();
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $category->id
        ]);

        $category->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back();
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back();
    }
}