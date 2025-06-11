<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest; // <-- IMPORT
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // <-- Tambahkan Request $request
    {
        $query = Product::with('category');

        // Jika ada input pencarian
        if ($request->has('search') && !empty($request->input('search'))) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        $products = $query->latest()->paginate(10);
        $products->appends($request->only('search'));

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua kategori dengan tipe 'product' untuk pilihan di form
        $categories = Category::where('type', 'product')->get(); 
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();

        // Handle Slug jika ada (jika tidak dihandle di FormRequest)
        if (empty($validatedData['slug']) && !empty($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name'], '-');
        }

        if ($request->hasFile('image')) {
            // Simpan gambar ke public/storage/products dan simpan path-nya
            // Pastikan Anda sudah menjalankan `php artisan storage:link`
            $path = $request->file('image')->store('products', 'public');
            $validatedData['image_path'] = $path;
        }

        Product::create($validatedData);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('type', 'product')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();

        // Handle Slug jika ada (jika tidak dihandle di FormRequest)
        if (empty($validatedData['slug']) && !empty($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name'], '-');
        } elseif (!empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['slug'], '-');
        }


        if ($request->hasFile('image')) {
            // 1. Hapus gambar lama jika ada
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            // 2. Simpan gambar baru
            $path = $request->file('image')->store('products', 'public');
            $validatedData['image_path'] = $path;
        }

        $product->update($validatedData);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diperbarui!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // 1. Hapus gambar terkait dari storage jika ada
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        // 2. Hapus produk dari database
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }
}
