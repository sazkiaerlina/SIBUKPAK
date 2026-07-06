@extends('layouts.admin')

@section('content')

<h3 class="mb-4">
    Rekap Presensi
</h3>

{{-- ═══ FILTER & PENCARIAN ═══ --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <div class="row g-2 align-items-end mb-1">

            {{-- Pencarian Nama/NIM --}}
            <div class="col-12 col-md-4">
                <form method="GET" action="{{ url('/admin/rekap') }}">
                    <input type="hidden" name="periode" value="{{ $periode }}">
                    @if($periode == 'custom')
                        <input type="hidden" name="tanggal_dari" value="{{ $tanggalDari }}">
                        <input type="hidden" name="tanggal_sampai" value="{{ $tanggalSampai }}">
                    @endif

                    <label class="form-label small text-muted mb-1">Cari Mahasiswa</label>
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control"
                               placeholder="Nama atau NIM..." value="{{ $keyword }}">
                        <button class="btn btn-primary">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- Filter Periode + Reset (sejajar) --}}
            <div class="col-12 col-md-4">
                <label class="form-label small text-muted mb-1">Periode</label>
                <div class="d-flex gap-2">
                    <form method="GET" action="{{ url('/admin/rekap') }}" id="form-periode" class="flex-grow-1">
                        <input type="hidden" name="keyword" value="{{ $keyword }}">
                        <select name="periode" class="form-select" onchange="document.getElementById('form-periode').submit()">
                            <option value="hari_ini" {{ $periode == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="bulan"    {{ $periode == 'bulan'    ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="custom"   {{ $periode == 'custom'   ? 'selected' : '' }}>Pilih Rentang Tanggal</option>
                            <option value="semua"    {{ $periode == 'semua'    ? 'selected' : '' }}>Semua Data</option>
                        </select>
                    </form>

                    @if($keyword || $periode !== 'hari_ini')
                        <a href="{{ url('/admin/rekap') }}" class="btn btn-outline-secondary flex-shrink-0" title="Reset Filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Export CSV (kanan) --}}
            <div class="col-12 col-md-4 d-flex justify-content-md-end">
                <a href="{{ route('admin.rekap.export', request()->query()) }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </div>

        </div>

        {{-- Baris kedua: Rentang Tanggal — muncul HANYA kalau periode = custom --}}
        @if($periode == 'custom')
        <div class="row g-2 align-items-end mt-1">
            <form method="GET" action="{{ url('/admin/rekap') }}" class="row g-2 align-items-end w-100">
                <input type="hidden" name="periode" value="custom">
                <input type="hidden" name="keyword" value="{{ $keyword }}">

                <div class="col-6 col-md-3">
                    <label class="form-label small text-muted mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_dari" value="{{ $tanggalDari }}" class="form-control">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small text-muted mb-1">Sampai Tanggal</label>
                    <input type="date" name="tanggal_sampai" value="{{ $tanggalSampai }}" class="form-control">
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
</div>
{{-- ═══ AKHIR FILTER ═══ --}}

<div class="card card-dashboard mt-4">

    <div class="card-header text-white"
        style="background:#043277;">

        Data Presensi

    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">

            <thead class="table-primary">

                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody>

                @forelse($presensiRekap as $item)

                <tr>

                    <td>
                        {{ ($presensiRekap->currentPage()-1) * $presensiRekap->perPage() + $loop->iteration }}
                    </td>

                    <td>{{ $item->mahasiswa->user->name }}</td>

                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>

                    <td>{{ $item->jam_masuk ?? '-' }}</td>

                    <td>{{ $item->jam_pulang ?? '-' }}</td>

                    <td>

                        @if($item->status == 'hadir')

                            <span class="badge bg-success">Hadir</span>

                        @elseif($item->status == 'alpa')

                            <span class="badge bg-danger">Alpa</span>

                        @elseif($item->status == 'izin')

                            <span class="badge bg-warning text-dark">Izin</span>

                        @elseif($item->status == 'sakit')

                            <span class="badge bg-info text-dark">Sakit</span>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center">

                        Belum ada data presensi untuk periode/kata kunci ini.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

        <div class="d-flex justify-content-end mt-3">

            {{ $presensiRekap->links() }}

        </div>

    </div>
</div>

@endsection