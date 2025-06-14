<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Review;
use Illuminate\Http\Request; // Menggunakan Request standar untuk validasi sederhana
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderUpdate; // <-- IMPORT
use App\Models\User;
use App\Notifications\NewComplaintSubmitted;
use App\Notifications\NewReviewSubmitted;
use App\Notifications\QuotationResponded;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        $customerId = Auth::id(); // Dapatkan ID pelanggan yang sedang login

        $serviceOrders = ServiceOrder::where('customer_id', $customerId)
                            ->with('technician') // Eager load data teknisi jika perlu ditampilkan
                            ->latest('date_received') // Urutkan berdasarkan tanggal diterima terbaru
                            ->paginate(10); // Paginasi jika daftarnya panjang

        return view('pelanggan.dashboard', compact('serviceOrders'));
    }

    // Nanti kita bisa tambahkan method lain di sini, misalnya untuk melihat detail order spesifik
    // atau untuk fitur persetujuan quotation.

    public function showServiceOrderDetail(ServiceOrder $serviceOrder)
    {
        // Autorisasi: Pastikan order ini milik pelanggan yang sedang login
        if ($serviceOrder->customer_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN. Anda tidak memiliki akses ke order servis ini.');
        }

        // Eager load relasi yang dibutuhkan untuk tampilan detail
        $serviceOrder->load([
            'technician' => function ($query) {
                $query->select('id', 'name'); // Hanya ambil nama teknisi
            },
            'updates' => function ($query) {
                $query->with(['updatedBy' => function($q){ $q->select('id','name'); }, 'photos'])
                      ->select('id','service_order_id', 'updated_by_id', 'update_type', 'notes', 'status_from', 'status_to', 'created_at')
                      ->latest();
            },
            'warranty' => function($query){
                $query->select('id', 'service_order_id', 'start_date', 'end_date', 'terms');
            }
            // Relasi 'customer' tidak perlu di-load lagi karena kita sudah punya $serviceOrder->customer_id
            // dan $serviceOrder->customer akan otomatis ter-load jika diakses di view karena $user adalah customer.
        ]);

        $existingReview = Review::where('service_order_id', $serviceOrder->id)
                                ->where('customer_id', Auth::id())
                                ->first();

        // Kirim juga daftar status jika diperlukan untuk tampilan, tapi untuk halaman ini mungkin tidak perlu
        // $statuses = [ ... ]; 

        return view('pelanggan.service_orders.show', compact('serviceOrder', 'existingReview'));
    }

    public function respondToQuotation(Request $request, ServiceOrder $serviceOrder)
    {
        // Autorisasi: Pastikan order ini milik pelanggan yang sedang login
        if ($serviceOrder->customer_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        // Pastikan order dalam status yang tepat untuk direspon
        if ($serviceOrder->status !== 'Menunggu Persetujuan Pelanggan' || 
            !in_array($serviceOrder->customer_approval_status, ['Pending', null])) {
            return redirect()->route('pelanggan.service-orders.show', $serviceOrder->id)
                             ->with('error', 'Saat ini Anda tidak bisa merespon penawaran untuk order ini.');
        }

        $validated = $request->validate([ // Hasil disimpan di $validated
            'decision' => 'required|string|in:Approved,Rejected',
        ]);

        $decision = $validated['decision']; // <-- PERBAIKAN DI SINI

        // Update status persetujuan pelanggan
        $serviceOrder->customer_approval_status = $decision;

        $updateNotes = '';
        if ($decision === 'Approved') {
            $serviceOrder->status = 'Persetujuan Diterima'; // Atau 'Siap Dikerjakan' / 'Menunggu Sparepart'
            $updateNotes = 'Pelanggan menyetujui penawaran biaya/pekerjaan.';
        } elseif ($decision === 'Rejected') {
            $serviceOrder->status = 'Quotation Ditolak'; // Atau 'Diskusi Lebih Lanjut'
            $updateNotes = 'Pelanggan menolak penawaran biaya/pekerjaan. Mohon hubungi Pangkalan Komputer untuk diskusi lebih lanjut.';
        }
        
        $serviceOrder->save();

        // Buat entri di ServiceOrderUpdate
        $serviceOrder->updates()->create([
            'updated_by_id' => Auth::id(), // Pelanggan yang melakukan aksi
            'update_type' => 'Respon Pelanggan',
            'notes' => $updateNotes,
            'status_from' => 'Menunggu Persetujuan Pelanggan', // Status sebelum aksi ini
            'status_to' => $serviceOrder->status, // Status baru setelah aksi ini
        ]);
        // Kirim Notifikasi ke semua Admin
        $admins = User::whereHas('role', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new QuotationResponded($serviceOrder));
        }

        return redirect()->route('pelanggan.service-orders.show', $serviceOrder->id)
                         ->with('success', 'Respon Anda telah berhasil disimpan. Status order telah diperbarui.');
    }

    public function downloadServiceOrderPdf(ServiceOrder $serviceOrder)
    {
        // Autorisasi: Pastikan order ini milik pelanggan yang sedang login
        if ($serviceOrder->customer_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        // Eager load relasi yang dibutuhkan untuk PDF
        $serviceOrder->load([
            'customer', 
            'technician' => function ($query) { $query->select('id', 'name'); },
            'updates' => function ($query) {
                $query->with(['updatedBy' => function($q){ $q->select('id','name'); }, 'photos'])
                      ->select('id','service_order_id', 'updated_by_id', 'update_type', 'notes', 'status_from', 'status_to', 'created_at')
                      ->latest();
            },
            'warranty' => function($query){
                $query->select('id', 'service_order_id', 'start_date', 'end_date', 'terms');
            }
        ]);

        // --- LOGIKA UNTUK LOGO BASE64 ---
        $logoPath = public_path('images/pkid-logo.png'); // Pastikan path dan nama file 100% benar
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            try {
                $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                $logoData = file_get_contents($logoPath);
                $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
            } catch (\Exception $e) {
                // Jika gagal membaca file, logo akan tetap null
                // dan tidak akan ditampilkan di PDF.
                // Ini mencegah error 500 jika file rusak atau tidak bisa dibaca.
            }
        }

        // Data yang akan dikirim ke view PDF
        $data = [
        'serviceOrder' => $serviceOrder,
        'title' => 'Bukti Servis - ' . $serviceOrder->service_order_number,
        'logoBase64' => $logoBase64,
        'company_name' => 'Pangkalan Komputer ID',
        'company_address' => 'Jl. Sersan Sodik No.57 RT03, RW.2, Gg. Kelinci VI, Kec. Sukasari, Kota Bandung, Jawa Barat', // Ganti dengan alamat asli
        'company_phone' => '0895-4157-18458',
    ];

    $fileName = 'bukti_servis_' . str_replace('-', '_', strtolower($serviceOrder->service_order_number)) . '.pdf';
    $pdf = Pdf::loadView('pelanggan.service_orders.pdf', $data);
    return $pdf->download($fileName);
    }

    public function storeReview(Request $request, ServiceOrder $serviceOrder)
    {
        // Autorisasi: Pastikan order ini milik pelanggan yang sedang login
        if ($serviceOrder->customer_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        // Validasi: Hanya boleh review jika status Completed atau Picked Up
        if (!in_array($serviceOrder->status, ['Completed', 'Picked Up'])) {
            return redirect()->route('pelanggan.service-orders.show', $serviceOrder->id)
                             ->with('error', 'Anda hanya bisa memberi ulasan untuk servis yang sudah selesai.');
        }

        // Validasi: Pastikan pelanggan belum pernah mereview order ini
        $existingReview = Review::where('service_order_id', $serviceOrder->id)->first();
        if ($existingReview) {
            return redirect()->route('pelanggan.service-orders.show', $serviceOrder->id)
                             ->with('error', 'Anda sudah pernah memberikan ulasan untuk order servis ini.');
        }

        // Validasi input dari form
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // BUAT REVIEW HANYA SATU KALI dan simpan hasilnya ke variabel
        $newReview = Review::create([
            'service_order_id' => $serviceOrder->id,
            'customer_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true, // Langsung approve
        ]);

        // Kirim Notifikasi ke semua Admin menggunakan $newReview yang baru dibuat
        $admins = User::whereHas('role', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewReviewSubmitted($newReview));
        }

        return redirect()->route('pelanggan.service-orders.show', $serviceOrder->id)
                         ->with('success', 'Terima kasih! Ulasan Anda telah berhasil disimpan.');
    }

    public function createComplaint(ServiceOrder $serviceOrder)
    {
        // Autorisasi: Pastikan order ini milik pelanggan yang sedang login
        if ($serviceOrder->customer_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        // Validasi: Hanya boleh komplain jika status Completed atau Picked Up (sesuai kebutuhan)
        if (!in_array($serviceOrder->status, ['Completed', 'Picked Up'])) {
            return redirect()->route('pelanggan.service-orders.show', $serviceOrder->id)
                             ->with('error', 'Anda hanya bisa mengajukan komplain untuk servis yang sudah selesai.');
        }

        return view('pelanggan.complaints.create', compact('serviceOrder'));
    }

    public function storeComplaint(Request $request, ServiceOrder $serviceOrder)
    {
        // Autorisasi: Pastikan order ini milik pelanggan yang sedang login
        // Otorisasi: Pastikan order ini milik pelanggan yang sedang login
        if ($serviceOrder->customer_id !== Auth::id()) {
            abort(403);
        }

        // Validasi input dari form
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
        ]);

        // BUAT KOMPLAIN HANYA SATU KALI
        // dan simpan hasilnya ke variabel $newComplaint
        $newComplaint = Complaint::create([
            'service_order_id' => $serviceOrder->id,
            'customer_id' => Auth::id(),
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'status' => 'Open', // Status awal komplain
        ]);

        // Kirim Notifikasi ke semua Admin menggunakan $newComplaint yang baru dibuat
        $admins = User::whereHas('role', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewComplaintSubmitted($newComplaint));
        }

        return redirect()->route('pelanggan.service-orders.show', $serviceOrder->id)
                         ->with('success', 'Komplain Anda telah berhasil dikirim. Kami akan segera menindaklanjutinya.');
    }

    public function listComplaints()
    {
        $complaints = Complaint::where('customer_id', Auth::id())
                            ->with('serviceOrder') // Eager load service order terkait (jika ada)
                            ->latest() // Urutkan dari terbaru
                            ->paginate(10);

        return view('pelanggan.complaints.index', compact('complaints'));
    }
}