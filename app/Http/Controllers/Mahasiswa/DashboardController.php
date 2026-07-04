<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller; // Pastikan ini ada

class DashboardController extends Controller 
{
    public function index()
    {
        return view('mahasiswa.dashboard'); // Sesuaikan dengan lokasi file view Anda
    }
}