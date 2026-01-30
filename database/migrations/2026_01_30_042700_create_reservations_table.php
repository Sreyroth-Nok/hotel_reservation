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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('reservation_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms', 'room_id')->onDelete('cascade');
            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('total_price', 10, 2)->nullable();
            $table->enum('status', ['booked', 'checked_in', 'checked_out', 'cancelled'])->default('booked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
