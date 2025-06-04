<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool { 
        return Auth::check() && Auth::user()->role->name === 'Admin'; 
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'nullable|exists:categories,id', // Pastikan kategori ada jika diisi
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Validasi gambar
            // 'slug' => 'nullable|string|max:255|unique:products,slug', // Jika Anda punya slug
        ];
    }

    // protected function prepareForValidation() // Jika perlu auto-generate slug
    // {
    //     if (empty($this->slug) && !empty($this->name)) {
    //         $this->merge(['slug' => Str::slug($this->name, '-')]);
    //     }
    // }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.unique' => 'Nama produk sudah ada.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'stock_quantity.required' => 'Stok wajib diisi.',
            'stock_quantity.integer' => 'Stok harus berupa angka bulat.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diizinkan: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}