<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Untuk validasi kondisional

class StoreServiceOrderRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check() && Auth::user()->role->name === 'Admin'; }

    public function rules(): array
    {
        $rules = [
            'customer_id' => 'nullable|exists:users,id', // Jika pelanggan lama dipilih

            // Validasi untuk pelanggan baru, hanya jika customer_id tidak dipilih
            'new_customer_name' => Rule::requiredIf(empty($this->customer_id)) . '|nullable|string|max:255',
            'new_customer_email' => Rule::requiredIf(empty($this->customer_id)) . '|nullable|email|max:255|unique:users,email',
            'new_customer_phone' => 'nullable|string|max:20',

            'device_type' => 'required|string|max:255',
            'device_brand_model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'accessories_received' => 'nullable|string',
            'problem_description' => 'required|string',
            'assigned_technician_id' => 'nullable|exists:users,id', // Pastikan teknisi ada
            'status' => 'required|string|max:100', // Sesuaikan dengan daftar status Anda
        ];
        return $rules;
    }

    public function messages(): array
    {
        return [
            'customer_id.exists' => 'Pelanggan yang dipilih tidak valid.',
            'new_customer_name.required' => 'Nama pelanggan baru wajib diisi jika tidak memilih pelanggan lama.',
            'new_customer_email.required' => 'Email pelanggan baru wajib diisi jika tidak memilih pelanggan lama.',
            'new_customer_email.email' => 'Format email pelanggan baru tidak valid.',
            'new_customer_email.unique' => 'Email pelanggan baru sudah terdaftar.',
            'device_type.required' => 'Jenis perangkat wajib diisi.',
            'problem_description.required' => 'Deskripsi keluhan wajib diisi.',
            'assigned_technician_id.exists' => 'Teknisi yang dipilih tidak valid.',
            'status.required' => 'Status awal wajib diisi.',
        ];
    }
}