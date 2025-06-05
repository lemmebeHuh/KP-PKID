<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service; // Model untuk Jasa Servis
use App\Models\Category; // Untuk mengambil ID kategori
use Illuminate\Support\Str;  // Untuk slug

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Service::truncate(); // Hati-hati! Hanya jika ingin mengosongkan tabel dulu.

        $getCategoryId = function ($name) {
            $category = Category::where('name', $name)->where('type', 'service')->first();
            return $category ? $category->id : null;
        };

        $services = [
            [
                'name' => 'Install Ulang Windows 10/11 Pro + Aplikasi Dasar',
                'category_name' => 'Instalasi & Upgrade Software',
                'description' => 'Jasa instalasi ulang sistem operasi Windows 10 atau 11 Professional original (lisensi dari pelanggan atau beli terpisah) beserta instalasi aplikasi standar perkantoran dan driver.',
                'estimated_price' => 150000,
                'estimated_duration' => '1-2 Jam',
            ],
            [
                'name' => 'Ganti Keyboard Laptop (Semua Merek)',
                'category_name' => 'Perbaikan Hardware Laptop',
                'description' => 'Penggantian keyboard laptop yang rusak, error, atau tombol tidak berfungsi. Harga belum termasuk keyboard pengganti.',
                'estimated_price' => 200000, // Bisa juga null jika harga tergantung tipe keyboard
                'estimated_duration' => '1-3 Jam',
            ],
            [
                'name' => 'Pembersihan Virus, Malware, & Optimalisasi Kinerja',
                'category_name' => 'Perawatan & Optimalisasi Sistem',
                'description' => 'Membersihkan komputer atau laptop dari virus, malware, adware, serta melakukan tuning untuk meningkatkan kecepatan dan responsivitas.',
                'estimated_price' => 125000,
                'estimated_duration' => '1-2 Jam',
            ],
            [
                'name' => 'Jasa Upgrade RAM Laptop/PC (Tidak Termasuk RAM)',
                'category_name' => 'Instalasi & Upgrade Hardware',
                'description' => 'Jasa pemasangan atau penambahan modul RAM pada laptop atau PC. Harga hanya untuk jasa pemasangan.',
                'estimated_price' => 75000,
                'estimated_duration' => '30 Menit',
            ],
            [
                'name' => 'Rakit PC Gaming Custom (Hanya Jasa Rakit)',
                'category_name' => 'Rakit PC Custom',
                'description' => 'Jasa perakitan PC gaming atau workstation sesuai dengan komponen yang telah disediakan oleh pelanggan. Termasuk cable management dasar.',
                'estimated_price' => 350000,
                'estimated_duration' => '2-4 Jam',
            ],
            [
                'name' => 'Servis Motherboard Laptop (Mati Total / No Display)',
                'category_name' => 'Perbaikan Hardware Laptop',
                'description' => 'Diagnosa dan upaya perbaikan motherboard laptop yang mengalami mati total, tidak ada tampilan, atau masalah chip lainnya. Biaya akhir tergantung tingkat kerusakan.',
                'estimated_price' => null, // Harga akan ditentukan setelah diagnosa
                'estimated_duration' => '2-7 Hari Kerja',
            ],
            [
                'name' => 'Penggantian Thermal Paste Processor & VGA',
                'category_name' => 'Perawatan & Optimalisasi Sistem',
                'description' => 'Membersihkan heatsink dan mengganti thermal paste pada processor dan kartu grafis untuk menjaga suhu tetap optimal.',
                'estimated_price' => 100000,
                'estimated_duration' => '1 Jam',
            ],
            [
                'name' => 'Backup & Restore Data (Hingga 500GB)',
                'category_name' => 'Perawatan & Optimalisasi Sistem',
                'description' => 'Jasa backup data penting Anda ke media penyimpanan lain atau restore data dari backup yang sudah ada. Untuk kapasitas lebih dari 500GB, harga menyesuaikan.',
                'estimated_price' => 200000,
                'estimated_duration' => 'Tergantung Jumlah Data',
            ],
            [
                'name' => 'Diagnosa Kerusakan Detail Komputer/Laptop',
                'category_name' => 'Perbaikan Hardware PC', // Bisa juga untuk laptop
                'description' => 'Pengecekan menyeluruh untuk menemukan sumber masalah pada komputer atau laptop Anda. Biaya diagnosa akan dikurangi dari total biaya perbaikan jika servis dilanjutkan.',
                'estimated_price' => 75000,
                'estimated_duration' => '30-90 Menit',
            ],
            [
                'name' => 'Setting Jaringan Dasar Rumah/Kantor Kecil (Router & File Sharing)',
                'category_name' => 'Setting Jaringan',
                'description' => 'Jasa konfigurasi router internet, WiFi, dan pengaturan file/printer sharing sederhana untuk kebutuhan rumah atau kantor kecil (maksimal 5 perangkat).',
                'estimated_price' => 250000,
                'estimated_duration' => '1-3 Jam',
            ],
        ];

        foreach ($services as $serviceData) {
            if (Service::where('name', $serviceData['name'])->exists()) {
                $this->command->info('Jasa Servis "' . $serviceData['name'] . '" sudah ada, dilewati.');
                continue;
            }

            Service::create([
                'name' => $serviceData['name'],
                'slug' => Str::slug($serviceData['name'], '-'),
                'category_id' => $getCategoryId($serviceData['category_name']),
                'description' => $serviceData['description'],
                'estimated_price' => $serviceData['estimated_price'],
                'estimated_duration' => $serviceData['estimated_duration'],
            ]);
        }
        $this->command->info('ServiceSeeder selesai dijalankan.');
    }
}