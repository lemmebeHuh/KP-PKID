<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceOrderUpdate; // <-- IMPORT
use App\Models\ServiceOrderPhoto;  // <-- IMPORT
use App\Http\Requests\Teknisi\StoreServiceOrderUpdateByTechnicianRequest; // <-- IMPORT
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
        // Autorisasi tambahan: Pastikan order ini memang ditugaskan ke teknisi yang sedang login
        if ($serviceOrder->assigned_technician_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION. Anda tidak bisa mengupdate order servis ini.');
        }

        $validatedData = $request->validated();

        // 1. Buat entri ServiceOrderUpdate baru
        $serviceOrderUpdate = $serviceOrder->updates()->create([
            'updated_by_id' => Auth::id(), // ID Teknisi yang login
            'update_type' => $validatedData['update_type'], // Dari hidden input
            'notes' => $validatedData['notes'],
            'status_from' => $serviceOrder->status, // Status saat ini sebelum diubah
            'status_to' => $validatedData['new_status'] ?? $serviceOrder->status, // Status baru jika ada, atau status lama
        ]);

        // 2. Handle upload foto jika ada
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photoFile) {
                $path = $photoFile->store('service_orders/' . $serviceOrder->id . '/updates_by_technician', 'public');
                $serviceOrderUpdate->photos()->create([
                    'file_path' => $path,
                    'uploaded_by_id' => Auth::id(),
                ]);
            }
        }

        // 3. Update status utama di ServiceOrder jika ada perubahan dan diizinkan untuk Teknisi
        $statusUpdated = false;
        if (!empty($validatedData['new_status']) && $serviceOrder->status !== $validatedData['new_status']) {
            // Di sini Anda bisa menambahkan validasi apakah Teknisi boleh mengubah ke status tertentu
            $allowedTechnicianStatuses = ['Diagnosing', 'Menunggu Sparepart', 'In Progress', 'Pengujian', 'Completed']; 
            if (in_array($validatedData['new_status'], $allowedTechnicianStatuses)) {
                $serviceOrder->status = $validatedData['new_status'];

                if ($serviceOrder->status === 'Completed' && is_null($serviceOrder->date_completed)) {
                    $serviceOrder->date_completed = now();
                }
                // Teknisi biasanya tidak set date_picked_up

                $serviceOrder->save();
                $statusUpdated = true;
            } else {
                // Jika teknisi mencoba set status yang tidak diizinkan, kembalikan dengan error
                return redirect()->route('teknisi.service-orders.show', $serviceOrder->id)
                                 ->with('update_error', 'Anda tidak diizinkan mengubah status ke: ' . $validatedData['new_status']);
            }
        }
        return redirect()->route('teknisi.service-orders.show', $serviceOrder->id)
                         ->with('success', 'Update progres berhasil ditambahkan!' . ($statusUpdated ? ' Status order juga telah diperbarui.' : ''));
    }
}