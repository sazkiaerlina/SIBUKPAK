@extends('layouts.admin')

@section('title', 'Kelola Buku Panduan')

@section('content')

<div class="container" style="max-width:700px">

    <h3 class="fw-bold mb-4" style="color:#043277;">
        <i class="bi bi-book"></i> Kelola Buku Panduan
    </h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <p class="text-muted mb-4">
        Upload file PDF di bawah untuk mengganti buku panduan yang tampil di halaman
        mahasiswa/admin. File lama akan otomatis tertimpa oleh file baru.
    </p>

    {{-- ================= PANDUAN MAHASISWA ================= --}}
    <div class="card card-dashboard mb-4">

        <div class="card-header text-white" style="background:#043277;">
            <strong>Buku Panduan Mahasiswa</strong>
        </div>

        <div class="card-body">

            <p class="mb-3">
                Status:
                @if($adaMahasiswa)
                    <span class="badge bg-success">Sudah ada file</span>
                    <a href="{{ route('admin.panduan.mahasiswa.show') }}" target="_blank" class="ms-2 small">
                        Lihat file saat ini
                    </a>
                @else
                    <span class="badge bg-secondary">Belum ada file</span>
                @endif
            </p>

            <form method="POST" action="{{ route('admin.panduan.mahasiswa') }}" enctype="multipart/form-data">
    @csrf

    <div class="d-flex gap-2">
        <input type="file" name="file" accept=".pdf" class="form-control" required>

        <button type="submit" class="btn text-white" style="background:#043277; white-space:nowrap;">
            Upload
        </button>
    </div>

    <small class="text-muted">
        Format: PDF • Maks. ukuran file 5 MB
    </small>
</form>

        </div>

    </div>

    {{-- ================= PANDUAN ADMIN ================= --}}
    <div class="card card-dashboard">

        <div class="card-header text-white" style="background:#043277;">
            <strong>Buku Panduan Admin</strong>
        </div>

        <div class="card-body">

            <p class="mb-3">
                Status:
                @if($adaAdmin)
                    <span class="badge bg-success">Sudah ada file</span>
                    <a href="{{ route('admin.panduan.admin.show') }}" target="_blank" class="ms-2 small">
                        Lihat file saat ini
                    </a>
                @else
                    <span class="badge bg-secondary">Belum ada file</span>
                @endif
            </p>

            <form method="POST" action="{{ route('admin.panduan.admin') }}" enctype="multipart/form-data">
    @csrf

    <div class="d-flex gap-2">
        <input type="file" name="file" accept=".pdf" class="form-control" required>

        <button type="submit" class="btn text-white" style="background:#043277; white-space:nowrap;">
            Upload
        </button>
    </div>

    <small class="text-muted">
        Format: PDF • Maks. ukuran file 5 MB
    </small>
</form>

        </div>

    </div>

</div>

@endsection