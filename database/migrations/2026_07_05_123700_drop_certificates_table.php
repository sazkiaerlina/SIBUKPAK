<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/..._remove_nilai_from_certificates_table.php
public function up(): void
{
    Schema::table('certificates', function (Blueprint $table) {
        $table->dropColumn(['nilai_angka', 'nilai_huruf']);
    });
}

public function down(): void
{
    Schema::table('certificates', function (Blueprint $table) {
        $table->integer('nilai_angka')->nullable();
        $table->string('nilai_huruf')->nullable();
    });
}
};
