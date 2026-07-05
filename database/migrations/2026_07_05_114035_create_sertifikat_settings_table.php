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
        Schema::create('sertifikat_settings', function (Blueprint $table) {
            $table->id();
            $table->string('background_path')->nullable();

            // Posisi dalam PERSEN (0-100) dari lebar/tinggi halaman.
            // Supaya tetap proporsional walau background diganti resolusi lain.
            $table->float('nama_top')->default(45);
            $table->float('nama_left')->default(50);
            $table->integer('nama_font_size')->default(30);

            $table->float('nomor_top')->default(18);
            $table->float('nomor_left')->default(50);
            $table->integer('nomor_font_size')->default(12);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat_settings');
    }
};