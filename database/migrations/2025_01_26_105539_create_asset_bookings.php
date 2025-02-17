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
            $table->uuid('user_id');
            $table->uuid('event_id')->nullable();
            $table->uuid('asset_category_id')->nullable();
            $table->dateTime('usage_date_start');
            $table->dateTime('usage_date_end');
            $table->integer('booking_duration')->default(1);
            $table->enum('payment_type', ['dp', 'lunas']);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['booked', 'submission', 'approved', 'rejected'])->default('submission');
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
