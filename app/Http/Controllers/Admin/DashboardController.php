<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use App\Models\Complaint;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data untuk Kartu Statistik
        $ongoingServiceOrdersCount = ServiceOrder::whereNotIn('status', ['Completed', 'Picked Up', 'Cancelled'])->count();
        $openComplaintsCount = Complaint::where('status', 'Open')->count();
        $totalUsersCount = User::count();
        $totalProductsCount = Product::count();

        // 2. Data untuk Daftar Aktivitas Terbaru
        $latestServiceOrders = ServiceOrder::with('customer')->latest('date_received')->take(5)->get();
        $latestReviews = Review::with('customer')->latest()->take(5)->get();

        // 3. Data untuk Statistik Chart Order Servis per Bulan (6 bulan terakhir)
        $serviceOrderStats = ServiceOrder::select(
                DB::raw('YEAR(created_at) as year, MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth()) // Data dari awal 6 bulan yang lalu
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Persiapkan label dan data untuk 6 bulan terakhir, termasuk bulan yang 0 order
        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthLabel = $date->translatedFormat('M Y');
            $chartLabels[] = $monthLabel;

            $stat = $serviceOrderStats->first(function ($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });

            $chartData[] = $stat ? $stat->count : 0;
        }

        // 4. Data untuk Top 3 Pelanggan
        $topCustomers = User::select('users.id', 'users.name', DB::raw('COUNT(service_orders.id) as service_orders_count'))
            ->join('service_orders', 'users.id', '=', 'service_orders.customer_id')
            ->groupBy('users.id', 'users.name')
            ->orderBy('service_orders_count', 'desc')
            ->take(3)
            ->get();


        // 5. Kirim semua data ke view
        return view('admin.dashboard', compact(
            'ongoingServiceOrdersCount',
            'openComplaintsCount',
            'totalUsersCount',
            'totalProductsCount',
            'latestServiceOrders',
            'latestReviews',
            'chartLabels',
            'chartData',
            'topCustomers'
        ));
    }
}