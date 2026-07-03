@extends('layouts.admin')

@section('content')

<h3 class="mb-4">
    Rekap Presensi
</h3>

{{-- batas filter --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ route('rekap.export', request()->query()) }}"
            class="btn btn-success ">

                Export CSV

        </a>


    <form method="GET" action="{{ url('/admin/rekap') }}" class="d-flex gap-2">

        <select
            name="periode"
            class="form-select"
            onchange="this.form.submit()">

            <option value="hari_ini"
            {{ $periode=='hari_ini' ? 'selected' : '' }}>
                Hari Ini
            </option>

            <option value="7_hari"
            {{ $periode=='7_hari' ? 'selected' : '' }}>
                7 Hari Terakhir
            </option>

            <option value="30_hari"
            {{ $periode=='30_hari' ? 'selected' : '' }}>
                30 Hari Terakhir
            </option>

            <option value="bulan"
            {{ $periode=='bulan' ? 'selected' : '' }}>
                Bulan Ini
            </option>

            <option value="tahun"
            {{ $periode=='tahun' ? 'selected' : '' }}>
                Tahun Ini
            </option>

            <option value="custom"
            {{ $periode=='custom' ? 'selected' : '' }}>
                Pilih Bulan & Tahun
            </option>

            <option value="tanggal"
            {{ $periode=='tanggal' ? 'selected' : '' }}>
                Tanggal Tertentu
            </option>

            <option value="semua"
            {{ $periode=='semua' ? 'selected' : '' }}>
                Semua Data
            </option>

        </select>

        @if($periode == 'custom')

            <select
                name="bulan"
                class="form-select"
                onchange="this.form.submit()">

                @php
                    $namaBulan = [
                        1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
                        5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
                        9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
                    ];
                @endphp

                @foreach($namaBulan as $angka => $nama)
                    <option value="{{ $angka }}" {{ $bulan == $angka ? 'selected' : '' }}>
                        {{ $nama }}
                    </option>
                @endforeach

            </select>

            <select
                name="tahun"
                class="form-select"
                onchange="this.form.submit()">

                @for($i = now()->year; $i >= now()->year - 5; $i--)
                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor

            </select>

        @endif

        @if($periode == 'tanggal')

            <input
                type="date"
                name="tanggal_pilih"
                value="{{ $tanggalPilih }}"
                class="form-control"
                onchange="this.form.submit()">

        @endif

    </form>

</div>
{{-- batas filter --}}

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

                        Belum ada data presensi untuk periode ini.

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