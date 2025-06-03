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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Kolom id (PK, auto-increment)
            $table->string('name'); // Nama kategori
            $table->string('slug')->unique(); // Untuk URL-friendly
            $table->text('description')->nullable();
            // Kolom type untuk membedakan kategori produk, layanan, atau artikel
            $table->string('type')->comment('Contoh: product, service, article'); 
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
