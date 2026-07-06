@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')

<div class="container" style="max-width:700px">

    <h3 class="fw-bold mb-4" style="color:#043277;">
        <i class="bi bi-person-circle"></i> Profil Admin
    </h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= GANTI EMAIL ================= --}}
    <div class="card card-dashboard mb-4">

        <div class="card-header text-white" style="background:#043277;">
            <strong>Ganti Email</strong>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('admin.profil.email') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Email Saat Ini
                    </label>

                    <input
                        type="email"
                        name="current_email"
                        value="{{ auth()->user()->email }}"
                        class="form-control @error('current_email') is-invalid @enderror">

                    @error('current_email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Email Baru
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror">

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <button class="btn text-white" style="background:#043277;">
                    Simpan Email
                </button>

            </form>

        </div>

    </div>

    {{-- ================= PASSWORD ================= --}}
    <div class="card card-dashboard">

        <div class="card-header text-white" style="background:#043277;">
            <strong>Ganti Password</strong>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('admin.profil.password') }}">
                @csrf
                @method('PUT')

                {{-- Password lama --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Password Saat Ini
                    </label>

                    <div class="position-relative">

                        <input
                            id="current_password"
                            type="password"
                            name="current_password"
                            class="form-control pe-5 @error('current_password') is-invalid @enderror">

                        <button
                            type="button"
                            onclick="togglePassword('current_password','icon1')"
                            class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0 bg-transparent"
                            style="z-index:5;">

                            <img id="icon1" src="{{ asset('assets/images/hidden.png') }}" class="w-6" style="width:22px;height:22px;">

                        </button>

                    </div>

                    @error('current_password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Password baru --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Password Baru
                    </label>

                    <div class="position-relative">

                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control pe-5 @error('password') is-invalid @enderror">

                        <button
                            type="button"
                            onclick="togglePassword('password','icon2')"
                            class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0 bg-transparent"
                            style="z-index:5;">

                            <img id="icon2" src="{{ asset('assets/images/hidden.png') }}" style="width:22px;height:22px;">

                        </button>

                    </div>

                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Konfirmasi (tanpa toggle mata) --}}
                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control">

                </div>

                <button class="btn text-white" style="background:#043277;">
                    Ubah Password
                </button>

            </form>

        </div>

    </div>

</div>

<script>

function togglePassword(inputId, iconId){

    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if(input.type === "password"){

        input.type = "text";

        icon.src = "{{ asset('assets/images/eye.png') }}";

    }else{

        input.type = "password";

        icon.src = "{{ asset('assets/images/hidden.png') }}";

    }

}

</script>

@endsection