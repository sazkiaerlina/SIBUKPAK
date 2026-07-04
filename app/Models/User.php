<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'is_uploaded_form',
        'is_bypassed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_uploaded_form' => 'boolean',
            'is_bypassed' => 'boolean',
        ];
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Boleh login kalau: admin, ATAU sudah di-bypass manual oleh admin,
     * ATAU pendaftarannya sudah berstatus is_active = true (disetujui admin).
     */
    public function bolehLogin(): bool
    {
        return $this->isAdmin() || $this->is_bypassed || $this->is_active;
    }

    /**
     * Satu sumber kebenaran: kemana user ini seharusnya diarahkan
     * setelah login / setiap kali dia coba masuk ke halaman terproteksi.
     */
    public function redirectPath(): string
    {
        if ($this->isAdmin()) {
            return route('admin.home');
        }

        // Belum upload form pendaftaran -> paksa lengkapi dulu
        if (! $this->is_uploaded_form) {
            return route('daftar.create'); // sesuai name route kamu: '/daftar'
        }

        // Sudah upload, tapi belum di-approve/bypass admin -> masih pending
        if (! $this->bolehLogin()) {
            return $this->mahasiswa
            ? route('daftar.sukses', $this->mahasiswa->id)
            : route('daftar.create');
        }

        // Lolos semua -> dashboard
        return route('dashboard');
    }
}