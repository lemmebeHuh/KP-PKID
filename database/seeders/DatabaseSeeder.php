<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            AdminUserSeeder::class,
            // Anda bisa tambahkan seeder lain di sini nanti, misalnya:
            // CategoriesTableSeeder::class,
            // ProductsTableSeeder::class,
        ]);
    }
}
