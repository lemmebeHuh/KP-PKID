<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Import Rule
use Illuminate\Support\Str;


class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool {
         return Auth::check() && Auth::user()->role->name === 'Admin';
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id ?? $this->route('product'); // Dapatkan ID produk dari route

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($productId)],
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Gambar opsional saat update
            // 'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($productId)], // Jika ada slug
        ];
    }

    // protected function prepareForValidation() // Jika perlu auto-generate slug
    // {
    //     if (!empty($this->name) && empty($this->slug)) { // Hanya buat slug jika nama ada dan slug kosong
    //         $this->merge(['slug' => Str::slug($this->name, '-')]);
    //     } elseif (!empty($this->slug)) {
    //         $this->merge(['slug' => Str::slug($this->slug, '-')]);
    //     }
    // }

    public function messages(): array // Pesan error kustom (opsional)
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.unique' => 'Nama produk sudah ada.',
            'price.required' => 'Harga wajib diisi.',
            // ... pesan lainnya
        ];
    }
}