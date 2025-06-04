<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash; // Import Hash facade

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah role Admin ada
        $adminRole = Role::where('name', 'Admin')->first();

        if ($adminRole) {
            // Cek apakah user admin sudah ada
            if (User::where('email', 'admin@pangkalan_komputer.id')->count() == 0) {
                User::create([
                    'name' => 'Irham Fauzan',
                    'email' => 'admin@pangkalan_komputer.id',
                    'password' => Hash::make('pekaydi1'),
                    'role_id' => $adminRole->id,
                    'phone_number' => '083192310040', // Opsional
                    'email_verified_at' => now(), // Jika ingin langsung verified
                ]);
            }
        } else {
            // Jika role Admin belum ada, Anda bisa memberi pesan error atau membuatnya di sini
            // Tapi idealnya RolesTableSeeder sudah dijalankan terlebih dahulu.
            $this->command->warn('Role Admin tidak ditemukan. Jalankan RolesTableSeeder terlebih dahulu.');
        }
    }
}