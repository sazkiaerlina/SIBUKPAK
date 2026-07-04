<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (! Schema::hasColumn('mahasiswas', 'laporan_path')) {
                $table->string('laporan_path')->nullable()->after('proposal_path');
            }
            if (! Schema::hasColumn('mahasiswas', 'laporan_uploaded_at')) {
                $table->timestamp('laporan_uploaded_at')->nullable()->after('laporan_path');
            }
            if (! Schema::hasColumn('mahasiswas', 'sertifikat_path')) {
                // Diisi ADMIN setelah magang selesai, mahasiswa hanya download
                $table->string('sertifikat_path')->nullable()->after('laporan_uploaded_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['laporan_path', 'laporan_uploaded_at', 'sertifikat_path']);
        });
    }
};