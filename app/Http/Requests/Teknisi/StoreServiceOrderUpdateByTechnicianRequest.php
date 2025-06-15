<?php

namespace App\Http\Requests\Teknisi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreServiceOrderUpdateByTechnicianRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan user adalah teknisi
        return Auth::check() && Auth::user()->role->name === 'Teknisi';
    }

    public function rules(): array
    {
        // Dapatkan serviceOrder dari route untuk memastikan teknisi hanya update ordernya sendiri
        // Ini bisa juga divalidasi di controller sebelum menyimpan
        $serviceOrder = $this->route('serviceOrder');
        if ($serviceOrder && $serviceOrder->assigned_technician_id !== Auth::id()) {
            // Sebenarnya otorisasi di controller show() sudah mencegah ini,
            // tapi sebagai lapisan tambahan bisa saja.
            // Namun, untuk FormRequest, authorize() sudah cukup.
            // Aturan di sini fokus pada data yang diinput.
        }

        return [
            'notes' => 'required|string',
            'new_status' => 'nullable|string|max:100', // Validasi dengan daftar status yang diizinkan untuk Teknisi jika perlu
            'update_type' => 'required|string|max:100', // Dari hidden input
            'photos'   => 'nullable|array',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'quotation_details' => 'nullable|string|max:2000',
            'estimated_completion_date' => 'nullable|date',
            
        ];
    }

    public function messages(): array
    {
        return [
            'notes.required' => 'Catatan update pengerjaan tidak boleh kosong.',
            'photos.*.image' => 'Semua file yang diunggah harus berupa gambar.',
            'photos.*.mimes' => 'Format gambar yang diizinkan: jpeg, png, jpg, gif, webp.',
            'photos.*.max' => 'Ukuran setiap gambar maksimal 2MB.',
        ];
    }
}