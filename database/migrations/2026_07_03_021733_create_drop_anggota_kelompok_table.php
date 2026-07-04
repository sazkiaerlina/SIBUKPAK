
2026 07 05 000001 drop anggota kelompok table · PHP
<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('anggota_kelompok');
    }
 
    public function down(): void
    {
        // Sengaja dikosongkan — kalau butuh rollback ke struktur lama,
        // jalankan ulang migration pembuatan anggota_kelompok yang asli.
    }
};
 