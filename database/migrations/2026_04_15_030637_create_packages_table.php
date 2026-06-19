<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tracking_number')->unique();
            $table->enum('payment_type', ['prepaid', 'cod']);
            
            $table->decimal('amount', 10, 2)->default(0); // Target nominal COD yang harus dibayar
            $table->decimal('total_received', 10, 2)->default(0)->comment('Akumulasi uang yang sudah masuk ke slot via sensor IoT. Digunakan untuk menghitung sisa tagihan COD.');

            $table->enum('payment_status', ['pending', 'deposited', 'taken'])->default('pending');
            $table->enum('package_status', ['waiting', 'arrived'])->default('waiting');
            $table->foreignId('slot_id')->nullable()->constrained('money_slots');
            $table->timestamp('arrived_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
