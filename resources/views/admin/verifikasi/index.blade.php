@extends('layouts.admin')

@section('content')

<h3 class="mb-4">
    Kelola Pendaftar
</h3>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card card-dashboard">

    <div class="card-body">

        @forelse($pendaftaran as $item)

        <a
            href="{{ route('admin.verifikasi.show', $item->id) }}"
            class="text-decoration-none text-dark"
        >
            <div class="d-flex justify-content-between align-items-center bg-white rounded-4 shadow-sm px-4 py-3 mb-3"
                 style="border:1px solid #e5e9f2; transition:.15s;"
                 onmouseover="this.style.boxShadow='0 4px 14px rgba(4,50,119,.12)'"
                 onmouseout="this.style.boxShadow=''"
            >

                <div>
                    <span class="fw-bold text-uppercase" style="color:#043277;">
                        {{ $item->user->name }}
                    </span>

                    @if($item->isKelompok())
                        <span class="badge bg-info text-dark ms-2">
                            Kelompok
                        </span>
                    @else
                        <span class="badge bg-secondary ms-2">
                            Individu
                        </span>
                    @endif

                    <div class="text-muted small mt-1">
                        {{ $item->jenis_instansi == 'perguruan_tinggi' ? '🎓 Perguruan Tinggi' : '🏫 SMK' }}
                        — {{ $item->universitas }}
                    </div>
                </div>

                <div class="text-end">

                    @if($item->status_pendaftaran == 'pending')
                        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                    @elseif($item->status_pendaftaran == 'diterima')
                        <span class="badge bg-success">Diterima</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif

                    <div class="text-muted small mt-1">
                        {{ optional($item->created_at)->format('d-m-Y H:i') ?? '-' }}
                    </div>

                </div>

            </div>
        </a>

        @empty

        <p class="text-center text-muted py-4">
            Belum ada pendaftar magang.
        </p>

        @endforelse

        <div class="d-flex justify-content-end mt-3">
            {{ $pendaftaran->links() }}
        </div>

    </div>

</div>

@endsection