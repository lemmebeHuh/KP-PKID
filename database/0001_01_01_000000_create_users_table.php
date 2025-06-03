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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id'); // Untuk foreign key ke tabel roles
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number')->nullable()->unique();
            $table->text('address')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Definisi foreign key dengan onDelete('restrict')
            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onDelete('restrict'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};