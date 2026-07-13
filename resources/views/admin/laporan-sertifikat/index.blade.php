@extends('layouts.admin')

@section('title', 'Laporan & Sertifikat')

@section('content')

<h4 class="fw-bold mb-1">Laporan & Sertifikat Mahasiswa</h4>
<p class="text-muted mb-4">
    Unggah sertifikat yang telah ditandatangani. Setelah diunggah, mahasiswa dapat langsung mengunduh sertifikat tersebut.
</p>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nama</th>
                        <th>Asal Kampus/Sekolah</th>
                        <th>Laporan</th>
                        <th style="width:260px;">Upload Sertifikat</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($daftar as $item)

                    <tr>

                        <td class="ps-3 fw-semibold">
                            {{ $item->user->name }}
                        </td>

                        <td class="text-muted">
                            {{ $item->universitas }}
                        </td>

                        <td>

                            <a href="{{ route('admin.laporan.laporan.show', $item->id) }}"
                               target="_blank"
                               class="text-decoration-none">

                                <i class="bi bi-file-earmark-pdf text-danger"></i>

                                Lihat PDF

                            </a>

                        </td>

                        <td>

                            <form method="POST"
                                  action="{{ route('admin.laporan.sertifikat.simpan',$item->id) }}"
                                  enctype="multipart/form-data">

                                @csrf
                                @method('PATCH')

                                <input
                                    type="file"
                                    name="sertifikat"
                                    accept=".pdf"
                                    class="form-control form-control-sm mb-2">

                                <button
                                    class="btn btn-success btn-sm w-100">

                                    <i class="bi bi-upload"></i>

                                    Upload Sertifikat

                                </button>

                            </form>

                            @if($item->sertifikat_path)

                                <a href="{{ route('admin.laporan.sertifikat.download',$item->id) }}"
                                   class="btn btn-outline-primary btn-sm w-100 mt-2">

                                    <i class="bi bi-eye"></i>

                                    Lihat Sertifikat

                                </a>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4"
                            class="text-center text-muted py-4">

                            Belum ada mahasiswa yang mengunggah laporan.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>
    </div>
</div>

@endsection