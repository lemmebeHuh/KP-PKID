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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('service_order_number')->unique(); // Nomor servis unik (bisa untuk QR Code)
            $table->unsignedBigInteger('customer_id'); // FK ke users.id (Pelanggan)
            $table->unsignedBigInteger('assigned_technician_id')->nullable(); // FK ke users.id (Teknisi), boleh null

            $table->string('device_type'); // Contoh: 'Laptop', 'PC', 'Printer'
            $table->string('device_brand_model')->nullable(); // Contoh: 'Acer Aspire 5 A514-54'
            $table->string('serial_number')->nullable(); // Nomor seri perangkat
            $table->text('problem_description'); // Keluhan awal pelanggan
            $table->text('accessories_received')->nullable(); // Kelengkapan yg dibawa: tas, charger, dll.

            // Status utama order servis
            $table->string('status')->default('Pending'); // Contoh: 'Pending', 'Diagnosing', 'Quotation Sent', 'In Progress', 'Awaiting Parts', 'Completed', 'Picked Up', 'Cancelled'

            $table->date('estimated_completion_date')->nullable(); // Estimasi tanggal selesai
            $table->text('quotation_details')->nullable(); // Rincian tawaran perbaikan dan biaya
            // Status persetujuan pelanggan terhadap kuotasi
            $table->string('customer_approval_status')->nullable(); // Contoh: 'Pending', 'Approved', 'Rejected'

            $table->decimal('final_cost', 10, 2)->nullable(); // Biaya akhir perbaikan

            $table->timestamp('date_received')->useCurrent(); // Tanggal perangkat diterima
            $table->timestamp('date_completed')->nullable(); // Tanggal perbaikan selesai
            $table->timestamp('date_picked_up')->nullable(); // Tanggal perangkat diambil pelanggan

            $table->timestamps(); // created_at dan updated_at

            // Definisi foreign key untuk customer
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict'); // Pelanggan tidak boleh dihapus jika masih punya order servis aktif/riwayat

            // Definisi foreign key untuk teknisi
            $table->foreign('assigned_technician_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null'); // Jika teknisi dihapus, order servis ini jadi tidak terassign (assigned_technician_id jadi NULL)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
