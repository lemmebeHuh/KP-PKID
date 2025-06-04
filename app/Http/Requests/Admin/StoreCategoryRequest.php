<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str; // Import Str facade
use Illuminate\Support\Facades\Auth;


class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Kita asumsikan hanya pengguna yang terautentikasi (misalnya admin) yang boleh membuat kategori.
        // Anda bisa menambahkan logika otorisasi yang lebih spesifik di sini jika perlu.
        return Auth::check() && Auth::user()->role->name === 'Admin'; // atau auth()->user()->role->name === 'Admin'
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'type' => 'required|string|in:product,service,article', // Sesuaikan dengan tipe yang Anda definisikan
            'description' => 'nullable|string',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Membuat slug otomatis dari nama jika slug tidak diisi atau kosong
        if (empty($this->slug)) {
            $this->merge([
                'slug' => Str::slug($this->name, '-'),
            ]);
        } else {
            // Pastikan slug yang diinput manual juga valid
            $this->merge([
                'slug' => Str::slug($this->slug, '-'),
            ]);
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori tidak boleh kosong.',
            'name.unique' => 'Nama kategori sudah ada.',
            'slug.unique' => 'Slug sudah ada, gunakan slug lain atau kosongkan agar dibuat otomatis.',
            'type.required' => 'Tipe kategori harus dipilih.',
            'type.in' => 'Tipe kategori tidak valid.',
        ];
    }
}