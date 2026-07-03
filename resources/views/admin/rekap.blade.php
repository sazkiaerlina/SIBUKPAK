@extends('layouts.admin')

@section('content')

<h3 class="mb-4">
    Rekap Presensi
</h3>

<div class="d-flex justify-content-between align-items-center mb-4">

    <h5 class="mb-0">
        Data Presensi
    </h5>

    <form method="GET" action="{{ url('/admin/rekap') }}">

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

            <option value="semua"
            {{ $periode=='semua' ? 'selected' : '' }}>
                Semua Data
            </option>

        </select>

    </form>

</div>

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