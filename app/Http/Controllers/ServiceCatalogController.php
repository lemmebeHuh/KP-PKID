<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service; // <-- IMPORT MODEL SERVICE (master jenis jasa)
use App\Models\Category; // Import Category jika ingin menampilkan filter kategori nanti

class ServiceCatalogController extends Controller
{
    /**
     * Menampilkan halaman daftar jasa servis publik.
     */
    public function index(Request $request) // <-- Tambahkan Request $request
    {
        // Ambil semua kategori dengan tipe 'service' yang memiliki setidaknya satu jasa
        $serviceCategories = Category::where('type', 'service')->has('services')->orderBy('name')->get();

        // Ambil filter dan pencarian dari request
        $selectedCategorySlug = $request->input('kategori');
        $searchTerm = $request->input('search');
        $selectedCategory = null;

        // Memulai query dasar
        $servicesQuery = Service::with('category');

        // Filter berdasarkan Kategori
        if ($selectedCategorySlug) {
            $selectedCategory = Category::where('slug', $selectedCategorySlug)->firstOrFail();
            $servicesQuery->where('category_id', $selectedCategory->id);
        }

        // Filter berdasarkan Pencarian
        if ($searchTerm) {
            $servicesQuery->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        $services = $servicesQuery->latest()->paginate(10);

        // Menambahkan semua query string (kategori & search) ke link paginasi
        $services->appends($request->all());

        return view('public.services.catalog', [
            'services' => $services,
            'serviceCategories' => $serviceCategories,
            'selectedCategory' => $selectedCategory, // <-- Variabel ini sekarang selalu terdefinisi
        ]);
    }

    public function show(Service $service) // Route model binding dengan slug
    {
        // Eager load kategori jika belum otomatis ter-load
        $service->load('category');

        return view('public.services.show', [
            'service' => $service,
        ]);
    }
}