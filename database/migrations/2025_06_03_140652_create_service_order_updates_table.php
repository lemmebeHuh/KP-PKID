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
        Schema::create('service_order_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_order_id'); // FK ke service_orders.id
            $table->unsignedBigInteger('updated_by_id')->nullable(); // FK ke users.id (Admin/Teknisi yang melakukan update)

            // Jenis update: 'Status Change', 'Technician Note', 'Photo Upload', 'Quotation Sent', 'Customer Feedback', dll.
            $table->string('update_type'); 
            $table->text('notes')->nullable(); // Catatan detail terkait update

            // Jika update_type adalah 'Status Change', kolom ini bisa diisi
            $table->string('status_from')->nullable(); // Status sebelum diubah
            $table->string('status_to')->nullable();   // Status setelah diubah

            $table->timestamps(); // created_at akan menandakan waktu update

            // Definisi foreign key untuk service_order
            $table->foreign('service_order_id')
                  ->references('id')
                  ->on('service_orders')
                  ->onDelete('cascade'); // Jika order servis dihapus, semua update terkait juga dihapus

            // Definisi foreign key untuk user yang melakukan update
            $table->foreign('updated_by_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null'); // Jika user (admin/teknisi) dihapus, update tetap ada tapi updated_by_id jadi NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_order_updates');
    }
};
