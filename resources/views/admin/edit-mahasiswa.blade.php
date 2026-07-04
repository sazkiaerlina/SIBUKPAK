@extends('layouts.admin')

@section('content')

<h3 class="mb-4">

Edit Biodata Mahasiswa

</h3>

@if($errors->any())
<div class="alert alert-danger">
    <strong>❌ Gagal menyimpan, periksa kembali:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card card-dashboard">

<div class="card-body">

<form action="{{ url('/admin/mahasiswa/'.$mahasiswa->id) }}" method="POST">

    @csrf
    @method('PUT')

<div class="mb-3">

<label class="form-label">

Nama

</label>

<input
type="text"
name="name"
class="form-control"
value="{{ old('name', $mahasiswa->user->name) }}">

</div>

<div class="mb-3">

<label class="form-label">

NIM

</label>

<input
type="text"
name="nim"
class="form-control"
value="{{ old('nim', $mahasiswa->nim) }}">

</div>

<div class="mb-3">

<label class="form-label">

Jurusan

</label>

<input
type="text"
name="jurusan"
class="form-control"
value="{{ old('jurusan', $mahasiswa->jurusan) }}">

</div>


<div class="mb-3">

<label class="form-label">

Prodi

</label>

<input
type="text"
name="prodi"
class="form-control"
value="{{ old('prodi', $mahasiswa->prodi) }}">

</div>


<div class="mb-3">

<label class="form-label">

Fakultas

</label>

<input
type="text"
name="fakultas"
class="form-control"
value="{{ old('fakultas', $mahasiswa->fakultas) }}">

</div>



<div class="mb-3">

<label class="form-label">

Universitas

</label>

<input
type="text"
name="universitas"
class="form-control"
value="{{ old('universitas', $mahasiswa->universitas) }}">

</div>

<div class="mb-3">

<label class="form-label">

Nomor HP

</label>

<input
type="text"
name="nomor_hp"
class="form-control"
value="{{ old('nomor_hp', $mahasiswa->nomor_hp) }}">

</div>

<div class="mb-3">

<label class="form-label">

Tanggal Mulai

</label>

<input
type="date"
name="tanggal_mulai"
class="form-control"
value="{{ old('tanggal_mulai', optional($mahasiswa->tanggal_mulai)->format('Y-m-d')) }}">

</div>

<div class="mb-3">

<label class="form-label">

Tanggal Selesai

</label>

<input
type="date"
name="tanggal_selesai"
class="form-control"
value="{{ old('tanggal_selesai', optional($mahasiswa->tanggal_selesai)->format('Y-m-d')) }}">

</div>

<div class="mb-3">

<label class="form-label">

Email

</label>

<input
type="email"
name="email"
class="form-control"
value="{{ old('email', $mahasiswa->user->email) }}">

</div>

<div class="mb-3">

<label class="form-label">

Status Akun

</label>

<select
name="is_active"
class="form-select">

<option value="1"
{{ old('is_active', $mahasiswa->user->is_active) == 1 ? 'selected' : '' }}>

Aktif

</option>

<option value="0"
{{ old('is_active', $mahasiswa->user->is_active) == 0 ? 'selected' : '' }}>

Tidak Aktif

</option>

</select>

</div>

<a href="{{ url('/admin/mahasiswa') }}"
class="btn btn-secondary">

Batal

</a>

<button
type="submit"
class="btn btn-primary">

Simpan

</button>

</form>

</div>

</div>

@endsection