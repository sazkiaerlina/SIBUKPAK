<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('nim', 20)->unique();
            $table->string('jurusan');                 // Program Studi / Jurusan (dari form)
            $table->string('prodi');                 // Program Studi / Jurusan (dari form)
            $table->string('fakultas');              // Fakultas (dari form)
            $table->string('universitas');                  // Nama Universitas / Kampus (dari form)
            $table->string('nomor_hp', 15)->nullable();

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->string('kode_kelompok')->nullable(); // Kode tim, kosong jika individu

            // Otomatis terisi 'pendaftar' saat pertama kali form dikirim
            $table->string('status_magang')->default('aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
