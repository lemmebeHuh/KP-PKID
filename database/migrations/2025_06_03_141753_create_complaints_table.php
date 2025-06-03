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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            // service_order_id boleh null jika komplain tidak terkait order spesifik
            $table->unsignedBigInteger('service_order_id')->nullable(); 
            $table->unsignedBigInteger('customer_id'); // FK ke users.id (Pelanggan yang mengajukan komplain)
            $table->string('subject'); // Judul/subjek komplain
            $table->text('description'); // Deskripsi detail komplain
            $table->string('status')->default('Open'); // Contoh: 'Open', 'In Progress', 'Resolved', 'Closed'
            $table->text('resolved_notes')->nullable(); // Catatan penyelesaian dari admin
            $table->timestamps();

            // Definisi foreign key untuk service_order
            $table->foreign('service_order_id')
                  ->references('id')
                  ->on('service_orders')
                  ->onDelete('set null'); // Jika order servis dihapus, komplain ini tidak lagi terikat (jadi NULL)

            // Definisi foreign key untuk customer
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict'); // Customer tidak boleh dihapus jika masih punya komplain
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
