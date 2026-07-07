<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Langkah 1: perlebar enum, izinkan 'alpa' DAN 'terlambat' sementara ──
        DB::statement("
            ALTER TABLE presensis
            MODIFY COLUMN status ENUM('hadir', 'sakit', 'izin', 'alpa', 'terlambat')
            NOT NULL DEFAULT 'alpa'
        ");

        // ── Langkah 2: pindahkan semua data lama dari 'alpa' ke 'terlambat' ──
        DB::table('presensis')->where('status', 'alpa')->update(['status' => 'terlambat']);

        // ── Langkah 3: persempit lagi, 'alpa' resmi dihapus dari enum ──
        DB::statement("
            ALTER TABLE presensis
            MODIFY COLUMN status ENUM('hadir', 'sakit', 'izin', 'terlambat')
            NOT NULL DEFAULT 'terlambat'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE presensis
            MODIFY COLUMN status ENUM('hadir', 'sakit', 'izin', 'alpa', 'terlambat')
            NOT NULL DEFAULT 'alpa'
        ");

        DB::table('presensis')->where('status', 'terlambat')->update(['status' => 'alpa']);

        DB::statement("
            ALTER TABLE presensis
            MODIFY COLUMN status ENUM('hadir', 'sakit', 'izin', 'alpa')
            NOT NULL DEFAULT 'alpa'
        ");
    }
};