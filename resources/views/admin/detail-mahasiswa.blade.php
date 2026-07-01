@extends('layouts.admin')

@section('content')

<h3 class="mb-4">
    Biodata Mahasiswa
</h3>

<div class="card card-dashboard">

    <div class="card-body">

        <table class="table">

            <tr>
                <th width="30%">Nama</th>
                <td>{{ $mahasiswa->user->name }}</td>
            </tr>

            <tr>
                <th>NIM</th>
                <td>{{ $mahasiswa->nim }}</td>
            </tr>

            <tr>
                <th>Jurusan</th>
                <td>{{ $mahasiswa->jurusan }}</td>
            </tr>

            <tr>
                <th>Universitas</th>
                <td>{{ $mahasiswa->universitas }}</td>
            </tr>

            <tr>
                <th>Nomor HP</th>
                <td>{{ $mahasiswa->nomor_hp }}</td>
            </tr>

            <tr>
                <th>Tanggal Mulai</th>
                <td>{{ $mahasiswa->tanggal_mulai }}</td>
            </tr>

            <tr>
                <th>Tanggal Selesai</th>
                <td>{{ $mahasiswa->tanggal_selesai }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $mahasiswa->user->email }}</td>
            </tr>

            <tr>
                <th>Status Akun</th>
                <td>

                    @if($mahasiswa->user->is_active)

                        <span class="badge bg-success">
                            Aktif
                        </span>

                    @else

                        <span class="badge bg-danger">
                            Tidak Aktif
                        </span>

                    @endif

                </td>
            </tr>

        </table>

        <a href="{{ url('/admin/mahasiswa') }}" class="btn btn-secondary mt-3">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

</div>

@endsection