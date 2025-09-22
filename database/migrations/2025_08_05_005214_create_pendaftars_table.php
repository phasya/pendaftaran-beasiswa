<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beasiswa_id');
            $table->string('email');

            // JSON field untuk menyimpan semua data form dinamis dari admin
            $table->json('form_data')->nullable();

            // JSON field untuk menyimpan dokumen yang diupload
            $table->json('uploaded_documents')->nullable();

            // Status pendaftaran
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');

            // Field untuk admin memberikan catatan/alasan
            $table->text('rejection_reason')->nullable();
            $table->text('catatan_admin')->nullable();

            // Field untuk mengatur resubmit
            $table->boolean('can_resubmit')->default(false);
            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();

            // Foreign key ke tabel beasiswas
            $table->foreign('beasiswa_id')->references('id')->on('beasiswas')->onDelete('cascade');

            // Indexes untuk performance
            $table->index(['beasiswa_id', 'status']);
            $table->index('email');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};