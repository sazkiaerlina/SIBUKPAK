<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (! Schema::hasColumn('mahasiswas', 'kategori_pemohon')) {
                $table->enum('kategori_pemohon', ['individu', 'kelompok'])
                    ->default('individu')->after('user_id');
            }
            if (! Schema::hasColumn('mahasiswas', 'jenis_instansi')) {
                $table->enum('jenis_instansi', ['perguruan_tinggi', 'smk'])
                    ->default('perguruan_tinggi')->after('kategori_pemohon');
            }
            if (! Schema::hasColumn('mahasiswas', 'peran_kelompok')) {
                $table->enum('peran_kelompok', ['individu', 'ketua', 'anggota'])
                    ->default('individu')->after('jenis_instansi');
            }
            if (! Schema::hasColumn('mahasiswas', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])
                    ->nullable()->after('tanggal_selesai');
            }
            if (! Schema::hasColumn('mahasiswas', 'kelas')) {
                $table->string('kelas', 5)->nullable()->after('fakultas');
            }
            if (! Schema::hasColumn('mahasiswas', 'kompetensi_keahlian')) {
                $table->string('kompetensi_keahlian')->nullable()->after('kelas');
            }
            if (! Schema::hasColumn('mahasiswas', 'surat_pengantar_path')) {
                $table->string('surat_pengantar_path')->nullable()->after('status_magang');
            }
            if (! Schema::hasColumn('mahasiswas', 'proposal_path')) {
                $table->string('proposal_path')->nullable()->after('surat_pengantar_path');
            }
            if (! Schema::hasColumn('mahasiswas', 'status_pendaftaran')) {
                $table->enum('status_pendaftaran', ['pending', 'diterima', 'ditolak'])
                    ->default('pending')->after('proposal_path');
            }
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('nim', 20)->nullable()->change();
            $table->string('jurusan')->nullable()->change();
            $table->string('prodi')->nullable()->change();
            $table->string('fakultas')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn([
                'kategori_pemohon',
                'jenis_instansi',
                'peran_kelompok',
                'jenis_kelamin',
                'kelas',
                'kompetensi_keahlian',
                'surat_pengantar_path',
                'proposal_path',
                'status_pendaftaran',
            ]);
            $table->string('nim', 20)->nullable(false)->change();
            $table->string('jurusan')->nullable(false)->change();
            $table->string('prodi')->nullable(false)->change();
            $table->string('fakultas')->nullable(false)->change();
        });
    }
};