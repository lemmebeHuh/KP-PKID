<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade
use App\Models\Role; // Import model Role

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel roles terlebih dahulu jika perlu untuk menghindari duplikasi saat seeding ulang
        // Role::truncate(); // Hati-hati jika ada foreign key constraint

        // Atau lebih aman, cek jika data sudah ada
        if (Role::count() == 0) {
            Role::create(['name' => 'Admin', 'description' => 'Administrator Sistem Penuh']);
            Role::create(['name' => 'Teknisi', 'description' => 'Teknisi Perbaikan Perangkat']);
            Role::create(['name' => 'Pelanggan', 'description' => 'Pengguna Layanan dan Pelacakan Servis']);
        }
    }
}