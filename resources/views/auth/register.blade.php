<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Akun - SISTEM BPS Ogan Ilir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
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
        PANDU Statistik Berdampak Untuk Kampus Berdampak
    </p>

</div>

</div>
    <!-- ======================= KANAN ======================= -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-blue-50 px-6 py-10">

        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 lg:p-10">

            <h2 class="text-3xl font-bold text-[#043277] text-center">
                BUAT AKUN
            </h2>

            <p class="text-center text-gray-500 mt-2 mb-8">
                Buat akun untuk mendaftar
            </p>

            <form method="POST"
                action="{{ route('register.post') }}"
                class="space-y-5"
                autocomplete="off"
                novalidate
                data-lpignore="true">
                
                @csrf

                <!-- Nama -->
                <div>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Nama"
                        required
                        autofocus
                        class="w-full rounded-full border border-gray-300 px-5 py-3 focus:border-[#043277] focus:ring-[#043277]">

                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

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
                        autocomplete="new-password"
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
                        <p class="absolute left-2 top-full mt-1 text-[10px] text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>
  

                <!-- Konfirmasi Password -->
                <div class="relative mb-10">

                    <input
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Konfirmasi Password"
                        required
                        class="w-full rounded-full border border-gray-300 px-5 py-3 focus:border-[#043277] focus:ring-[#043277]">

                </div>


                <!-- Tombol -->
                <button
                    type="submit"
                    class="w-full bg-[#043277] hover:bg-[#03255a] text-white font-semibold py-3 rounded-full transition duration-300">

                    KIRIM

                </button>

            </form>

            <p class="text-center text-gray-600 mt-8">

                Sudah punya akun?

                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-[#043277] hover:underline">

                    Masuk

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