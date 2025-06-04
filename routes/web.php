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
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role.redirect'])->name('dashboard');

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
        // Rute admin lainnya nanti di sini
    });

    // Rute untuk Teknisi (contoh)
    Route::middleware(['role.protect:Teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
        Route::get('/dashboard', [TeknisiDashboardController::class, 'index'])->name('dashboard');
        // Rute teknisi lainnya nanti di sini
    });

    // Rute untuk Pelanggan (contoh)
    Route::middleware(['role.protect:Pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
        // Rute pelanggan lainnya nanti di sini
    });
});

require __DIR__.'/auth.php';
