<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Bukti Servis' }}</title>
    <style>
        @page { margin: 25px; }
        body { 
            font-family: 'Helvetica', 'DejaVu Sans', sans-serif; 
            font-size: 10px; 
            color: #333;
        }
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header-table td {
            vertical-align: top;
        }
        .company-logo {
            max-height: 50px;
        }
        .company-details {
            text-align: right;
        }
        .company-details h1 {
            margin: 0;
            font-size: 16px;
            color: #000;
        }
        .document-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            vertical-align: top;
            padding: 5px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            background-color: #f2f2f2;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }
        .content-table th, .content-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        .content-table th {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .whitespace-pre-wrap {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .footer {
            position: fixed;
            bottom: -10px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td>
                <img src="{{ public_path('images/pkid-logo.png') }}" alt="Logo" style="max-height: 60px; margin-bottom:10px;">
            </td>
            <td class="company-details">
                <h1>{{ $company_name ?? 'Pangkalan Komputer ID' }}</h1>
                <p>{{ $company_address ?? 'Alamat Perusahaan' }}</p>
                <p>Telp: {{ $company_phone ?? 'Nomor Telepon' }}</p>
            </td>
        </tr>
    </table>

    <div class="document-title">BUKTI SERVIS</div>

    {{-- INFORMASI ORDER & PELANGGAN --}}
    <div class="section">
        <table class="info-table">
            <tr>
                <td style="width: 50%;">
                    <strong>No. Servis:</strong> {{ $serviceOrder->service_order_number }}<br>
                    <strong>Tanggal Diterima:</strong> {{ $serviceOrder->date_received ? $serviceOrder->date_received->format('d M Y') : '-' }}<br>
                    <strong>Tanggal Selesai:</strong> {{ $serviceOrder->date_completed ? $serviceOrder->date_completed->format('d M Y') : '-' }}<br>
                    <strong>Status Terkini:</strong> {{ $serviceOrder->status }}
                </td>
                <td style="width: 50%;">
                    <strong>Pelanggan:</strong> {{ $serviceOrder->customer ? $serviceOrder->customer->name : 'N/A' }}<br>
                    <strong>Email:</strong> {{ $serviceOrder->customer ? $serviceOrder->customer->email : 'N/A' }}<br>
                    <strong>No. HP:</strong> {{ $serviceOrder->customer && $serviceOrder->customer->phone_number ? $serviceOrder->customer->phone_number : '-' }}
                </td>
            </tr>
        </table>
    </div>

    {{-- DETAIL PERANGKAT & KELUHAN --}}
    <div class="section">
        <div class="section-title">Detail Perangkat & Keluhan</div>
        <table class="content-table">
            <tr><th style="width: 30%;">Jenis Perangkat</th><td>{{ $serviceOrder->device_type }}</td></tr>
            <tr><th>Merk/Model</th><td>{{ $serviceOrder->device_brand_model ?: '-' }}</td></tr>
            <tr><th>Kelengkapan Diterima</th><td>{{ $serviceOrder->accessories_received ?: '-' }}</td></tr>
            <tr><th>Keluhan Awal</th><td class="whitespace-pre-wrap">{{ $serviceOrder->problem_description }}</td></tr>
        </table>
    </div>

    {{-- RINGKASAN PEKERJAAN & BIAYA --}}
    <div class="section">
        <div class="section-title">Ringkasan Pekerjaan & Biaya</div>
        <table class="content-table">
            <tr>
                <th style="width: 30%;">Hasil Diagnosa & Pekerjaan</th>
                <td class="whitespace-pre-wrap">{{ $serviceOrder->quotation_details ?: 'Tidak ada catatan diagnosa detail.' }}</td>
            </tr>
             <tr>
                <th>Biaya Final</th>
                <td><strong>{{ $serviceOrder->final_cost ? 'Rp ' . number_format($serviceOrder->final_cost, 0, ',', '.') : 'Belum ditentukan' }}</strong></td>
            </tr>
        </table>
    </div>

    {{-- INFORMASI GARANSI --}}
    @if($serviceOrder->warranty)
    <div class="section">
        <div class="section-title">Informasi Garansi</div>
         <table class="content-table">
            <tr><th style="width: 30%;">Masa Berlaku</th><td>{{ \Carbon\Carbon::parse($serviceOrder->warranty->start_date)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($serviceOrder->warranty->end_date)->translatedFormat('d F Y') }}</td></tr>
            <tr><th>Syarat & Ketentuan</th><td class="whitespace-pre-wrap">{{ $serviceOrder->warranty->terms ?: '-' }}</td></tr>
        </table>
    </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        Terima kasih telah mempercayakan layanan Anda kepada Pangkalan Komputer ID.
        <br>
        Dokumen ini dicetak otomatis oleh sistem pada {{ now()->translatedFormat('d F Y H:i:s') }}.
    </div>
</body>
</html>