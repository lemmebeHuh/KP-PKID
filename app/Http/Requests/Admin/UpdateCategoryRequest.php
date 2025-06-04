<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // Import Rule facade

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role->name === 'Admin'; // Atau otorisasi yang lebih spesifik
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Mendapatkan ID kategori dari route parameter
        // $this->route('category') akan mengembalikan instance Category atau ID-nya
        // Jika route model binding aktif, $this->category akan berisi instance Category
        $categoryId = $this->route('category')->id ?? $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($categoryId),
            ],
            'type' => 'required|string|in:product,service,article',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if (empty($this->slug) && !empty($this->name)) {
            $this->merge([
                'slug' => Str::slug($this->name, '-'),
            ]);
        } elseif (!empty($this->slug)) {
            $this->merge([
                'slug' => Str::slug($this->slug, '-'),
            ]);
        }
    }

    /**
     * Get custom messages for validator errors.
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