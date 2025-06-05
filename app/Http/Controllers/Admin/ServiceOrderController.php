<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceOrderRequest;
use App\Http\Requests\Admin\StoreServiceOrderUpdate;
use App\Http\Requests\Admin\UpdateServiceOrderRequest;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth; // Import Auth jika digunakan untuk updated_by_id


class ServiceOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceOrders = ServiceOrder::with(['customer', 'technician'])
                                    ->latest('date_received') // Urutkan berdasarkan tanggal diterima terbaru
                                    ->paginate(15); 
        return view('admin.service_orders.index', compact('serviceOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = User::whereHas('role', function ($query) {
            $query->where('name', 'Pelanggan');
        })->orderBy('name')->get();

        // Ambil user dengan peran 'Teknisi'
        $technicians = User::whereHas('role', function ($query) {
            $query->where('name', 'Teknisi');
        })->orderBy('name')->get();

        // Untuk status awal, bisa didefinisikan di sini atau langsung di view/controller store
        $initialStatus = 'Pending'; // atau 'Baru Masuk', 'Menunggu Diagnosa'

        return view('admin.service_orders.create', compact('customers', 'technicians', 'initialStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceOrderRequest $request)
    {
        $validatedData = $request->validated();
        $customerId = $validatedData['customer_id'] ?? null;

        // Jika pelanggan baru diinput
        if (empty($customerId) && !empty($validatedData['new_customer_name']) && !empty($validatedData['new_customer_email'])) {
            $customerRole = Role::where('name', 'Pelanggan')->first();
            if (!$customerRole) {
                // Handle jika role 'Pelanggan' tidak ada, mungkin redirect dengan error
                return back()->withInput()->with('error', 'Peran "Pelanggan" tidak ditemukan. Hubungi Super Admin.');
            }

            // Buat password acak sederhana untuk pelanggan baru
            $newPassword = Str::random(8); 

            $newCustomer = User::create([
                'name' => $validatedData['new_customer_name'],
                'email' => $validatedData['new_customer_email'],
                'phone_number' => $validatedData['new_customer_phone'] ?? null,
                'password' => Hash::make($newPassword), // Simpan password yang di-hash
                'role_id' => $customerRole->id,
                'email_verified_at' => now(), // Anggap langsung terverifikasi
            ]);
            $customerId = $newCustomer->id;
            // Idealnya, Anda memberitahu password ini ke pelanggan, atau kirim email.
            // Untuk KP, bisa tampilkan di pesan sukses (tapi ini tidak aman untuk produksi).
            // Misalnya: session()->flash('new_customer_password', $newPassword);
        } elseif (empty($customerId)) {
            // Jika customer_id kosong dan data pelanggan baru juga tidak lengkap
            return back()->withInput()->with('error', 'Informasi pelanggan tidak lengkap.');
        }

        // Generate Nomor Servis Otomatis (Contoh Sederhana)
        // Format: YYYYMMDD-XXXX (XXXX adalah 4 digit acak/unik harian)
        $serviceOrderNumber = date('Ymd') . '-' . strtoupper(Str::random(4));
        // Pastikan unik, bisa dengan loop cek ke db jika perlu, atau gunakan UUID

        ServiceOrder::create([
            'service_order_number' => $serviceOrderNumber,
            'customer_id' => $customerId,
            'assigned_technician_id' => $validatedData['assigned_technician_id'] ?? null,
            'device_type' => $validatedData['device_type'],
            'device_brand_model' => $validatedData['device_brand_model'] ?? null,
            'serial_number' => $validatedData['serial_number'] ?? null,
            'problem_description' => $validatedData['problem_description'],
            'accessories_received' => $validatedData['accessories_received'] ?? null,
            'status' => $validatedData['status'], // Status awal dari form
            // 'date_received' akan otomatis terisi jika pakai useCurrent() di migrasi atau biarkan default
        ]);

        $message = 'Order servis baru berhasil ditambahkan dengan Nomor Servis: ' . $serviceOrderNumber;
        // if (session()->has('new_customer_password')) {
        //     $message .= ' Password pelanggan baru: ' . session('new_customer_password');
        // }

        return redirect()->route('admin.service-orders.index')
                         ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceOrder $serviceOrder)
    {
        // Eager load relasi yang dibutuhkan untuk tampilan detail
        $serviceOrder->load([
            'customer', // Relasi ke pelanggan
            'technician', // Relasi ke teknisi yang ditugaskan
            'updates' => function ($query) { // Relasi ke riwayat update
                $query->with(['updatedBy', 'photos'])->latest(); // Setiap update memuat siapa yang update dan foto-fotonya, urut terbaru
            },
            'warranty' // Relasi ke garansi jika ada
        ]);

        // Daftar status untuk form tambah update (jika kita letakkan di sini)
        $statuses = [
            'Pending' => 'Pending',
            'Menunggu Diagnosa' => 'Menunggu Diagnosa',
            'Diagnosing' => 'Sedang Diagnosa',
            'Menunggu Persetujuan Pelanggan' => 'Menunggu Persetujuan Pelanggan',
            'Persetujuan Diterima' => 'Persetujuan Diterima',
            'Quotation Ditolak' => 'Quotation Ditolak',
            'Menunggu Sparepart' => 'Menunggu Sparepart',
            'In Progress' => 'Sedang Dikerjakan',
            'Pengujian' => 'Pengujian',
            'Completed' => 'Servis Selesai - Siap Diambil',
            'Picked Up' => 'Sudah Diambil',
            'Cancelled' => 'Dibatalkan',];

        return view('admin.service_orders.show', compact('serviceOrder', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceOrder $serviceOrder)
    {
        // Ambil user dengan peran 'Pelanggan'
        $customers = User::whereHas('role', function ($query) {
            $query->where('name', 'Pelanggan');
        })->orderBy('name')->get();

        // Ambil user dengan peran 'Teknisi'
        $technicians = User::whereHas('role', function ($query) {
            $query->where('name', 'Teknisi');
        })->orderBy('name')->get();

        // Daftar status yang bisa dipilih (sesuaikan dengan alur kerja Anda)
        $statuses = [
            'Pending' => 'Pending',
            'Menunggu Diagnosa' => 'Menunggu Diagnosa',
            'Diagnosing' => 'Sedang Diagnosa',
            'Menunggu Persetujuan Pelanggan' => 'Menunggu Persetujuan Pelanggan',
            'Persetujuan Diterima' => 'Persetujuan Diterima',
            'Quotation Ditolak' => 'Quotation Ditolak',
            'Menunggu Sparepart' => 'Menunggu Sparepart',
            'In Progress' => 'Sedang Dikerjakan',
            'Pengujian' => 'Pengujian',
            'Completed' => 'Servis Selesai - Siap Diambil',
            'Picked Up' => 'Sudah Diambil',
            'Cancelled' => 'Dibatalkan',
        ];

        $customerApprovalStatuses = [
            'Pending' => 'Pending',
            'Approved' => 'Approved',
            'Rejected' => 'Rejected',
        ];

        return view('admin.service_orders.edit', compact('serviceOrder', 'customers', 'technicians', 'statuses', 'customerApprovalStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceOrderRequest $request, ServiceOrder $serviceOrder)
    {
        $validatedData = $request->validated();

        // Logika untuk mengisi date_completed dan date_picked_up berdasarkan status
        if (isset($validatedData['status'])) {
            if ($validatedData['status'] === 'Completed' && is_null($serviceOrder->date_completed)) {
                $validatedData['date_completed'] = now();
            } elseif ($validatedData['status'] !== 'Completed' && !is_null($serviceOrder->date_completed) && $validatedData['status'] !== 'Picked Up' ) {
                // Jika status diubah dari Completed ke status lain (kecuali Picked Up), null-kan date_completed
                // Ini opsional, tergantung alur bisnis
                // $validatedData['date_completed'] = null;
            }

            if ($validatedData['status'] === 'Picked Up' && is_null($serviceOrder->date_picked_up)) {
                $validatedData['date_picked_up'] = now();
                // Jika saat Picked Up, date_completed belum terisi (misal langsung dari Pengujian ke Picked Up)
                if (is_null($serviceOrder->date_completed)) {
                    $validatedData['date_completed'] = now(); 
                }
            } elseif ($validatedData['status'] !== 'Picked Up' && !is_null($serviceOrder->date_picked_up)) {
                // Jika status diubah dari Picked Up, null-kan date_picked_up
                // $validatedData['date_picked_up'] = null;
            }
        }

        $serviceOrder->update($validatedData);

        // Di sini admin juga bisa membuat entri di service_order_updates
        // misalnya jika status berubah atau ada catatan penting saat update.
        // Contoh:
        // if ($serviceOrder->wasChanged('status')) {
        //     $serviceOrder->updates()->create([
        //         'updated_by_id' => Auth::id(),
        //         'update_type' => 'Status Diubah Admin',
        //         'notes' => 'Status diubah menjadi: ' . $serviceOrder->status,
        //         'status_from' => $serviceOrder->getOriginal('status'),
        //         'status_to' => $serviceOrder->status,
        //     ]);
        // }
        // Logika untuk membuat atau mengupdate Garansi
        // Pastikan field warranty_start_date, warranty_end_date, warranty_terms ada di $fillable model Warranty jika belum
        // dan juga divalidasi di UpdateServiceOrderRequest jika diperlukan (misal, end_date > start_date)

        // Tambahkan validasi untuk field garansi di UpdateServiceOrderRequest jika belum
        // 'warranty_start_date' => 'nullable|date',
        // 'warranty_end_date' => 'nullable|date|after_or_equal:warranty_start_date',
        // 'warranty_terms' => 'nullable|string',

        if ($request->filled('warranty_start_date') && $request->filled('warranty_end_date')) {
            $serviceOrder->warranty()->updateOrCreate(
                ['service_order_id' => $serviceOrder->id], // Kunci untuk mencari atau membuat
                [
                    'start_date' => $request->input('warranty_start_date'),
                    'end_date' => $request->input('warranty_end_date'),
                    'terms' => $request->input('warranty_terms'),
                ]
            );
        } elseif ($serviceOrder->warranty && (!$request->filled('warranty_start_date') || !$request->filled('warranty_end_date')) ) {
            // Jika salah satu atau kedua field tanggal garansi dikosongkan dan sebelumnya ada garansi, hapus garansi.
            // Ini adalah satu cara, Anda bisa juga hanya mengupdate jika semua field diisi.
            $serviceOrder->warranty->delete();
        }


        return redirect()->route('admin.service-orders.index')
                         ->with('success', 'Order servis berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceOrder $serviceOrder)
    {
        // Dengan SoftDeletes di model, ini akan mengisi kolom 'deleted_at'
        $serviceOrder->delete(); 

        // Anda tidak perlu khawatir tentang foreign key 'cascade' atau 'restrict' di sini
        // karena data tidak benar-benar dihapus dari tabel service_orders.
        // Namun, jika Anda forceDelete(), maka constraint akan berlaku.

        return redirect()->route('admin.service-orders.index')
                         ->with('success', 'Order servis berhasil dinonaktifkan (soft delete).');
    }

    public function storeUpdate(StoreServiceOrderUpdate $request, ServiceOrder $serviceOrder)
    {
        $validatedData = $request->validated();

        // 1. Buat entri ServiceOrderUpdate baru
        $serviceOrderUpdate = $serviceOrder->updates()->create([
            'updated_by_id' => Auth::id(),
            'update_type' => $validatedData['update_type'], // Dari hidden input, atau set manual
            'notes' => $validatedData['notes'],
            'status_from' => $serviceOrder->status, // Status saat ini sebelum diubah
            'status_to' => $validatedData['new_status'] ?? $serviceOrder->status, // Status baru jika ada, atau status lama
        ]);

        // 2. Handle upload foto jika ada
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photoFile) {
                $path = $photoFile->store('service_orders/' . $serviceOrder->id . '/updates', 'public');
                $serviceOrderUpdate->photos()->create([
                    'file_path' => $path,
                    'uploaded_by_id' => Auth::id(),
                    // 'caption' bisa ditambahkan jika ada inputnya di form
                ]);
            }
        }

        // 3. Update status utama di ServiceOrder jika ada perubahan
        $statusUpdated = false;
        if (!empty($validatedData['new_status']) && $serviceOrder->status !== $validatedData['new_status']) {
            $serviceOrder->status = $validatedData['new_status'];

            // Logika untuk mengisi date_completed dan date_picked_up berdasarkan status baru
            if ($serviceOrder->status === 'Completed' && is_null($serviceOrder->date_completed)) {
                $serviceOrder->date_completed = now();
            } elseif ($serviceOrder->status !== 'Completed' && !is_null($serviceOrder->date_completed) && $serviceOrder->status !== 'Picked Up') {
                // $serviceOrder->date_completed = null; // Opsional: reset jika status mundur
            }

            if ($serviceOrder->status === 'Picked Up' && is_null($serviceOrder->date_picked_up)) {
                $serviceOrder->date_picked_up = now();
                if (is_null($serviceOrder->date_completed)) { // Jika langsung dari proses ke picked up
                    $serviceOrder->date_completed = now();
                }
            } elseif ($serviceOrder->status !== 'Picked Up' && !is_null($serviceOrder->date_picked_up)) {
                // $serviceOrder->date_picked_up = null; // Opsional: reset jika status mundur
            }

            $serviceOrder->save();
            $statusUpdated = true;
        }

        // Redirect kembali ke halaman detail order servis dengan pesan sukses
        // Kita gunakan named error bag untuk validasi form update
        return redirect()->route('admin.service-orders.show', $serviceOrder->id)
                         ->with('success', 'Update progres berhasil ditambahkan!' . ($statusUpdated ? ' Status order juga telah diperbarui.' : ''));
    }
}
