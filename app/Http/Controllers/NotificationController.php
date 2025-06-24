<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //
    // public function markAsRead()
    // {
    //     Auth::user()->unreadNotifications->markAsRead();

    //     // Kembali ke halaman sebelumnya
    //     return back()->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    // }

    public function readAndRedirect(DatabaseNotification $notification)
    {
        // Langkah A: Otorisasi
        // Pastikan notifikasi ini benar-benar milik pengguna yang sedang login
        if (Auth::id() === $notification->notifiable_id) {

            // Langkah B: Tandai sebagai sudah dibaca
            // Ini akan mengisi kolom `read_at` di database
            $notification->markAsRead();
        }

        // Langkah C: Redirect ke URL tujuan yang ada di data notifikasi
        // Jika tidak ada URL, arahkan ke halaman dashboard sesuai peran
        $fallbackRoute = match (Auth::user()->role->name) {
            'Admin' => 'admin.dashboard',
            'Teknisi' => 'teknisi.dashboard',
            default => 'pelanggan.dashboard',
        };

        return redirect($notification->data['url'] ?? route($fallbackRoute));
    }
    
    
}
