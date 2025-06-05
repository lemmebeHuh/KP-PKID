<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateServiceOrderRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check() && Auth::user()->role->name === 'Admin'; }

    public function rules(): array
    {
        // $serviceOrderId = $this->route('service_order')->id ?? $this->route('service_order');
        // Nomor servis order biasanya tidak diubah, jadi tidak perlu validasi unik untuk itu saat update.

        return [
            'customer_id' => 'required|exists:users,id', // Memastikan customer_id yang dikirim valid
            'device_type' => 'required|string|max:255',
            'device_brand_model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'accessories_received' => 'nullable|string',
            'problem_description' => 'required|string',
            'assigned_technician_id' => 'nullable|exists:users,id',
            'status' => 'required|string|max:100',
            'estimated_completion_date' => 'nullable|date',
            'quotation_details' => 'nullable|string',
            'customer_approval_status' => 'nullable|string|max:100',
            'final_cost' => 'nullable|numeric|min:0',
            'warranty_start_date' => 'nullable|date_format:Y-m-d',
            'warranty_end_date' => 'nullable|date_format:Y-m-d|after_or_equal:warranty_start_date',
            'warranty_terms' => 'nullable|string|max:1000',
            // date_completed dan date_picked_up akan diisi berdasarkan logika status
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pelanggan wajib diisi.', // Seharusnya tidak terjadi karena disabled
            'device_type.required' => 'Jenis perangkat wajib diisi.',
            'problem_description.required' => 'Deskripsi keluhan wajib diisi.',
            'status.required' => 'Status servis wajib diisi.',
        ];
    }
}