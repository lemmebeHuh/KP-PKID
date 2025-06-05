<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Category::truncate(); // Hati-hati jika sudah ada relasi

        $categories = [
            // Tipe: product
            ['name' => 'Laptop', 'type' => 'product', 'description' => 'Berbagai jenis laptop untuk kebutuhan personal dan profesional.'],
            ['name' => 'Keyboard', 'type' => 'product', 'description' => 'Keyboard mekanikal, membran, wireless, dan lainnya.'],
            ['name' => 'Mouse', 'type' => 'product', 'description' => 'Mouse gaming, mouse wireless, mouse ergonomis.'],
            ['name' => 'Monitor', 'type' => 'product', 'description' => 'Monitor LED, IPS, Gaming dengan berbagai ukuran.'],
            ['name' => 'Penyimpanan Data', 'type' => 'product', 'description' => 'SSD, HDD Internal & Eksternal, Flashdisk.'],
            ['name' => 'RAM (Memory)', 'type' => 'product', 'description' => 'Modul RAM untuk PC dan Laptop.'],
            ['name' => 'Printer & Scanner', 'type' => 'product', 'description' => 'Printer Inkjet, Laser, All-in-One dan Scanner.'],
            ['name' => 'Jaringan', 'type' => 'product', 'description' => 'Router, Switch, Kabel LAN, WiFi Adapter.'],
            ['name' => 'Webcam', 'type' => 'product', 'description' => 'Webcam untuk meeting online dan streaming.'],
            ['name' => 'Aksesoris Laptop', 'type' => 'product', 'description' => 'Cooling pad, tas, pelindung layar, dll.'],
            ['name' => 'Hardware Internal PC', 'type' => 'product', 'description' => 'Processor, Motherboard, VGA, PSU, Casing.'],
            ['name' => 'Software Original', 'type' => 'product', 'description' => 'Sistem Operasi, Antivirus, Aplikasi Produktivitas.'],

            // Tipe: service
            ['name' => 'Perbaikan Hardware Laptop', 'type' => 'service', 'description' => 'Jasa perbaikan komponen fisik laptop seperti LCD, keyboard, motherboard.'],
            ['name' => 'Perbaikan Hardware PC', 'type' => 'service', 'description' => 'Jasa perbaikan komponen fisik PC rakitan dan branded.'],
            ['name' => 'Instalasi & Upgrade Software', 'type' => 'service', 'description' => 'Instalasi sistem operasi, aplikasi, driver, dan upgrade.'],
            ['name' => 'Instalasi & Upgrade Hardware', 'type' => 'service', 'description' => 'Pemasangan atau penggantian komponen hardware seperti RAM, SSD.'],
            ['name' => 'Perawatan & Optimalisasi Sistem', 'type' => 'service', 'description' => 'Pembersihan virus, optimalisasi kinerja, backup data.'],
            ['name' => 'Rakit PC Custom', 'type' => 'service', 'description' => 'Jasa perakitan PC sesuai spesifikasi keinginan pelanggan.'],
            ['name' => 'Setting Jaringan', 'type' => 'service', 'description' => 'Konfigurasi jaringan untuk rumah atau kantor kecil.'],


            // Tipe: article
            ['name' => 'Tips & Trik Komputer', 'type' => 'article', 'description' => 'Kumpulan cara praktis dan solusi masalah umum komputer.'],
            ['name' => 'Review Hardware', 'type' => 'article', 'description' => 'Ulasan mendalam mengenai hardware terbaru.'],
            ['name' => 'Panduan Software', 'type' => 'article', 'description' => 'Tutorial penggunaan aplikasi dan sistem operasi.'],
            ['name' => 'Berita Teknologi', 'type' => 'article', 'description' => 'Informasi terkini dari dunia IT dan gadget.'],
            ['name' => 'Keamanan Siber', 'type' => 'article', 'description' => 'Tips dan panduan menjaga keamanan data dan perangkat online.'],
        ];

        foreach ($categories as $categoryData) {
            // Cek jika kategori dengan nama dan tipe yang sama sudah ada
            if (!Category::where('name', $categoryData['name'])->where('type', $categoryData['type'])->exists()) {
                Category::create([
                    'name' => $categoryData['name'],
                    'slug' => Str::slug($categoryData['name'], '-'),
                    'type' => $categoryData['type'],
                    'description' => $categoryData['description'] ?? null,
                ]);
            } else {
                $this->command->info('Kategori "' . $categoryData['name'] . '" dengan tipe "' . $categoryData['type'] . '" sudah ada, dilewati.');
            }
        }
        $this->command->info('CategorySeeder selesai dijalankan.');
    }
}