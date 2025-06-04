<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\UpdateArticleRequest;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relasi category dan author
        $articles = Article::with(['category', 'author'])->latest()->paginate(10); 
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('type', 'article')->get();
        $statuses = ['draft' => 'Draft', 'published' => 'Published'];
        // Ambil user yang bisa jadi penulis (contoh: Admin dan Teknisi)
        $authors = User::whereHas('role', function ($query) {
            $query->whereIn('name', ['Admin', 'Teknisi']);
        })->orderBy('name')->get();

        return view('admin.articles.create', compact('categories', 'statuses', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $validatedData = $request->validated();
    // $validatedData['author_id'] = Auth::id();

    if ($request->hasFile('featured_image')) {
        $path = $request->file('featured_image')->store('articles/featured', 'public');
        $validatedData['featured_image_path'] = $path;
    }

    // Otomatis set published_at jika status adalah 'published'
    if (isset($validatedData['status']) && $validatedData['status'] === 'published') {
        $validatedData['published_at'] = now(); // Menggunakan helper now() untuk waktu saat ini
    } else {
        // Jika status bukan 'published' (misalnya 'draft'), pastikan published_at adalah null
        // Ini penting jika ada kemungkinan field published_at dikirim dari form (meskipun saat ini tidak)
        // atau jika Anda ingin memastikan konsistensi.
        $validatedData['published_at'] = null;
    }

    Article::create($validatedData);

    return redirect()->route('admin.articles.index')
                     ->with('success', 'Artikel baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $categories = Category::where('type', 'article')->get();
        $statuses = ['draft' => 'Draft', 'published' => 'Published'];
        // Ambil user yang bisa jadi penulis
        $authors = User::whereHas('role', function ($query) {
            $query->whereIn('name', ['Admin', 'Teknisi']);
        })->orderBy('name')->get();
        
        return view('admin.articles.edit', compact('article', 'categories', 'statuses', 'authors'));
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validatedData = $request->validated();

    if ($request->hasFile('featured_image')) {
        if ($article->featured_image_path) {
            Storage::disk('public')->delete($article->featured_image_path);
        }
        $path = $request->file('featured_image')->store('articles/featured', 'public');
        $validatedData['featured_image_path'] = $path;
    }

    // Logika untuk published_at saat update
    if (isset($validatedData['status'])) {
        if ($validatedData['status'] === 'published' && is_null($article->published_at)) {
            // Jika status diubah menjadi 'published' dan belum pernah ada tanggal publikasi
            $validatedData['published_at'] = now();
        } elseif ($validatedData['status'] === 'draft') {
            // Jika status diubah kembali ke 'draft', mungkin Anda ingin mengosongkan published_at
            // atau membiarkannya (tergantung kebutuhan). Untuk konsistensi, kita bisa kosongkan.
            // $validatedData['published_at'] = null; 
            // Namun, jika artikel sudah pernah publish, biasanya tanggal publish pertama tetap disimpan.
            // Jadi, kita bisa juga tidak melakukan apa-apa pada published_at jika diubah ke draft.
            // Keputusan ini tergantung alur bisnis Anda.
            // Untuk saat ini, kita hanya set saat pertama kali publish.
        }
        // Jika status tidak berubah atau sudah published sebelumnya, biarkan published_at apa adanya
        // kecuali jika Anda secara eksplisit ingin mengubahnya (misal ada input field untuk published_at)
    }


    $article->update($validatedData);

    return redirect()->route('admin.articles.index')
                     ->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        if ($article->featured_image_path) {
            Storage::disk('public')->delete($article->featured_image_path);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Artikel berhasil dihapus!');
    }
}
