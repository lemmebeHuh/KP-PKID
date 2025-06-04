<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10); // Ambil semua kategori, urutkan dari terbaru, dan paginasi
        return view('admin.categories.index', compact('categories')); // Kirim data ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kita bisa passing data tambahan ke view jika perlu,
        // misalnya daftar tipe kategori yang diizinkan
        $types = ['product' => 'Produk', 'service' => 'Jasa Servis', 'article' => 'Artikel'];
        return view('admin.categories.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request) // <-- Gunakan StoreCategoryRequest
    {
        // Validasi sudah otomatis ditangani oleh StoreCategoryRequest.
        // Jika validasi gagal, pengguna akan otomatis di-redirect kembali ke form
        // dengan pesan error.

        // Ambil data yang sudah divalidasi (dan mungkin sudah di-prepare slug-nya)
        $validatedData = $request->validated();

        // Jika slug masih ingin dipastikan dibuat di sini jika kosong (meskipun sudah ada di FormRequest)
        // if (empty($validatedData['slug'])) {
        //     $validatedData['slug'] = Str::slug($validatedData['name'], '-');
        // } else {
        //     $validatedData['slug'] = Str::slug($validatedData['slug'], '-');
        // }

        Category::create($validatedData);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori baru berhasil ditambahkan!'); // Pesan sukses
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $types = ['product' => 'Produk', 'service' => 'Jasa Servis', 'article' => 'Artikel'];
        return view('admin.categories.edit', compact('category', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validatedData = $request->validated();

        // Jika slug dikosongkan, buat ulang dari nama
        // (sudah dihandle di prepareForValidation pada UpdateCategoryRequest,
        // tapi bisa juga ditaruh di sini sebagai fallback jika perlu)
        if (empty($validatedData['slug']) && !empty($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name'], '-');
        } elseif (!empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['slug'], '-');
        }


        $category->update($validatedData);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Tambahkan logika tambahan di sini jika ada relasi yang perlu ditangani
        // sebelum menghapus kategori, misalnya jika ada produk yang terkait
        // dan Anda ingin mencegah penghapusan atau mengalihkan produk tersebut.
        // Untuk saat ini, kita langsung hapus.
        // Contoh: jika kategori punya produk dan kita tidak ingin menghapus kategori jika masih ada produk:
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                             ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk terkait.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil dihapus!');
    }
}
