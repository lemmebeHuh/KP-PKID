<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article; // <-- IMPORT MODEL ARTICLE
use App\Models\Category; // Jika ingin ada filter kategori

class ArticleController extends Controller
{
    /**
     * Menampilkan halaman daftar artikel publik.
     */
    public function index(Request $request) // <-- Tambahkan Request $request
    {
        // Memulai query dasar
        $query = Article::query()->where('status', 'published')->with(['category', 'author']);

        // Jika ada input pencarian, tambahkan kondisi 'where'
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        // Eksekusi query dengan urutan dan paginasi
        $articles = $query->latest('published_at')->paginate(9);

        // Menambahkan query string pencarian ke link paginasi
        $articles->appends($request->only('search'));

        return view('public.articles.index', [
            'articles' => $articles,
        ]);
    }

    /**
     * Menampilkan halaman detail satu artikel.
     */
    public function show(Article $article) // Route model binding dengan slug
    {
        // Pastikan artikel yang diakses sudah di-publish
        // Jika tidak, tampilkan 404. Ini mencegah akses ke draft via URL langsung.
        if ($article->status !== 'published') {
            abort(404);
        }

        // Eager load relasi
        $article->load(['category', 'author']);

        // Opsional: Ambil beberapa artikel terbaru lainnya, kecuali yang sedang dibuka
        $latestArticles = Article::where('status', 'published')
                                 ->where('id', '!=', $article->id)
                                 ->latest('published_at')
                                 ->take(4)
                                 ->get();

        return view('public.articles.show', [
            'article' => $article,
            'latestArticles' => $latestArticles
        ]);
    }
}