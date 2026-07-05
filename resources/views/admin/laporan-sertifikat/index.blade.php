@extends('layouts.admin')

@section('title', 'Laporan & Sertifikat')

@section('content')

<h4 class="fw-bold mb-1"><i class="bi bi-folder text-warning"></i> Laporan & Sertifikat Mahasiswa</h4>
<p class="text-muted mb-4">Isi nomor surat untuk menerbitkan sertifikat — mahasiswa bisa langsung mengunduhnya setelah tersimpan.</p>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nama</th>
                        <th>Asal Kampus/Sekolah</th>
                        <th>Laporan</th>
                        <th style="width: 220px;">Nomor Surat</th>
                        <th style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftar as $item)
                    @php $cert = $item->user->certificate; @endphp
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $item->user->name }}</td>
                        <td class="text-muted">{{ $item->universitas }}</td>
                        <td>
                            <a href="{{ Storage::url($item->laporan_path) }}" target="_blank" class="text-decoration-none">
                                <i class="bi bi-file-earmark-pdf text-danger"></i> Lihat PDF
                            </a>
                        </td>

                        <td>
                            <form method="POST" action="{{ route('admin.laporan.sertifikat.simpan', $item->id) }}"
                                  class="d-flex align-items-center gap-1">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="nomor_surat" value="{{ $cert->nomor_surat ?? '' }}"
                                    placeholder="Contoh: 123/BPS-OI/VII/2026"
                                    class="form-control form-control-sm">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                        </td>

                        <td>
                            @if($cert && filled($cert->nomor_surat))
                                <a href="{{ route('admin.laporan.sertifikat.download', $item->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i> Sertifikat
                                </a>
                            @else
                                <span class="text-muted small fst-italic">Isi nomor surat</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
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