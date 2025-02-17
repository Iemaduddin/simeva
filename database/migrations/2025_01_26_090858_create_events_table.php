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
        // Events Table
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->string('title', 255);
            $table->string('theme', 255)->nullable();
            $table->text('description')->nullable();
            $table->enum('scope', ['internal_organisasi', 'internal_jurusan', 'internal_kampus', 'umum']);
            $table->dateTime('date_event_start')->nullable();
            $table->dateTime('date_event_end')->nullable();
            $table->dateTime('registration_date_start')->nullable();
            $table->dateTime('registration_date_end')->nullable();
            $table->boolean('registration_extended')->default(false);
            $table->text('location')->nullable();
            $table->integer('remaining_quota')->nullable();
            $table->integer('quota')->nullable();
            $table->enum('event_mode', ['offline', 'online', 'hybrid']);
            $table->text('benefit')->nullable();
            $table->text('sponsored_by')->nullable();
            $table->text('media_partner')->nullable();
            $table->text('additional_links')->nullable();
            $table->string('event_leader', 255);
            $table->string('contact_person', 255)->nullable();
            $table->boolean('is_draft')->default(false);
            $table->boolean('is_publish')->default(false);
            $table->enum('status', ['planned', 'ongoing', 'completed']);
            $table->uuid('organizer_id');
            $table->timestamps();

            $table->foreign('organizer_id')->references('id')->on('organizers')->onDelete('cascade');
        });

        // Categories of Event Table
        Schema::create('categories_of_event', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->string('name', 100);
        });

        // Event Categories Table
        Schema::create('event_categories', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->uuid('event_id');
            $table->uuid('category_id');

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories_of_event')->onDelete('cascade');
            $table->unique(['event_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('categories_of_event');
        Schema::dropIfExists('event_categories');
    }
};
