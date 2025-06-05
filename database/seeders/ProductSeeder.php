<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category; // Import Category model
use Illuminate\Support\Str; // Import Str untuk slug

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data produk lama jika ada (opsional, hati-hati jika sudah ada data penting)
        // Product::truncate(); // Ini akan menghapus semua produk!

        // Fungsi helper untuk mendapatkan category_id berdasarkan nama
        // Pastikan kategori-kategori ini sudah ada di tabel 'categories' Anda dengan tipe 'product'
        $getCategoryId = function ($name) {
            $category = Category::where('name', $name)->where('type', 'product')->first();
            return $category ? $category->id : null; // Kembalikan null jika tidak ditemukan agar bisa ditangani
        };

        $products = [
            [
                'name' => 'Laptop Gaming Nitro 15',
                'category_name' => 'Laptop', // Nama kategori yang akan dicari ID-nya
                'description' => 'Laptop gaming berperforma tinggi dengan prosesor Intel Core i7 generasi terbaru dan kartu grafis NVIDIA RTX 4060. Layar 15.6 inch Full HD 144Hz.',
                'price' => 15750000,
                'stock_quantity' => 5,
                'image_path' => null, // atau 'products/sample_laptop.jpg' jika Anda punya gambar contoh
            ],
            [
                'name' => 'Keyboard Mekanikal RGB K70 Pro',
                'category_name' => 'Keyboard',
                'description' => 'Keyboard mekanikal full-size dengan switch Cherry MX Red, lampu RGB per-tombol, dan wrist rest yang nyaman.',
                'price' => 1250000,
                'stock_quantity' => 10,
                'image_path' => null,
            ],
            [
                'name' => 'Mouse Gaming Viper Ultimate Wireless',
                'category_name' => 'Mouse',
                'description' => 'Mouse gaming wireless super ringan dengan sensor optik presisi tinggi, cocok untuk e-sports.',
                'price' => 899000,
                'stock_quantity' => 15,
                'image_path' => null,
            ],
            [
                'name' => 'Monitor LED 24 inch Full HD IPS',
                'category_name' => 'Monitor',
                'description' => 'Monitor 24 inch dengan panel IPS, resolusi Full HD (1920x1080), refresh rate 75Hz, dan desain bezel tipis.',
                'price' => 2300000,
                'stock_quantity' => 8,
                'image_path' => null,
            ],
            [
                'name' => 'SSD NVMe 1TB Gen4 Kingston KC3000',
                'category_name' => 'Penyimpanan Data', // Pastikan ada kategori "Penyimpanan Data"
                'description' => 'SSD NVMe PCIe 4.0 super cepat dengan kecepatan baca/tulis tinggi, ideal untuk OS, aplikasi berat, dan game.',
                'price' => 1650000,
                'stock_quantity' => 20,
                'image_path' => null,
            ],
            [
                'name' => 'RAM DDR4 16GB Kit (2x8GB) 3200MHz Corsair Vengeance',
                'category_name' => 'RAM',
                'description' => 'Kit RAM dual channel DDR4 16GB (2x8GB) dengan kecepatan 3200MHz dan heatspreader aluminium.',
                'price' => 950000,
                'stock_quantity' => 12,
                'image_path' => null,
            ],
            [
                'name' => 'Printer All-in-One Pixma G2020 Ink Tank',
                'category_name' => 'Printer',
                'description' => 'Printer multifungsi (print, scan, copy) dengan sistem ink tank yang hemat biaya cetak.',
                'price' => 1850000,
                'stock_quantity' => 7,
                'image_path' => null,
            ],
            [
                'name' => 'Router WiFi Dual Band AC1200 TP-Link Archer C50',
                'category_name' => 'Jaringan',
                'description' => 'Router WiFi dual band dengan kecepatan hingga 1200Mbps, cocok untuk streaming dan gaming online.',
                'price' => 420000,
                'stock_quantity' => 10,
                'image_path' => null,
            ],
            [
                'name' => 'Webcam Full HD 1080p ProStream C922',
                'category_name' => 'Webcam', // Atau 'Periferal Komputer'
                'description' => 'Webcam Full HD 1080p dengan mic internal, ideal untuk streaming, video conference, dan belajar online.',
                'price' => 375000,
                'stock_quantity' => 18,
                'image_path' => null,
            ],
            [
                'name' => 'Cooling Pad Laptop XFan 5 Kipas',
                'category_name' => 'Aksesoris Laptop',
                'description' => 'Cooling pad laptop dengan 5 kipas pendingin dan lampu LED, menjaga suhu laptop tetap optimal.',
                'price' => 175000,
                'stock_quantity' => 25,
                'image_path' => null,
            ],
        ];

        foreach ($products as $productData) {
            // Cek jika produk dengan nama yang sama sudah ada untuk menghindari duplikasi
            if (Product::where('name', $productData['name'])->exists()) {
                $this->command->info('Produk "' . $productData['name'] . '" sudah ada, dilewati.');
                continue;
            }

            Product::create([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name'], '-'), // Buat slug otomatis
                'category_id' => $getCategoryId($productData['category_name']),
                'description' => $productData['description'],
                'price' => $productData['price'],
                'stock_quantity' => $productData['stock_quantity'],
                'image_path' => $productData['image_path'],
            ]);
        }
        $this->command->info('ProductSeeder selesai dijalankan.');
    }
}