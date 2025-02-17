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
        Schema::create('event_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->uuid('user_id');
            $table->uuid('event_id');
            $table->decimal('event_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['paid', 'pending', 'failed']);
            $table->timestamp('payment_date')->nullable();
            $table->string('proof_of_payment')->nullable();
            $table->string('signature')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('event_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_transactions');
    }
};
