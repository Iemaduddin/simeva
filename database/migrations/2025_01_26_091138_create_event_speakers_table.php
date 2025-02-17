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
        // Speakers Table
        Schema::create('speakers', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->string('name', 255);
            $table->string('email', 255)->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('organization', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('biography', 255)->nullable();
            $table->string('personal_document', 255)->nullable();
            $table->timestamps();
        });

        // Event Speakers Table
        Schema::create('event_speakers', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->uuid('event_id');
            $table->uuid('speaker_id');
            $table->enum('role', ['keynote_speaker', 'speaker', 'guest']);
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('speaker_id')->references('id')->on('speakers')->onDelete('cascade');
            $table->unique(['event_id', 'speaker_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speakers');
        Schema::dropIfExists('event_speakers');
    }
};
