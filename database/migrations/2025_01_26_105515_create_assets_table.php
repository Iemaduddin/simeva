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
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->enum('type', ['building', 'transportation']);
            $table->enum('facility_scope', ['jurusan', 'umum']);
            $table->uuid('jurusan_id')->nullable();
            $table->text('facility')->nullable();
            $table->string('available_at')->nullable();
            $table->text('asset_images');
            $table->enum('booking_type', ['daily', 'annual'])->default('daily');
            $table->enum('status', ['available', 'borrowed', 'unavailable'])->default('available');
            $table->timestamps();

            $table->foreign('jurusan_id')->references('id')->on('jurusan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
