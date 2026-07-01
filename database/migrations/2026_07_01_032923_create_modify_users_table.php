<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'mahasiswa'])->default('mahasiswa')->after('email');
            $table->boolean('is_active')->default(true)->after('role');
            
            // --- FITUR BYPASS & VALIDASI BERKAS ---
            // Menandai apakah mahasiswa sudah upload formulir pendaftaran
            $table->boolean('is_uploaded_form')->default(false)->after('is_active');
            
            // Menandai apakah akun didaftarkan manual & langsung di-bypass oleh Admin BPS
            $table->boolean('is_bypassed')->default(false)->after('is_uploaded_form');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'is_uploaded_form', 'is_bypassed']);
        });
    }
};