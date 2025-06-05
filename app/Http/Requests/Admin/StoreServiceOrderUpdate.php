<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreServiceOrderUpdate extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan user adalah admin (atau teknisi jika teknisi juga boleh update dari panelnya)
        return Auth::check() && Auth::user()->role->name === 'Admin';
    }

    public function rules(): array
    {
        return [
            'notes' => 'required|string',
            'new_status' => 'nullable|string|max:100', // Validasi dengan daftar status yang ada jika perlu
            'update_type' => 'required|string|max:100',
            'photos'   => 'nullable|array', // Memastikan photos adalah array (untuk multiple files)
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Validasi setiap file
        ];
    }

    public function messages(): array
    {
        return [
            'notes.required' => 'Catatan update tidak boleh kosong.',
            'photos.*.image' => 'Semua file yang diunggah harus berupa gambar.',
            'photos.*.mimes' => 'Format gambar yang diizinkan: jpeg, png, jpg, gif, webp.',
            'photos.*.max' => 'Ukuran setiap gambar maksimal 2MB.',
        ];
    }
}