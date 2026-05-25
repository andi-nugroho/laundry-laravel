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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->date('booking_date');
            $table->date('estimated_finish_date')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('total_price', 12, 2)->default(0);
            $table->enum('pickup_type', ['antar_sendiri', 'pickup'])->default('antar_sendiri');
            $table->enum('status', [
                'booking_masuk',
                'diterima',
                'dicuci',
                'dikeringkan',
                'disetrika',
                'selesai',
                'diambil',
                'dibatalkan',
            ])->default('booking_masuk');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
