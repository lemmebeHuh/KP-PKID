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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable(); // FK ke categories.id, boleh null
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('estimated_price', 10, 2)->nullable(); // Estimasi harga, boleh null
            $table->string('estimated_duration')->nullable(); // Contoh: '1-2 hari', '3 jam'
            $table->timestamps();

            // Definisi foreign key
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null'); // Jika kategori dihapus, set category_id jadi NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
