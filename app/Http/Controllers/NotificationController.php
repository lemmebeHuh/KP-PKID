<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //
    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        // Kembali ke halaman sebelumnya
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }
    
    
}
