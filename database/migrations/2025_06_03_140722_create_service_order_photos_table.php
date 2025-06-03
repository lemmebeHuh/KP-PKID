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
        Schema::create('service_order_photos', function (Blueprint $table) {
            $table->id();
            // Mengaitkan foto dengan sebuah service_order_update spesifik
            // Jika ingin foto bisa langsung ke service_order tanpa update, bisa diganti jadi service_order_id
            $table->unsignedBigInteger('service_order_update_id'); 
            $table->string('file_path'); // Path ke file foto yang disimpan di server
            $table->string('caption')->nullable(); // Keterangan singkat untuk foto
            $table->unsignedBigInteger('uploaded_by_id')->nullable(); // FK ke users.id (Admin/Teknisi yang mengunggah)
            $table->timestamps();

            // Definisi foreign key untuk service_order_update
            $table->foreign('service_order_update_id')
                  ->references('id')
                  ->on('service_order_updates')
                  ->onDelete('cascade'); // Jika entri update dihapus, foto terkait juga dihapus

            // Definisi foreign key untuk user yang mengunggah
            $table->foreign('uploaded_by_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null'); // Jika user (admin/teknisi) dihapus, info uploader jadi NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_order_photos');
    }
};
