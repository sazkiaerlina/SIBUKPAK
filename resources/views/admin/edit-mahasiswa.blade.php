@extends('layouts.admin')

@section('content')

<h3 class="mb-4">

Edit Biodata Mahasiswa

</h3>

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
value="{{ old('tanggal_mulai', $mahasiswa->tanggal_mulai) }}">

</div>

<div class="mb-3">

<label class="form-label">

Tanggal Selesai

</label>

<input
type="date"
name="tanggal_selesai"
class="form-control"
value="{{ old('tanggal_selesai', $mahasiswa->tanggal_selesai) }}">

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
{{ $mahasiswa->user->is_active ? 'selected' : '' }}>

Aktif

</option>

<option value="0"
{{ !$mahasiswa->user->is_active ? 'selected' : '' }}>

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



