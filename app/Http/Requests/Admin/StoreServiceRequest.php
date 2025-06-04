<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Jika perlu slug

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check() && Auth::user()->role->name === 'Admin'; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services,name',
            'category_id' => 'nullable|exists:categories,id',
            'estimated_price' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:services,slug', // Jika perlu slug untuk Jasa Servis
        ];
    }

    protected function prepareForValidation() // Jika perlu auto-generate slug
    {
        if (empty($this->slug) && !empty($this->name)) {
            $this->merge(['slug' => Str::slug($this->name, '-')]);
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama jasa servis wajib diisi.',
            'name.unique' => 'Nama jasa servis sudah ada.',
            'estimated_price.numeric' => 'Estimasi harga harus angka.',
        ];
    }
}