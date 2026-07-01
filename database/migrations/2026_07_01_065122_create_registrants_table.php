<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_kelompok', function (Blueprint $table) {
            $table->id();

            // Menghubungkan ke user_id si KETUA yang menginput data
            $table->foreignId('ketua_id')->constrained('users')->onDelete('cascade');

            // Kode kelompok, digenerate otomatis saat ketua pertama kali submit form
            $table->string('kode_kelompok');

            // Informasi lengkap teman kelompok yang diketik oleh ketua
            $table->string('nama_anggota');
            $table->string('email_anggota')->unique();
            $table->string('nim_anggota');
            $table->string('univ_anggota');

            // Kolom kunci: Awalnya NULL, nanti diisi ID user teman kalau mereka sudah bikin akun
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_kelompok');
    }
};