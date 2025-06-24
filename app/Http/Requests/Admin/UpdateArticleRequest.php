<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // Import Rule

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        $articleId = $this->route('article')->id ?? $this->route('article');

        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('articles', 'title')->ignore($articleId)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('articles', 'slug')->ignore($articleId)],
            'category_id' => 'nullable|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|string|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'author_id' => 'required|exists:users,id',
        ];
    }

    protected function prepareForValidation()
    {
        if (!empty($this->title) && empty($this->slug)) {
            $this->merge(['slug' => Str::slug($this->title, '-')]);
        } elseif (!empty($this->slug)) {
             $this->merge(['slug' => Str::slug($this->slug, '-')]);
        }
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul artikel wajib diisi.',
            'title.unique' => 'Judul artikel sudah ada.',
            'content.required' => 'Konten artikel wajib diisi.',
            // ... pesan lainnya
        ];
    }
}