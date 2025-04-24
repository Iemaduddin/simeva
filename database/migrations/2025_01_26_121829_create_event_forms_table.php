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
        // Tabel untuk form event
        // Schema::create('event_forms', function (Blueprint $table) {
        //     $table->bigIncrements('id'); // Primary key
        //     $table->uuid('event_id'); // Foreign key ke tabel events
        //     $table->string('name'); // Nama form
        //     $table->text('description')->nullable(); // Deskripsi form
        //     $table->boolean('is_required')->default(true); // Apakah form wajib diisi
        //     $table->timestamps();

        //     $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        // });

        // Tabel untuk field dalam form
        Schema::create('event_forms', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->uuid('event_id'); // Foreign key ke tabel events
            $table->string('field_label'); // Label atau nama field
            $table->enum('field_type', [
                'text',
                'number',
                'date',
                'email',
                'file',
                'checkbox',
                'textarea',
                'radio',
                'dropdown',
                'rating'
            ]); // Jenis field
            $table->boolean('is_required')->default(true); // Apakah field wajib diisi
            // $table->integer('field_order')->default(0); // Urutan field
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        // Tabel untuk opsi field (untuk radio, dropdown, atau checkbox)
        Schema::create('field_options', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('event_form_field_id'); // Foreign key ke tabel form_fields
            $table->string('option_label'); // Label opsi
            $table->string('option_value'); // Value opsi
            $table->timestamps();

            $table->foreign('event_form_field_id')->references('id')->on('event_forms')->onDelete('cascade');
        });

        // Tabel untuk menyimpan jawaban/respons peserta
        Schema::create('form_responses', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('event_form_field_id'); // Foreign key ke tabel event_forms
            $table->uuid('participant_id'); // Foreign key ke tabel event_participants
            $table->json('response_value'); // Nilai respons dalam format JSON
            $table->timestamps();

            $table->foreign('event_form_field_id')->references('id')->on('event_forms')->onDelete('cascade');
            $table->foreign('participant_id')->references('id')->on('event_participants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_responses');
        Schema::dropIfExists('field_options');
        Schema::dropIfExists('event_forms');
    }
};
