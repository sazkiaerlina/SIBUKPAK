@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h3 class="mb-0">
        Detail Pendaftar
    </h3>

    <a href="{{ route('admin.verifikasi.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

{{-- ═══════════════ TOMBOL AKSI / STATUS ═══════════════ --}}
<div class="card card-dashboard mb-4">

    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <span class="fw-bold" style="color:#043277;">
                {{ $mahasiswa->user->name }}
            </span>
            <span class="text-muted ms-2">
                ({{ $mahasiswa->isKelompok() ? 'Ketua Kelompok' : 'Individu' }})
            </span>
        </div>

        @if($mahasiswa->status_pendaftaran == 'pending')

            <div class="d-flex gap-2">

                <form action="{{ route('admin.verifikasi.approve', $mahasiswa->id) }}"
                      method="POST"
                      class="form-approve">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Terima
                    </button>
                </form>

                <button type="button"
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTolak">
                    <i class="bi bi-x-circle"></i> Tolak
                </button>

            </div>

        @elseif($mahasiswa->status_pendaftaran == 'diterima')

            <span class="badge bg-success fs-6">Diterima</span>

        @else

            <span class="badge bg-danger fs-6">Ditolak</span>

        @endif

    </div>

</div>

{{-- ═══════════════ MODAL TOLAK ═══════════════ --}}
<div class="modal fade" id="modalTolak" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.verifikasi.reject', $mahasiswa->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pendaftaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="catatan" class="form-control" rows="3"
                        placeholder="Alasan penolakan, akan bisa dilihat mahasiswa (opsional)"></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- ═══════════════ INFORMASI PERMOHONAN ═══════════════ --}}
<div class="card card-dashboard mb-4">

    <div class="card-header text-white" style="background:#043277;">
        Informasi Permohonan
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="text-muted small">Kategori Pemohon</div>
                <div class="fw-semibold">{{ ucfirst($mahasiswa->kategori_pemohon) }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="text-muted small">Jenis Instansi</div>
                <div class="fw-semibold">
                    {{ $mahasiswa->jenis_instansi == 'perguruan_tinggi' ? 'Perguruan Tinggi' : 'SMK' }}
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════ DATA PESERTA (KETUA / INDIVIDU) ═══════════════ --}}
<div class="card card-dashboard mb-4">

    <div class="card-header text-white" style="background:#043277;">
        Data Peserta
        @if($mahasiswa->isKelompok())
            <span class="fw-normal small">(Ketua Kelompok)</span>
        @endif
    </div>

    <div class="card-body">
        <div class="row">

            <div class="col-md-6 mb-3">
                <div class="text-muted small">Nama Lengkap</div>
                <div class="fw-semibold">{{ $mahasiswa->user->name }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="text-muted small">Email</div>
                <div class="fw-semibold">{{ $mahasiswa->user->email }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="text-muted small">No. WhatsApp</div>
                <div class="fw-semibold">{{ $mahasiswa->nomor_hp ?? '-' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="text-muted small">Jenis Kelamin</div>
                <div class="fw-semibold">
                    {{ $mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($mahasiswa->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="text-muted small">Asal Instansi</div>
                <div class="fw-semibold">{{ $mahasiswa->universitas }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="text-muted small">
                    {{ $mahasiswa->jenis_instansi == 'perguruan_tinggi' ? 'NIM' : 'NISN' }}
                </div>
                <div class="fw-semibold">{{ $mahasiswa->nim ?? '-' }}</div>
            </div>

            @if($mahasiswa->jenis_instansi == 'perguruan_tinggi')

                <div class="col-md-6 mb-3">
                    <div class="text-muted small">Fakultas</div>
                    <div class="fw-semibold">{{ $mahasiswa->fakultas ?? '-' }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="text-muted small">Program Studi</div>
                    <div class="fw-semibold">{{ $mahasiswa->prodi ?? '-' }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="text-muted small">Jurusan</div>
                    <div class="fw-semibold">{{ $mahasiswa->jurusan ?? '-' }}</div>
                </div>

            @else

                <div class="col-md-6 mb-3">
                    <div class="text-muted small">Kelas</div>
                    <div class="fw-semibold">{{ $mahasiswa->kelas ?? '-' }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="text-muted small">Kompetensi Keahlian</div>
                    <div class="fw-semibold">{{ $mahasiswa->kompetensi_keahlian ?? '-' }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="text-muted small">Jurusan</div>
                    <div class="fw-semibold">{{ $mahasiswa->jurusan ?? '-' }}</div>
                </div>

            @endif

            <div class="col-md-6 mb-3">
                <div class="text-muted small">Tanggal Mulai Magang</div>
                <div class="fw-semibold">{{ $mahasiswa->tanggal_mulai->format('d-m-Y') }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="text-muted small">Tanggal Selesai Magang</div>
                <div class="fw-semibold">{{ $mahasiswa->tanggal_selesai->format('d-m-Y') }}</div>
            </div>

        </div>
    </div>

</div>

{{-- ═══════════════ ANGGOTA KELOMPOK (KALAU ADA) ═══════════════ --}}
@if($mahasiswa->isKelompok() && $anggota->count() > 0)

<div class="card card-dashboard mb-4">

    <div class="card-header text-white" style="background:#043277;">
        Data Anggota Kelompok ({{ $anggota->count() }} orang)
    </div>

    <div class="card-body">

        @foreach($anggota as $index => $item)

        <div class="border rounded-3 p-3 mb-3 {{ $loop->last ? '' : '' }}">

            <div class="fw-bold mb-2" style="color:#043277;">
                Anggota {{ $index + 1 }} — {{ $item->user->name }}
            </div>

            <div class="row">

                <div class="col-md-6 mb-2">
                    <div class="text-muted small">Email</div>
                    <div>{{ $item->user->email }}</div>
                </div>

                <div class="col-md-6 mb-2">
                    <div class="text-muted small">No. WhatsApp</div>
                    <div>{{ $item->nomor_hp ?? '-' }}</div>
                </div>

                <div class="col-md-6 mb-2">
                    <div class="text-muted small">Asal Instansi</div>
                    <div>{{ $item->universitas }}</div>
                </div>

                <div class="col-md-6 mb-2">
                    <div class="text-muted small">
                        {{ $item->jenis_instansi == 'perguruan_tinggi' ? 'NIM' : 'NISN' }}
                    </div>
                    <div>{{ $item->nim ?? '-' }}</div>
                </div>

                <div class="col-md-6 mb-2">
                    <div class="text-muted small">Jurusan</div>
                    <div>{{ $item->jurusan ?? '-' }}</div>
                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endif

{{-- ═══════════════ BERKAS TERUNGGAH ═══════════════ --}}
<div class="card card-dashboard mb-4">

    <div class="card-header text-white" style="background:#043277;">
        Berkas Terunggah
    </div>

    <div class="card-body">

        <div class="d-flex align-items-center gap-3 border rounded-3 p-3 mb-2">
            <i class="bi bi-file-earmark-text fs-3" style="color:#043277;"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold">Surat Pengantar</div>
                @if($mahasiswa->surat_pengantar_path)
                    <a href="{{ asset('storage/'.$mahasiswa->surat_pengantar_path) }}" target="_blank" class="small">
                        Klik untuk melihat file
                    </a>
                @else
                    <span class="text-muted small">Belum ada file</span>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 border rounded-3 p-3">
            <i class="bi bi-file-earmark-text fs-3" style="color:#043277;"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold">Proposal Magang</div>
                @if($mahasiswa->proposal_path)
                    <a href="{{ asset('storage/'.$mahasiswa->proposal_path) }}" target="_blank" class="small">
                        Klik untuk melihat file
                    </a>
                @else
                    <span class="text-muted small">Belum ada file</span>
                @endif
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>

document.querySelector('.form-approve')?.addEventListener('submit', function(e){

    e.preventDefault();
    const form = this;

    Swal.fire({
        title: 'Terima Pendaftaran?',
        text: 'Akun pendaftar (dan seluruh anggota kelompok jika ada) akan langsung aktif.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Terima',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

});

</script>
@endpush