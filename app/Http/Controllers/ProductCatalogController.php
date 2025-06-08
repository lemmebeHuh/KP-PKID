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
    public function index(Request $request)
    {
        $productCategories = Category::where('type', 'product')->has('products')->orderBy('name')->get();
        $selectedCategorySlug = $request->input('kategori');
        $selectedCategory = null;

        // Memulai query dasar
        $productsQuery = Product::with('category');

        // Filter berdasarkan Kategori
        if ($selectedCategorySlug) {
            $selectedCategory = Category::where('slug', $selectedCategorySlug)->firstOrFail();
            $productsQuery->where('category_id', $selectedCategory->id);
        }

        // Filter berdasarkan Pencarian
        if ($request->has('search') && !empty($request->input('search'))) {
            $searchTerm = $request->input('search');
            $productsQuery->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // LOGIKA SORTING BARU
        $sortOption = $request->input('sort', 'latest'); // Default ke 'latest' jika tidak ada input
        switch ($sortOption) {
            case 'price_asc':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price', 'desc');
                break;
            default: // 'latest'
                $productsQuery->latest(); // Sama dengan orderBy('created_at', 'desc')
                break;
        }

        $products = $productsQuery->paginate(12);

        // Menambahkan semua query string ke link paginasi
        $products->appends($request->all());

        return view('public.products.catalog', [
            'products' => $products,
            'productCategories' => $productCategories,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category');

        // Ambil 4 produk terkait dari kategori yang sama, kecuali produk ini sendiri
        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->inRandomOrder() // Ambil acak
                                 ->take(4)
                                 ->get();

        return view('public.products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts, // Kirim data produk terkait ke view
        ]);
    }
}