<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asset_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->uuid('booking_id');
            $table->uuid('user_id');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'credit_card', 'e-wallet']);
            $table->string('proof_of_payment')->nullable();
            $table->enum('status', ['paid', 'pending', 'failed'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('booking_id')->references('id')->on('asset_bookings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_transactions');
    }
};
