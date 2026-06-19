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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Relasi tetap ada
            $table->foreignId('package_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // MEKANISME: Bagaimana dia masuk?
            $table->enum('method', ['scan', 'keypad', 'web', 'magnet_sensor'])->nullable();
            
            // AKSI: Apa yang dia lakukan?
            $table->enum('event', ['door_opened', 'door_closed', 'package_arrived', 'access_denied'])->nullable();
            
            // DETAIL: Simpan input mentah jika perlu
            $table->string('input_value')->nullable(); // Ganti dari input_key agar lebih umum
            
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->enum('actor_type', ['user', 'courier', 'system'])->nullable(); // 'courier', 'user', 'system'
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
