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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable(); // FK ke categories.id, boleh null
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Contoh: 10 digit total, 2 digit di belakang koma
            $table->integer('stock_quantity')->default(0);
            $table->string('image_path')->nullable(); // Path ke gambar produk
            $table->timestamps();

            // Definisi foreign key
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null'); // Jika kategori dihapus, set category_id di produk ini jadi NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
