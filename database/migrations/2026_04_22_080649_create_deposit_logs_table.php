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
        Schema::create('deposit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            // Nominal uang yang terdeteksi sensor
            $table->decimal('nominal', 10, 2);
            // Signature warna untuk validasi/debugging sensor IoT
            $table->string('color_signature')->nullable()
                  ->comment('Nilai sensor warna yang dibaca IoT sebagai bukti kalibrasi');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_logs');
    }
};
