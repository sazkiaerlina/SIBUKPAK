<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Presensi Magang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#E3EEFF] flex items-center justify-center px-4">

    <div class="w-full max-w-lg">
        {{-- Logo / Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
                <span class="text-3xl">🎓</span>
            </div>
            <h1 class="text-2xl font-bold text-[#043277]">Presensi Magang</h1>
            <p class="text-blue-200 text-sm mt-1">Masuk untuk melanjutkan</p>
        </div>

        {{-- Card Form --}}
        <div class="bg-white rounded-2xl shadow-2xl p-10">
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

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="nama@email.com"
                        class="w-full px-5 py-4 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full px-5 py-4 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="w-4 h-4 text-blue-600 rounded">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-[#043277] hover:bg-blue-700 active:scale-95 text-white font-semibold py-4 px-5 rounded-xl transition-all duration-150 text-sm"
                >
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-blue-200 text-xs mt-6">
            © {{ date('Y') }} Sistem Presensi Magang
        </p>
    </div>
</body>
</html>