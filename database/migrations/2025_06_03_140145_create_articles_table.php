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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable(); // FK ke categories.id (type='article'), boleh null
            $table->unsignedBigInteger('author_id'); // FK ke users.id (Admin/Penulis)
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->text('excerpt')->nullable(); // Ringkasan singkat
            $table->string('status')->default('draft'); // Contoh: 'draft', 'published', 'archived'
            $table->timestamp('published_at')->nullable(); // Tanggal publikasi
            $table->string('featured_image_path')->nullable();
            $table->timestamps();

            // Definisi foreign key untuk category
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null'); // Jika kategori dihapus, set category_id jadi NULL

            // Definisi foreign key untuk author
            $table->foreign('author_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict'); // Penulis tidak boleh dihapus jika masih punya artikel
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
