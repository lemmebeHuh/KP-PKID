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
    public function index()
    {
        // Ambil semua jasa servis, bisa di-eager load dengan kategori jika perlu
        $services = Service::with('category')
                            ->latest() // Urutkan dari terbaru
                            ->paginate(10); // Sesuaikan jumlah paginasi

        // Ambil kategori jasa untuk filter (opsional, bisa ditambahkan nanti)
        // $serviceCategories = Category::where('type', 'service')->has('services')->orderBy('name')->get();

        return view('public.services.catalog', [
            'services' => $services,
            // 'serviceCategories' => $serviceCategories,
        ]);
    }

    // Method show(Service $service) untuk detail jasa bisa ditambahkan nanti
}