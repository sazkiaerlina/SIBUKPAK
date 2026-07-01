<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Sertifikat milik siapa

            $table->string('nomor_surat'); // Nomor resmi dari BPS, misal: B-123/BPS/2026
            $table->integer('nilai_angka'); // Nilai berupa angka, misal: 92
            $table->string('nilai_huruf'); // Predikat nilai akhir, misal: A / Sangat Baik

            // TODO: tambahkan kolom lain di sini jika ada (tabel di gambar terpotong "...")
            // contoh kemungkinan: $table->string('file_pdf')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};