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
        Schema::create('guests', function (Blueprint $table) {
            $table->id('guest_id');
            $table->string('full_name', 100);
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('address', 255)->nullable();
            $table->string('id_card_number', 50)->nullable();
            $table->string('notes', 500)->nullable();
            $table->timestamps();
        });

        // Modify reservations table to use guests instead of users
        Schema::table('reservations', function (Blueprint $table) {
            // First drop the foreign key if it exists
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('guest_id')->nullable();
            $table->foreign('guest_id')->references('guest_id')->on('guests')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['guest_id']);
            $table->dropColumn('guest_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
        });

        Schema::dropIfExists('guests');
    }
};
