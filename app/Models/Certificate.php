<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'nomor_surat',
        'nilai_angka',
        'nilai_huruf',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}