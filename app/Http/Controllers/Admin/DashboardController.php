<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use App\Models\Complaint;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data untuk Kartu Statistik
        $ongoingServiceOrdersCount = ServiceOrder::whereNotIn('status', ['Completed', 'Picked Up', 'Cancelled'])->count();
        $openComplaintsCount = Complaint::where('status', 'Open')->count();
        $totalUsersCount = User::count();
        $totalProductsCount = \App\Models\Product::count(); // Ambil dari model Product

        // 2. Ambil data untuk Daftar Aktivitas Terbaru
        $latestServiceOrders = ServiceOrder::with('customer')
                                            ->latest('date_received')
                                            ->take(5)
                                            ->get();

        $latestReviews = Review::with('customer')
                                ->latest()
                                ->take(5)
                                ->get();

        // 3. Kirim semua data ke view
        return view('admin.dashboard', compact(
            'ongoingServiceOrdersCount',
            'openComplaintsCount',
            'totalUsersCount',
            'totalProductsCount',
            'latestServiceOrders',
            'latestReviews'
        ));
    }
}