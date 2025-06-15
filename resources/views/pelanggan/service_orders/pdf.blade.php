<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Bukti Servis' }}</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica', sans-serif; 
            font-size: 11px; 
            color: #333;
            margin: 0;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            font-size: 12px;
            line-height: 18px;
        }
        table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        table td { padding: 5px; vertical-align: top; }
        table tr.top table td { padding-bottom: 20px; }
        table tr.top table td.title { font-size: 30px; line-height: 30px; color: #333; }
        table tr.information table td { padding-bottom: 30px; }
        table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        table tr.details td { padding-bottom: 20px; }
        table tr.item td { border-bottom: 1px solid #eee; }
        table tr.item.last td { border-bottom: none; }
        table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .company-logo { max-width: 150px; max-height: 60px; }
        .section-title { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; color: #004aad; }
        .footer { font-size: 9px; text-align: center; color: #777; margin-top: 30px; }
        .whitespace-pre-wrap { white-space: pre-wrap; word-wrap: break-word; }
    </style>
</head>
<body>
    <div class="invoice-box">
        {{-- BAGIAN HEADER --}}
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ public_path('images/logoP.png') }}" alt="">
                            </td>
                            <td class="text-right">
                                <strong style="font-size: 20px;">BUKTI SERVIS</strong><br>
                                No. Servis: {{ $serviceOrder->service_order_number }}<br>
                                Dibuat: {{ now()->translatedFormat('d F Y') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong>Ditujukan Kepada:</strong><br>
                                {{ $serviceOrder->customer?->name ?? 'N/A' }}<br>
                                {{ $serviceOrder->customer?->email ?? '' }}<br>
                                {{ $serviceOrder->customer?->phone_number ?? '' }}
                            </td>
                            <td class="text-right">
                                <strong>Pangkalan Komputer ID</strong><br>
                                {{ $company_address ?? 'Alamat Perusahaan Anda' }}<br>
                                {{ $company_phone ?? 'Nomor Telepon Anda' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        {{-- DETAIL PERANGKAT & KELUHAN --}}
        <div class="section-title">Detail Perangkat</div>
        <table cellpadding="0" cellspacing="0">
            <tr class="heading">
                <td>Item</td>
                <td>Keterangan</td>
            </tr>
            <tr class="item">
                <td>Jenis Perangkat</td>
                <td>{{ $serviceOrder->device_type }}</td>
            </tr>
            <tr class="item">
                <td>Merk / Model</td>
                <td>{{ $serviceOrder->device_brand_model ?: '-' }}</td>
            </tr>
             <tr class="item">
                <td>Kelengkapan Diterima</td>
                <td>{{ $serviceOrder->accessories_received ?: '-' }}</td>
            </tr>
            <tr class="details">
                <td>Keluhan Awal</td>
                <td class="whitespace-pre-wrap">{{ $serviceOrder->problem_description }}</td>
            </tr>
        </table>

        {{-- RINGKASAN PEKERJAAN & BIAYA --}}
        <div class="section-title">Ringkasan Pekerjaan</div>
         <table cellpadding="0" cellspacing="0">
            <tr class="heading">
                <td>Deskripsi Pekerjaan</td>
                <td class="text-right">Biaya</td>
            </tr>
            <tr class="item">
                <td class="whitespace-pre-wrap">
                    <strong>Hasil Diagnosa & Tindakan Perbaikan:</strong><br>
                    {{ $serviceOrder->quotation_details ?: 'Tidak ada catatan diagnosa atau pekerjaan detail.' }}
                </td>
                <td class="text-right">{{ $serviceOrder->final_cost ? 'Rp ' . number_format($serviceOrder->final_cost, 0, ',', '.') : 'Rp 0' }}</td>
            </tr>
            <tr class="total">
                <td class="text-right"><strong>Total Biaya Final:</strong></td>
                <td class="text-right"><strong>{{ $serviceOrder->final_cost ? 'Rp ' . number_format($serviceOrder->final_cost, 0, ',', '.') : 'Rp 0' }}</strong></td>
            </tr>
        </table>

        {{-- INFORMASI GARANSI --}}
        @if($serviceOrder->warranty)
        <div class="section-title">Informasi Garansi</div>
        <div style="font-size: 11px;">
            <p><strong>Masa Berlaku:</strong> {{ \Carbon\Carbon::parse($serviceOrder->warranty->start_date)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($serviceOrder->warranty->end_date)->translatedFormat('d F Y') }}</p>
            @if($serviceOrder->warranty->terms)
                <p style="margin-top: 5px;"><strong>Syarat & Ketentuan:</strong></p>
                <div class="whitespace-pre-wrap">{{ $serviceOrder->warranty->terms }}</div>
            @endif
        </div>
        @endif
        
        <div class="footer">
            Terima kasih telah mempercayakan layanan Anda kepada Pangkalan Komputer ID.
        </div>
    </div>
</body>
</html>