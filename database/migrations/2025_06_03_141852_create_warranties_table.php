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
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            // Satu order servis hanya memiliki satu entri garansi
            $table->unsignedBigInteger('service_order_id')->unique(); 
            $table->date('start_date'); // Tanggal mulai garansi
            $table->date('end_date');   // Tanggal berakhir garansi
            $table->text('terms')->nullable(); // Syarat dan ketentuan garansi
            $table->timestamps();

            // Definisi foreign key untuk service_order
            $table->foreign('service_order_id')
                  ->references('id')
                  ->on('service_orders')
                  ->onDelete('cascade'); // Jika order servis dihapus, data garansi terkait juga dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};
