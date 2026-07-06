<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sertifikat_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('sertifikat_settings', 'nama_color')) {
                $table->string('nama_color', 7)->default('#b8860b'); // format hex, contoh #b8860b
            }
            if (! Schema::hasColumn('sertifikat_settings', 'nomor_color')) {
                $table->string('nomor_color', 7)->default('#555555');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sertifikat_settings', function (Blueprint $table) {
            $table->dropColumn(['nama_color', 'nomor_color']);
        });
    }
};