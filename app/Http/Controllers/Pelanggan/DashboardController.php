<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Review;
use Illuminate\Http\Request; // Menggunakan Request standar untuk validasi sederhana
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderUpdate; // <-- IMPORT
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

        // Data yang akan dikirim ke view PDF
        $data = [
            'serviceOrder' => $serviceOrder,
            'title' => 'Bukti Servis - ' . $serviceOrder->service_order_number,
            // Anda bisa tambahkan data lain jika perlu, misal info perusahaan
            'company_name' => 'Pangkalan Komputer ID',
            'company_address' => 'Jl. Sersan Sodik No.57 RT03, RW.2, Gg. Kelinci VI, Kec. Sukasari, Kota Bandung, Jawa Barat', // Ganti dengan alamat asli
            'company_phone' => '0895-4157-18458', // Ganti dengan no telp asli
        ];

        // Buat nama file PDF
        $fileName = 'bukti_servis_' . str_replace('-', '_', strtolower($serviceOrder->service_order_number)) . '.pdf';

        // Load view dan data, lalu generate PDF
        // Kita akan buat view 'pelanggan.service_orders.pdf' nanti
        $pdf = Pdf::loadView('pelanggan.service_orders.pdf', $data);

        // Kirim PDF sebagai download ke browser
        return $pdf->download($fileName);

        // Atau jika ingin ditampilkan di browser dulu (inline):
        // return $pdf->stream($fileName);
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
        $existingReview = Review::where('service_order_id', $serviceOrder->id)
                                ->where('customer_id', Auth::id())
                                ->first();
        if ($existingReview) {
            return redirect()->route('pelanggan.service-orders.show', $serviceOrder->id)
                             ->with('error', 'Anda sudah pernah memberikan ulasan untuk order servis ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'service_order_id' => $serviceOrder->id,
            'customer_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true, // Langsung approve, atau set false jika perlu moderasi Admin
        ]);

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
        if ($serviceOrder->customer_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        // Validasi: Hanya boleh komplain jika status Completed atau Picked Up
        if (!in_array($serviceOrder->status, ['Completed', 'Picked Up'])) {
             return redirect()->route('pelanggan.service-orders.show', $serviceOrder->id)
                             ->with('error', 'Anda hanya bisa mengajukan komplain untuk servis yang sudah selesai.');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000', // Batasi panjang deskripsi
        ]);

        Complaint::create([
            'service_order_id' => $serviceOrder->id,
            'customer_id' => Auth::id(),
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'status' => 'Open', // Status awal komplain
        ]);

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