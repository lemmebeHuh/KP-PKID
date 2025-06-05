<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Bukti Servis' }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; line-height: 1.6; font-size: 11px; color: #333; }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 2px 0; font-size: 10px; }
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .content-table th, .content-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .content-table th { background-color: #f2f2f2; font-size: 11px; }
        .section-title { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px;}
        .text-sm { font-size: 10px; }
        .text-gray-600 { color: #555; }
        .whitespace-pre-wrap { white-space: pre-wrap; }
        .image-gallery img { max-width: 100px; max-height: 100px; margin: 5px; border: 1px solid #ddd; }
        .footer { text-align: center; font-size: 9px; color: #777; margin-top: 30px; border-top: 1px solid #eee; padding-top:10px; }
        table { page-break-inside: auto } /* Coba hindari table pecah antar halaman */
        tr    { page-break-inside:avoid; page-break-after:auto }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('images/logo_pangkalan_komputer.png') }}" alt="Logo" style="max-height: 60px; margin-bottom:10px;">
            <h1>{{ $company_name ?? 'Pangkalan Komputer ID' }}</h1>
            <p>{{ $company_address ?? 'Alamat Perusahaan' }}</p>
            <p>Telp: {{ $company_phone ?? 'Nomor Telepon' }}</p>
            <h2 style="font-size: 16px; margin-top: 15px; margin-bottom:5px;">BUKTI LAYANAN SERVIS</h2>
            <hr>
        </div>

        <table class="content-table">
            <tr>
                <th style="width:30%;">No. Servis</th><td>{{ $serviceOrder->service_order_number }}</td>
                <th style="width:30%;">Status Terkini</th><td>{{ $serviceOrder->status }}</td>
            </tr>
            <tr>
                <th>Pelanggan</th><td>{{ $serviceOrder->customer ? $serviceOrder->customer->name : 'N/A' }}</td>
                <th>Teknisi</th><td>{{ $serviceOrder->technician ? $serviceOrder->technician->name : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Tgl Diterima</th><td>{{ $serviceOrder->date_received ? $serviceOrder->date_received->format('d M Y, H:i') : '-' }}</td>
                <th>Tgl Selesai</th><td>{{ $serviceOrder->date_completed ? $serviceOrder->date_completed->format('d M Y, H:i') : '-' }}</td>
            </tr>
            @if($serviceOrder->date_picked_up)
            <tr>
                <th>Tgl Diambil</th><td colspan="3">{{ $serviceOrder->date_picked_up ? $serviceOrder->date_picked_up->format('d M Y, H:i') : '-' }}</td>
            </tr>
            @endif
        </table>

        <div class="section-title">Detail Perangkat & Keluhan</div>
        <table class="content-table">
            <tr><th style="width:30%;">Jenis Perangkat</th><td>{{ $serviceOrder->device_type }}</td></tr>
            <tr><th>Merk/Model</th><td>{{ $serviceOrder->device_brand_model ?: '-' }}</td></tr>
            <tr><th>No. Seri</th><td>{{ $serviceOrder->serial_number ?: '-' }}</td></tr>
            <tr><th>Kelengkapan Diterima</th><td>{{ $serviceOrder->accessories_received ?: '-' }}</td></tr>
            <tr><th>Keluhan Awal</th><td class="whitespace-pre-wrap">{{ $serviceOrder->problem_description }}</td></tr>
        </table>

        @if($serviceOrder->quotation_details)
        <div class="section-title">Diagnosa & Penawaran Biaya</div>
        <p class="whitespace-pre-wrap text-sm">{{ $serviceOrder->quotation_details }}</p>
        <p class="text-sm"><strong>Status Persetujuan:</strong> {{ $serviceOrder->customer_approval_status ?: 'Belum Ada Respon' }}</p>
        @endif
        @if($serviceOrder->final_cost)
        <p class="text-sm"><strong>Biaya Final:</strong> Rp {{ number_format($serviceOrder->final_cost, 0, ',', '.') }}</p>
        @endif

        <div class="section-title">Riwayat Progres Perbaikan</div>
        @if($serviceOrder->updates && $serviceOrder->updates->count() > 0)
            @foreach($serviceOrder->updates as $update)
                <div style="border: 1px solid #eee; padding: 8px; margin-bottom: 10px; page-break-inside: avoid;">
                    <p class="text-sm"><strong>{{ $update->update_type ?: 'Update' }}</strong> - {{ $update->created_at->format('d M Y, H:i') }} (Oleh: {{ $update->updatedBy ? $update->updatedBy->name : 'Sistem' }})</p>
                    @if($update->status_from && $update->status_to && $update->status_from !== $update->status_to)
                        <p class="text-sm" style="color: blue;">Status: {{ $update->status_from }} &rarr; {{ $update->status_to }}</p>
                    @endif
                    <p class="whitespace-pre-wrap text-sm">{{ $update->notes }}</p>
                    {{-- DomPDF mungkin punya keterbatasan merender banyak gambar atau CSS kompleks.
                         Untuk foto di PDF, Anda bisa tampilkan beberapa foto kunci saja atau link ke galeri jika terlalu banyak.
                         Atau coba tampilkan path-nya saja jika render gambar memberatkan.
                         Untuk contoh ini, kita coba tampilkan beberapa.
                    --}}
                    @if($update->photos && $update->photos->count() > 0)
                        <p class="text-sm"><strong>Foto Bukti:</strong></p>
                        <div class="image-gallery">
                        @foreach($update->photos->take(2) as $photo) {{-- Batasi jumlah foto di PDF --}}
                            {{-- Untuk DomPDF, path absolut ke gambar di storage lebih baik, atau pastikan symlink bekerja & diakses dengan benar --}}
                            {{-- Coba dengan path absolut jika asset() tidak bekerja baik di PDF --}}
                            {{-- <img src="{{ storage_path('app/public/' . $photo->file_path) }}" alt="Foto"> --}}
                            <p class="text-sm">- {{ $photo->file_path }}</p> {{-- Paling aman tampilkan path --}}
                        @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <p class="text-sm">Belum ada update progres.</p>
        @endif

        @if($serviceOrder->warranty)
        <div class="section-title">Informasi Garansi</div>
        <p class="text-sm"><strong>Masa Berlaku:</strong> {{ \Carbon\Carbon::parse($serviceOrder->warranty->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($serviceOrder->warranty->end_date)->format('d M Y') }}</p>
        @if($serviceOrder->warranty->terms)
        <p class="text-sm"><strong>Syarat & Ketentuan:</strong></p>
        <p class="whitespace-pre-wrap text-sm">{{ $serviceOrder->warranty->terms }}</p>
        @endif
        @endif

        <div class="footer">
            Dokumen ini dicetak otomatis oleh sistem Pangkalan Komputer ID pada {{ now()->translatedFormat('d F Y H:i:s') }}.
        </div>
    </div>
</body>
</html>