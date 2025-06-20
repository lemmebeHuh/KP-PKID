<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceOrderUpdate; // <-- IMPORT
use App\Models\ServiceOrderPhoto;  // <-- IMPORT
use App\Http\Requests\Teknisi\StoreServiceOrderUpdateByTechnicianRequest; // <-- IMPORT
use App\Notifications\OrderStatusUpdatedNotification;
use App\Notifications\ServiceStatusUpdated;
use Illuminate\Support\Facades\Storage;


class DashboardController extends Controller
{
    public function index()
    {
        $technicianId = Auth::id(); // Dapatkan ID teknisi yang sedang login

        $assignedOrders = ServiceOrder::with('customer') // Eager load data pelanggan
                            ->where('assigned_technician_id', $technicianId)
                            ->whereNotIn('status', ['Completed', 'Picked Up', 'Cancelled']) // Hanya tampilkan yang masih aktif/perlu dikerjakan
                            ->orderBy('date_received', 'asc') // Urutkan berdasarkan tanggal diterima (yang paling lama masuk duluan)
                            ->paginate(10);

        return view('teknisi.dashboard', compact('assignedOrders'));
    }

    public function show(ServiceOrder $serviceOrder) // Route Model Binding
    {
        //  dd(
        //     'ID Teknisi yang Login saat ini (Auth::id()):', Auth::id(),
        //     'ID Teknisi yang ditugaskan di order ini (assigned_technician_id):', $serviceOrder->assigned_technician_id
        // );
        // Autorisasi: Pastikan order ini memang ditugaskan ke teknisi yang sedang login
        if ($serviceOrder->assigned_technician_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION. Anda tidak ditugaskan untuk order servis ini.');
        }

        // Eager load relasi yang dibutuhkan
        $serviceOrder->load([
            'customer',
            'updates' => function ($query) {
                $query->with(['updatedBy', 'photos'])->latest();
            }
        ]);

        // Daftar status yang bisa dipilih teknisi saat update (mungkin berbeda dari Admin)
        $statuses = [
            // 'Pending' => 'Pending', // Teknisi mungkin tidak set status awal ini
            // 'Menunggu Diagnosa' => 'Menunggu Diagnosa',
            'Diagnosing' => 'Sedang Diagnosa',
            // 'Menunggu Persetujuan Pelanggan' => 'Menunggu Persetujuan Pelanggan', // Mungkin dihandle Admin
            // 'Persetujuan Diterima' => 'Persetujuan Diterima',
            // 'Quotation Ditolak' => 'Quotation Ditolak',
            'Menunggu Sparepart' => 'Menunggu Sparepart',
            'In Progress' => 'Sedang Dikerjakan',
            'Pengujian' => 'Pengujian',
            'Completed' => 'Servis Selesai - Siap Diambil', // Teknisi bisa set ini
            // 'Picked Up' => 'Sudah Diambil', // Diinput Admin
            // 'Cancelled' => 'Dibatalkan', // Diinput Admin
        ];

        return view('teknisi.service_orders.show', compact('serviceOrder', 'statuses'));

        
    }

    public function storeServiceUpdate(StoreServiceOrderUpdateByTechnicianRequest $request, ServiceOrder $serviceOrder)
    {
        // Otorisasi: Pastikan lagi teknisi ini yang berhak
        if ($serviceOrder->assigned_technician_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        $validatedData = $request->validated();

        // 1. Buat Log Update di tabel service_order_updates
        $newUpdate = $serviceOrder->updates()->create([
            'updated_by_id' => Auth::id(),
            'update_type'   => 'Update Teknisi',
            'notes'         => $validatedData['notes'],
            'status_from'   => $serviceOrder->status,
            'status_to'     => $validatedData['new_status'] ?? $serviceOrder->status,
        ]);

        // 2. Handle upload foto jika ada dan hubungkan ke log update yang baru dibuat
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photoFile) {
                $path = $photoFile->store('service_orders/' . $serviceOrder->id, 'public');
                $newUpdate->photos()->create([
                    'file_path'      => $path,
                    'uploaded_by_id' => Auth::id(),
                ]);
            }
        }

        // 3. Siapkan data untuk diupdate ke tabel service_orders utama
        $orderNeedsSaving = false;

        // Update status utama jika ada perubahan
        if (!empty($validatedData['new_status']) && $serviceOrder->status !== $validatedData['new_status']) {
            $serviceOrder->status = $validatedData['new_status'];
            $orderNeedsSaving = true;

            // Otomatis isi tanggal selesai jika statusnya 'Completed'
            if ($serviceOrder->status === 'Completed' && is_null($serviceOrder->date_completed)) {
                $serviceOrder->date_completed = now();
            }
        }
        
        // Update detail diagnosa/quotation jika field-nya diisi
        if ($request->filled('quotation_details')) {
            $serviceOrder->quotation_details = $validatedData['quotation_details'];
            $orderNeedsSaving = true;
        }
        
        // Update estimasi tanggal jika diisi
        if ($request->filled('estimated_completion_date')) {
            $serviceOrder->estimated_completion_date = $validatedData['estimated_completion_date'];
            $orderNeedsSaving = true;
        }

        // Hanya jalankan proses save() jika ada perubahan pada order utama
        if ($orderNeedsSaving) {
            $serviceOrder->save();
        }
        
        // ====================================================================
        // !! BAGIAN PENGIRIMAN NOTIFIKASI YANG HILANG ADA DI SINI !!
        // ====================================================================
        // Cek apakah status pada order utama benar-benar berubah sebelum mengirim notifikasi
        if ($serviceOrder->wasChanged('status')) {
            $customer = $serviceOrder->customer;
            if ($customer) {
                $customer->notify(new OrderStatusUpdatedNotification($serviceOrder));
            }
        }
        
        return redirect()->route('teknisi.service-orders.show', $serviceOrder->id)
                         ->with('success', 'Update progres berhasil ditambahkan!');
    }
    
}