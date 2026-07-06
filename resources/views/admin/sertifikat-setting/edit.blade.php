@extends('layouts.admin')

@section('title', 'Pengaturan Sertifikat')

@section('content')

<h4 class="fw-bold mb-1"> Pengaturan Template Sertifikat</h4>
<p class="text-muted mb-4">Ganti background sertifikat dan atur posisi nama & nomor surat. Gunakan tombol Preview untuk cek hasil.</p>

<form method="POST" action="{{ route('admin.sertifikat-setting.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- ═══ BACKGROUND ═══ --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white fw-bold">Background Sertifikat</div>
        <div class="card-body">
            @if($setting->background_path)
                <p class="text-muted small mb-2">Background saat ini:</p>
                <img src="{{ Storage::url($setting->background_path) }}" class="img-fluid rounded border mb-3" style="max-width: 400px;">
            @else
                <p class="text-muted fst-italic">Belum ada background diunggah.</p>
            @endif

            <label class="form-label fw-semibold small">Ganti Background (opsional)</label>
            <input type="file" name="background" accept=".jpg,.jpeg,.png" class="form-control">
            <div class="form-text">Format JPG/PNG, maksimal 5MB. Disarankan rasio landscape (≈297x210mm).</div>
        </div>
    </div>

    {{-- ═══ POSISI NAMA ═══ --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white fw-bold">Posisi Nama Mahasiswa</div>
        <div class="card-body row g-3">
            <div class="col-md-3">
                <label class="form-label small">Jarak dari Atas (%)</label>
                <input type="number" name="nama_top" value="{{ old('nama_top', $setting->nama_top) }}" step="0.1" min="0" max="100" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Jarak dari Kiri (%)</label>
                <input type="number" name="nama_left" value="{{ old('nama_left', $setting->nama_left) }}" step="0.1" min="0" max="100" class="form-control">
                <div class="form-text">50 = tengah horizontal</div>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Ukuran Font (pt)</label>
                <input type="number" name="nama_font_size" value="{{ old('nama_font_size', $setting->nama_font_size) }}" min="8" max="100" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Warna Teks</label>
                <input type="color" name="nama_color" value="{{ old('nama_color', $setting->nama_color) }}" class="form-control form-control-color w-100">
            </div>
        </div>
    </div>

    {{-- ═══ POSISI NOMOR SURAT ═══ --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white fw-bold">Posisi Nomor Surat</div>
        <div class="card-body row g-3">
            <div class="col-md-3">
                <label class="form-label small">Jarak dari Atas (%)</label>
                <input type="number" name="nomor_top" value="{{ old('nomor_top', $setting->nomor_top) }}" step="0.1" min="0" max="100" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Jarak dari Kiri (%)</label>
                <input type="number" name="nomor_left" value="{{ old('nomor_left', $setting->nomor_left) }}" step="0.1" min="0" max="100" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Ukuran Font (pt)</label>
                <input type="number" name="nomor_font_size" value="{{ old('nomor_font_size', $setting->nomor_font_size) }}" min="6" max="50" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Warna Teks</label>
                <input type="color" name="nomor_color" value="{{ old('nomor_color', $setting->nomor_color) }}" class="form-control form-control-color w-100">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Simpan Pengaturan
    </button>
    <a href="{{ route('admin.sertifikat-setting.preview') }}" target="_blank" class="btn btn-outline-secondary">
        <i class="bi bi-eye"></i> Lihat Preview
    </a>
</form>

@endsection