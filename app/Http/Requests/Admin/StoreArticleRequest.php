<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; // Import Auth
use Illuminate\Support\Str; // Import Str

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); /* atau cek peran admin */ }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:articles,title',
            'slug' => 'nullable|string|max:255|unique:articles,slug', // Dibuat otomatis jika kosong
            'category_id' => 'nullable|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|string|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'author_id' => 'required|exists:users,id',
        ];
    }

    protected function prepareForValidation()
    {
        // Membuat slug otomatis dari title jika slug tidak diisi atau kosong
        if (empty($this->slug)) {
            $this->merge([
                'slug' => Str::slug($this->title, '-'),
            ]);
        } else {
            // Pastikan slug yang diinput manual juga valid
            $this->merge([
                'slug' => Str::slug($this->slug, '-'),
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul artikel wajib diisi.',
            'title.unique' => 'Judul artikel sudah ada.',
            'content.required' => 'Konten artikel wajib diisi.',
            'status.required' => 'Status artikel wajib dipilih.',
            // ... pesan lainnya
        ];
    }
}