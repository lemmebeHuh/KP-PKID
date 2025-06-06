<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // <-- IMPORT Mail facade


class PageController extends Controller
{
    /**
     * Menampilkan halaman "Tentang Kami".
     */
    public function about()
    {
        return view('public.pages.about'); // View ini akan kita buat
    }

    public function contact()
    {
        return view('public.pages.contact'); // View ini akan kita buat
    }

    /**
     * Mengirim pesan dari form kontak.
     */
    public function sendContactMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Di sini kita akan menambahkan logika untuk mengirim email ke admin
        // Contoh sederhana:
        // Mail::to('admin@pangkalan_komputer.id')->send(new \App\Mail\ContactFormMail($validated));
        // Untuk saat ini, kita hanya akan redirect dengan pesan sukses

        return redirect()->route('contact')
                         ->with('success', 'Pesan Anda telah berhasil terkirim! Terima kasih telah menghubungi kami.');
    }
    // Method lain seperti contact() bisa ditambahkan nanti
}