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
        Schema::create('event_forms', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->uuid('event_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(true);
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
        Schema::create('form_fields', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->uuid('form_id');
            $table->string('field_label');
            $table->enum('field_type', ['text', 'number', 'date', 'email', 'file', 'checkbox', 'textarea']);
            $table->boolean('is_required')->default(true);
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('event_forms')->onDelete('cascade');
        });

        Schema::create('form_responses', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->uuid('form_id');
            $table->uuid('participant_id');
            $table->uuid('field_id');
            $table->text('response_value');
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('event_forms')->onDelete('cascade');
            $table->foreign('participant_id')->references('id')->on('event_participants')->onDelete('cascade');
            $table->foreign('field_id')->references('id')->on('form_fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_responses');
        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('event_forms');
    }
};