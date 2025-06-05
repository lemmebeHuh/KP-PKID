<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceOrder; // Import model ServiceOrder

class TrackingController extends Controller
{
    /**
     * Menampilkan form untuk input nomor servis.
     */
    public function showTrackingForm()
    {
        return view('public.tracking.form'); // View akan kita buat
    }

    /**
     * Mencari dan menampilkan status order servis.
     */
    public function trackService(Request $request)
    {
        $request->validate([
            'service_order_number' => 'required|string|max:50', // Validasi input
        ]);

        $serviceOrderNumber = $request->input('service_order_number');

        $serviceOrder = ServiceOrder::where('service_order_number', $serviceOrderNumber)
                            ->with([ // Eager load relasi yang dibutuhkan
                                'customer' => function ($query) {
                                    // Hanya ambil nama customer untuk privasi di halaman publik
                                    $query->select('id', 'name'); 
                                },
                                'technician' => function ($query) {
                                    $query->select('id', 'name');
                                },
                                'updates' => function ($query) {
                                    $query->with(['updatedBy' => function($q){ $q->select('id','name'); }, 'photos'])
                                          ->select('id','service_order_id', 'updated_by_id', 'update_type', 'notes', 'status_from', 'status_to', 'created_at') // Pilih kolom yg relevan
                                          ->latest();
                                },
                                'warranty' => function($query){
                                    $query->select('id', 'service_order_id', 'start_date', 'end_date', 'terms');
                                }
                            ])
                            ->first(); // Ambil satu hasil

        if (!$serviceOrder) {
            return redirect()->route('tracking.form')
                             ->with('error', 'Nomor Order Servis tidak ditemukan. Pastikan nomor yang Anda masukkan benar.');
        }

        // Hanya pilih kolom yang ingin ditampilkan ke publik dari serviceOrder utama
        // Ini untuk keamanan dan privasi tambahan, agar tidak semua data terekspos
        $publicServiceOrderData = [
            'service_order_number' => $serviceOrder->service_order_number,
            'customer_name' => $serviceOrder->customer ? $this->maskName($serviceOrder->customer->name) : 'Pelanggan', // Samarkan nama
            'device_type' => $serviceOrder->device_type,
            'device_brand_model' => $serviceOrder->device_brand_model,
            'problem_description_short' => \Illuminate\Support\Str::limit($serviceOrder->problem_description, 100), // Ringkasan keluhan
            'date_received' => $serviceOrder->date_received,
            'status' => $serviceOrder->status,
            'estimated_completion_date' => $serviceOrder->estimated_completion_date,
            'final_cost' => $serviceOrder->final_cost, // Pertimbangkan apakah ini boleh tampil publik
            'date_completed' => $serviceOrder->date_completed,
            'date_picked_up' => $serviceOrder->date_picked_up,
            'updates' => $serviceOrder->updates->map(function ($update) { // Hanya data update yg relevan
                return [
                    'update_type' => $update->update_type,
                    'notes' => $update->notes,
                    'status_from' => $update->status_from,
                    'status_to' => $update->status_to,
                    'created_at_formatted' => $update->created_at->format('d M Y, H:i'),
                    'updated_by_name' => $update->updatedBy ? $update->updatedBy->name : 'Sistem',
                    'photos' => $update->photos->map(function ($photo) {
                        return [
                            'file_path' => $photo->file_path,
                            'caption' => $photo->caption,
                        ];
                    }),
                ];
            }),
            'warranty' => $serviceOrder->warranty ? [
                'start_date_formatted' => $serviceOrder->warranty->start_date ? \Carbon\Carbon::parse($serviceOrder->warranty->start_date)->format('d M Y') : null,
                'end_date_formatted' => $serviceOrder->warranty->end_date ? \Carbon\Carbon::parse($serviceOrder->warranty->end_date)->format('d M Y') : null,
                'terms' => $serviceOrder->warranty->terms,
            ] : null,
        ];


        return view('public.tracking.result', ['serviceOrder' => $publicServiceOrderData]);
    }

    // Fungsi helper untuk menyamarkan nama (contoh sederhana)
    private function maskName($name) {
        if (empty($name)) return 'Pelanggan';
        $parts = explode(' ', $name);
        if (count($parts) > 1) {
            return $parts[0] . ' ' . strtoupper(substr($parts[count($parts)-1], 0, 1)) . '.';
        }
        return $name; // Jika hanya satu kata
    }
}