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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_order_id'); // FK ke service_orders.id
            $table->unsignedBigInteger('customer_id'); // FK ke users.id (Pelanggan yang memberi review)
            $table->tinyInteger('rating'); // Rating 1-5 bintang
            $table->text('comment')->nullable(); // Komentar ulasan
            $table->boolean('is_approved')->default(true); // Jika perlu moderasi admin, default true (langsung tampil) atau false (perlu approval)
            $table->timestamps();

            // Unique constraint agar satu order hanya bisa direview satu kali oleh satu customer
            // Sebenarnya cukup service_order_id unik jika 1 order = 1 review.
            // Jika customer_id juga dimasukkan, ini lebih eksplisit tapi service_order_id sudah cukup jika review hanya dari customer pemilik order.
            $table->unique(['service_order_id'], 'service_order_review_unique');


            // Definisi foreign key untuk service_order
            $table->foreign('service_order_id')
                  ->references('id')
                  ->on('service_orders')
                  ->onDelete('cascade'); // Jika order servis dihapus, review terkait juga dihapus

            // Definisi foreign key untuk customer
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict'); // Customer tidak boleh dihapus jika masih punya review (kecuali direlakan)
                                          // atau onDelete('cascade') jika ingin review hilang saat customer dihapus. 'restrict' lebih aman.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
