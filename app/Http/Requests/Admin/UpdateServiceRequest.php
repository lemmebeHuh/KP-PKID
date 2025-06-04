<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Import Rule
use Illuminate\Support\Str; // Jika perlu slug

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool { 
        return Auth::check() && Auth::user()->role->name === 'Admin'; 
    }

    public function rules(): array
    {
        $serviceId = $this->route('service')->id ?? $this->route('service');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('services', 'name')->ignore($serviceId)],
            'category_id' => 'nullable|exists:categories,id',
            'estimated_price' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('services', 'slug')->ignore($serviceId)], // Jika perlu slug
        ];
    }

    protected function prepareForValidation() // Jika perlu auto-generate slug
    {
        if (!empty($this->name) && empty($this->slug)) {
            $this->merge(['slug' => Str::slug($this->name, '-')]);
        } elseif (!empty($this->slug)) {
             $this->merge(['slug' => Str::slug($this->slug, '-')]);
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama jasa servis wajib diisi.',
            'name.unique' => 'Nama jasa servis sudah ada.',
            // ... pesan lainnya
        ];
    }
}