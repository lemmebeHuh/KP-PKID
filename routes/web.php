<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Teknisi\DashboardController as TeknisiDashboardController; // Buat controller ini
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController; // Buat controller ini
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ServiceOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\ServiceCatalogController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ArticleController as PublicArticleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\App;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role.redirect'])->name('dashboard');

// Rute untuk Halaman Pelacakan Servis Publik
Route::get('/lacak-servis', [TrackingController::class, 'showTrackingForm'])->name('tracking.form');
Route::get('/lacak-servis/hasil', [TrackingController::class, 'trackService'])->name('tracking.result'); // Kita akan gunakan GET dengan query parameter

Route::get('/produk', [ProductCatalogController::class, 'index'])->name('products.catalog');
Route::get('/produk/{product:slug}', [ProductCatalogController::class, 'show'])->name('products.show-public');

Route::get('/layanan-kami', [ServiceCatalogController::class, 'index'])->name('services.catalog');
Route::get('/layanan-kami/{service:slug}', [ServiceCatalogController::class, 'show'])->name('services.show-public');

Route::get('/artikel', [PublicArticleController::class, 'index'])->name('articles.index-public');
Route::get('/artikel/{article:slug}', [PublicArticleController::class, 'show'])->name('articles.show-public');

Route::get('/tentang-kami', [PageController::class, 'about'])->name('about');

Route::get('/kontak-kami', [PageController::class, 'contact'])->name('contact');
Route::post('/kontak-kami/kirim', [PageController::class, 'sendContactMessage'])->name('contact.send');


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// // Rute Dashboard Spesifik Peran
//     Route::prefix('admin')->name('admin.')->group(function () {
//         Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
//         // Rute admin lainnya nanti di sini
//     })->middleware('role:admin');

//     Route::prefix('teknisi')->name('teknisi.')->group(function () {
//         Route::get('/teknisi/dashboard', [TeknisiDashboardController::class, 'index'])->name('dashboard');
//         // Rute teknisi lainnya nanti di sini
//     })->middleware('role:teknisi');

//     Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
//         Route::get('/pelanggan/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
//         // Rute pelanggan lainnya nanti di sini
//     })->middleware('role:pelanggan');

Route::middleware(['auth', 'verified'])->group(function () {
     Route::get('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
     Route::get('/semua-notifikasi', [NotificationController::class, 'index'])->name('notifications.index'); // Halaman semua notifikasi
     Route::get('/notifikasi/{notification}', [NotificationController::class, 'readAndRedirect'])->name('notifications.read');
     
    // ... (rute profile dan dashboard pelanggan/teknisi) ...
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk Admin
    Route::middleware(['role.protect:Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('articles', ArticleController::class);
        Route::resource('users', UserController::class);
        Route::resource('service-orders', ServiceOrderController::class);
        // Rute admin lainnya nanti di sini
        Route::post('/service-orders/{serviceOrder}/updates', [ServiceOrderController::class, 'storeUpdate'])
        ->name('service-orders.updates.store');

        Route::get('/service-orders/{serviceOrder}/print-receipt', [ServiceOrderController::class, 'printReceipt'])
             ->name('service-orders.print-receipt'); 

        Route::resource('reviews', ReviewController::class)->except(['create', 'store']);
        Route::resource('complaints', ComplaintController::class)->except(['create', 'store']); 
    });

    // Rute untuk Teknisi (contoh)
    Route::middleware(['role.protect:Teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
        Route::get('/dashboard', [TeknisiDashboardController::class, 'index'])->name('dashboard');
        Route::get('/service-orders/{serviceOrder}', [TeknisiDashboardController::class, 'show'])->name('service-orders.show');
        // Rute teknisi lainnya nanti di sini
        Route::post('/service-orders/{serviceOrder}/updates', [TeknisiDashboardController::class, 'storeServiceUpdate'])
             ->name('service-orders.updates.store'); 
    });
    

    // Rute untuk Pelanggan (contoh)
    Route::middleware(['role.protect:Pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
        // Rute pelanggan lainnya nanti di sini
        Route::get('/service-orders/{serviceOrder}', [PelangganDashboardController::class, 'showServiceOrderDetail'])
             ->name('service-orders.show');
        Route::post('/service-orders/{serviceOrder}/respond-quotation', [PelangganDashboardController::class, 'respondToQuotation'])
             ->name('service-orders.respond-quotation'); 
         Route::get('/service-orders/{serviceOrder}/download-pdf', [PelangganDashboardController::class, 'downloadServiceOrderPdf'])
             ->name('service-orders.download-pdf');

        Route::post('/service-orders/{serviceOrder}/reviews', [PelangganDashboardController::class, 'storeReview'])
             ->name('service-orders.reviews.store'); 
        
        Route::get('/service-orders/{serviceOrder}/complaints/create', [PelangganDashboardController::class, 'createComplaint'])
             ->name('service-orders.complaints.create');
        
        Route::post('/service-orders/{serviceOrder}/complaints', [PelangganDashboardController::class, 'storeComplaint'])
             ->name('service-orders.complaints.store');     

        // Rute baru untuk pelanggan melihat daftar komplain mereka
        Route::get('/komplain-saya', [PelangganDashboardController::class, 'listComplaints'])
             ->name('complaints.index'); // Nama rutenya menjadi pelanggan.complaints.index
    });
});

Route::get('/test-pdf', function () {
    try {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Tes PDF Berhasil!</h1><p>Jika halaman ini muncul, artinya library DomPDF sudah berfungsi dengan benar.</p>');
        return $pdf->stream('test.pdf');
    } catch (Exception $e) {
        // Jika ada error, tampilkan di sini
        dd($e->getMessage());
    }
});

Route::get('/test-image', function () {
    $path = public_path('images/logoP.png');

    $fileExists = file_exists($path);

    dd(
        'Path yang dihasilkan oleh public_path():', $path,
        'Apakah file ditemukan di path tersebut?:', $fileExists ? 'YA, DITEMUKAN' : 'TIDAK, INILAH MASALAHNYA!'
    );
});
require __DIR__.'/auth.php';
