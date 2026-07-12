<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Masuk - SISTEM BPS Ogan Ilir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-gray-100">

    <a href="/"
   class="fixed top-6 right-6 z-50
          w-12 h-12
          bg-white rounded-full shadow-lg
          flex items-center justify-center
          text-2xl font-bold text-gray-600
          hover:bg-red-500 hover:text-white
          transition">

    ✕

</a>

<div class="min-h-screen flex flex-col lg:flex-row">

    <!-- ======================= KIRI ======================= -->
<div class="relative w-full lg:w-1/2 min-h-[350px] lg:min-h-screen overflow-hidden">

    <img
    src="{{ asset('assets/images/bg.jpeg') }}"
    alt="Gedung BPS Kabupaten Ogan Ilir"
    class="absolute inset-0 w-full h-full object-cover"
    style="object-position: 65% center;">

    <!-- Overlay Biru -->
    <div class="absolute inset-0 bg-[#043277]/70"></div>

    <!-- Tulisan -->
   <div
    class="absolute z-10"
    style="left: 50px; top: 50%; transform: translateY(-50%);">

    <h1 class="text-white text-4xl lg:text-5xl font-bold leading-tight">
        HALO,<br>
        SELAMAT DATANG DI
    </h1>

    <p class="text-white mt-4 text-lg">
        SIBUKPAK Statistik Berdampak Untuk Kampus Berdampak
    </p>

</div>

</div>
    <!-- ======================= KANAN ======================= -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-blue-50 px-6 py-10">

        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 lg:p-10">

            <h2 class="text-3xl font-bold text-[#043277] text-center">
                MASUK
            </h2>

            <p class="text-center text-gray-500 mt-2 mb-8">
                Masuk untuk melanjutkan
            </p>

            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST"
                action="{{ route('login.post') }}"
                class="space-y-5"
                autocomplete="off">

                @csrf

                <!-- Email -->
                <div>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Email"
                        required
                        class="w-full rounded-full border border-gray-300 px-5 py-3 focus:border-[#043277] focus:ring-[#043277]">

                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="relative mb-5">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Password"
                        required
                        class="w-full rounded-full border border-gray-300 px-5 py-3 pr-14 focus:border-[#043277] focus:ring-[#043277]">

                    <button
                        type="button"
                        onclick="togglePassword('password','iconPassword')"
                        class="absolute right-4 top-1/2 -translate-y-[50%]">

                        <img
                            id="iconPassword"
                            src="{{ asset('assets/images/hidden.png') }}"
                            class="w-6 h-6">

                    </button>

                    @error('password')
                        <p class="absolute left-2 top-full mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <!-- Ingat Saya -->
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember" class="w-4 h-4 text-[#043277] rounded">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                    </div>
                </div>

                <!-- Tombol -->
                <button
                    type="submit"
                    class="w-full bg-[#043277] hover:bg-[#03255a] text-white font-semibold py-3 rounded-full transition duration-300">

                    MASUK

                </button>

            </form>

            <p class="text-center text-gray-600 mt-8">

                Belum punya akun?

                <a
                    href="{{ route('register') }}"
                    class="font-semibold text-[#043277] hover:underline">

                    Daftar

                </a>

            </p>

        
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

</body>
</html>