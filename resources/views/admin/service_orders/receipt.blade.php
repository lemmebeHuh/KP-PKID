<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Servis - {{ $serviceOrder->service_order_number }}</title>
    <style>
        /* Style untuk tampilan di layar & print */
        body { font-family: 'Courier New', Courier, monospace; background-color: #f4f4f4; }
        .receipt {
            width: 300px; /* Lebar struk thermal umum */
            margin: 20px auto;
            padding: 15px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .header { text-align: center; border-bottom: 1px dashed #333; padding-bottom: 10px; margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; }
        .header p { margin: 2px 0; font-size: 10px; }
        .info-section { font-size: 11px; line-height: 1.5; }
        .info-section table { width: 100%; }
        .info-section td:first-child { width: 100px; }
        .qr-code { text-align: center; margin-top: 15px; }
        .footer { text-align: center; font-size: 10px; border-top: 1px dashed #333; padding-top: 10px; margin-top: 15px; }
        .print-button {
            display: block;
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            background: #004aad; /* Warna primer Anda */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        /* Style khusus saat halaman akan di-print */
        @media print {
            body { background-color: #fff; margin: 0; }
            .receipt { width: 100%; margin: 0; padding: 0; border: none; box-shadow: none; }
            .print-button { display: none; } /* Sembunyikan tombol print saat dicetak */
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <img src="{{ asset('images/logoP.png') }}" alt="Logo" style="max-height: 40px; margin-bottom: 5px;">
            <h2>Pangkalan Komputer ID</h2>
            <p>Jl. Sersan Sodik No.57, Bandung</p>
            <p>0812-7364-7463</p>
        </div>

        <div class="info-section">
            <h3 style="text-align: center">TANDA TERIMA SERVIS</h3>
    <table>
        <tr>
            <td>No. Servis</td>
            <td>: <strong>{{ $serviceOrder->service_order_number }}</strong></td>
        </tr>
        <tr>
            <td>Tgl Diterima</td>
            <td>: {{ $serviceOrder->date_received ? $serviceOrder->date_received->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '-' }}</td>
        </tr>
        {{-- BARIS BARU: Estimasi Selesai --}}
        <tr>
            <td>Est. Selesai</td>
            <td>: {{ $serviceOrder->estimated_completion_date ? \Carbon\Carbon::parse($serviceOrder->estimated_completion_date)->translatedFormat('d F Y') : 'Akan diinfokan' }}</td>
        </tr>
        {{-- BARIS BARU: Waktu Cetak Struk --}}
        
        <tr>
            <td>Pelanggan</td>
            <td>: {{ $serviceOrder->customer->name ?? 'N/A' }}</td>
        </tr>
            <tr>
            <td>Perangkat</td>
            <td>: {{ $serviceOrder->device_type }}</td>
        </tr>
        <tr>
            <td>Keluhan</td>
            <td>: {{ $serviceOrder->problem_description }}</td>
        </tr>
    </table>
        </div>

        <div class="qr-code">
            <p style="font-size: 10px; margin-bottom: 5px;">Lacak Progres Servis Anda:</p>
            @php
                $trackingUrl = route('tracking.result', ['service_order_number' => $serviceOrder->service_order_number]);
            @endphp
            {!! QrCode::size(120)->generate($trackingUrl) !!}
            <p style="font-size: 9px; margin-top: 5px;">Scan QR Code di atas</p>
        </div>
        <div style="font-size: 8px;text-align: center">
            <tr>
                <td>Waktu Cetak</td>
                <td>: {{ now()->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }}</td>
            </tr>
        </div>

        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda.</p>
            <p>Harap simpan bukti ini untuk pengambilan barang.</p>
        </div>
    </div>

    <div style="width: 300px; margin: auto;">
         <button onclick="window.print();" class="print-button">Cetak Struk Ini</button>
    </div>

</body>
</html>