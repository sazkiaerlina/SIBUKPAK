<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfilController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        return view('mahasiswa.profil', compact('mahasiswa'));
    }

    /**
     * Update data non-krusial: nama, no. WhatsApp, jenis kelamin.
     * Field krusial (NIM, tanggal magang, kode kelompok, dll) TIDAK
     * bisa diubah dari sini — hanya lewat panel admin.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'nomor_hp'      => ['required', 'string', 'max:20'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update(['name' => $data['name']]);

        $user->mahasiswa->update([
            'nomor_hp'      => $data['nomor_hp'],
            'jenis_kelamin' => $data['jenis_kelamin'],
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($request->current_password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }                   
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}