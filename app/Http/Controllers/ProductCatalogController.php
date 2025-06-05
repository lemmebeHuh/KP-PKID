<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Kita akan gunakan ini nanti
use App\Models\Category; // Kita akan gunakan ini nanti

class ProductCatalogController extends Controller
{
    /**
     * Menampilkan halaman katalog produk publik.
     */
    public function index()
    {
        // Ambil produk yang aktif (jika ada kolom 'is_active' di tabel products, jika tidak, ambil semua)
        // Eager load relasi 'category' untuk menampilkan nama kategori
        $products = Product::with('category')
                            ->latest() // Urutkan dari terbaru
                            ->paginate(12); // Tampilkan 12 produk per halaman (bisa disesuaikan)

        // Ambil kategori produk untuk filter (opsional, bisa ditambahkan nanti)
        // $productCategories = Category::where('type', 'product')->has('products')->orderBy('name')->get();

        return view('public.products.catalog', [
            'products' => $products,
            // 'productCategories' => $productCategories, // Kirim jika Anda membuat filter kategori
        ]);
    }

    public function show(Product $product) // Route model binding dengan slug
    {
        // Eager load kategori jika belum otomatis ter-load atau ingin memastikan
        $product->load('category'); 

        // Anda bisa menambahkan logika untuk mengambil produk terkait atau rekomendasi nanti
        // $relatedProducts = Product::where('category_id', $product->category_id)
        //                             ->where('id', '!=', $product->id)
        //                             ->take(4)
        //                             ->get();

        return view('public.products.show', [
            'product' => $product,
            // 'relatedProducts' => $relatedProducts,
        ]);
    }
}