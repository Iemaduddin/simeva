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
        Schema::create('asset_booking_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('booking_id')->nullable();
            $table->uuid('event_id')->nullable();

            $table->string('document_path');
            $table->enum('document_type', ['Identitas Diri', 'Form Peminjaman', 'Surat Perjanjian Kontrak', 'Surat Pernyataan', 'Surat Disposisi']);
            $table->uuid('uploaded_by');
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('asset_bookings')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_booking_documents');
    }
};
