<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('mahasiswas', function (Blueprint $table) {
        $table->string('status_magang')->nullable()->change();
    });

    // UPDATE DATA
    DB::table('mahasiswas')->update([
        'status_magang' => DB::raw("
            CASE
                WHEN status_pendaftaran != 'diterima' THEN NULL
                WHEN CURDATE() < tanggal_mulai THEN 'belum_mulai'
                WHEN CURDATE() > tanggal_selesai THEN 'selesai'
                ELSE 'berlangsung'
            END
        "),
    ]);

    // UBAH JADI ENUM NULLABLE
    Schema::table('mahasiswas', function (Blueprint $table) {
        $table->enum('status_magang', ['belum_mulai', 'berlangsung', 'selesai'])
              ->nullable() 
              ->default(null) 
              ->change();
    });
}
};