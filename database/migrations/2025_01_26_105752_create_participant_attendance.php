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
        Schema::create('participant_attendance', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->uuid('user_id');
            $table->uuid('event_id');
            // $table->uuid('event_step_id')->nullable();
            $table->boolean('attendance_arrival')->default(false);
            $table->boolean('attendance_departure')->default(false);
            $table->timestamp('attendance_arrival_time')->nullable();
            $table->timestamp('attendance_departure_time')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            // $table->foreign('event_step_id')->references('id')->on('event_steps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_attendance');
    }
};