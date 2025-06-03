<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('technician_profiles', function (Blueprint $table) {
            // Menggunakan user_id sebagai Primary Key dan Foreign Key sekaligus (One-to-One relationship)
            $table->unsignedBigInteger('user_id')->primary(); 
            $table->string('specialization')->nullable(); // Keahlian utama, contoh: 'Laptop Hardware', 'Software Troubleshooting'
            $table->tinyInteger('experience_years')->unsigned()->nullable(); // Pengalaman dalam tahun
            $table->text('bio')->nullable(); // Deskripsi singkat atau bio teknisi
            // Anda bisa menambahkan kolom lain seperti sertifikasi, dll.
            $table->timestamps();

            // Definisi foreign key ke tabel users
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); // Jika user (teknisi) dihapus, profilnya juga dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technician_profiles');
    }
};
