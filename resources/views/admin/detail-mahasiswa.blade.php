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
                <th>Prodi</th>
                <td>{{ $mahasiswa->prodi }}</td>
            </tr>

             <tr>
                <th>Fakultas</th>
                <td>{{ $mahasiswa->fakultas }}</td>
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

            <tr>
                <th>Laporan Akhir</th>
                <td>
                    @if($mahasiswa->laporan_path)
                        <a href={{ route('admin.laporan.laporan.show', $mahasiswa) }} target="_blank" class="text-decoration-none">
                            <i class="bi bi-file-earmark-pdf text-danger"></i> Lihat Laporan
                        </a>
                        <span class="text-muted small ms-2">
                            (diunggah {{ \Carbon\Carbon::parse($mahasiswa->laporan_uploaded_at)->translatedFormat('d M Y') }})
                        </span>
                    @else
                        <span class="badge bg-secondary">Belum unggah</span>
                    @endif
                </td>
            </tr>

            <tr>
                <th>Sertifikat</th>
                <td>

                    @if($mahasiswa->sertifikat_path)

                        <span class="badge bg-success mb-2">
                            Sertifikat sudah diunggah
                        </span>

                        <br>

                        <a href="{{ route('admin.laporan.sertifikat.download', $mahasiswa->id) }}"
                        class="btn btn-sm btn-outline-primary mt-1">

                            <i class="bi bi-download"></i>

                            Lihat Sertifikat

                        </a>

                    @else

                        <span class="badge bg-secondary">
                            Belum diunggah
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