<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asset_bookings', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->uuid('asset_id');
            $table->uuid('user_id')->nullable();
            $table->string('external_user', 100)->nullable();
            $table->string('booking_number');
            $table->uuid('event_id')->nullable();
            $table->unsignedBigInteger('asset_category_id')->nullable();
            $table->dateTime('usage_date_start');
            $table->dateTime('usage_date_end');
            $table->dateTime('loading_date_start')->nullable();
            $table->dateTime('loading_date_end')->nullable();
            $table->dateTime('unloading_date')->nullable();
            $table->string('usage_event_name');
            $table->enum('payment_type', ['dp', 'lunas'])->nullable();
            $table->decimal('total_amount', 18, 2)->nullable();
            $table->enum('status', ['submission_booking', 'booked', 'rejected_booking', 'submission_dp_payment', 'submission_full_payment', 'rejected_dp_payment', 'rejected_full_payment', 'approved_dp_payment', 'approved_full_payment', 'approved', 'rejected', 'cancelled'])->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('set null');
            $table->foreign('asset_category_id')->references('id')->on('asset_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_bookings');
    }
};
