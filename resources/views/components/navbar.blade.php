<nav
x-data="{ open:false }"
id="navbar"
class="fixed top-0 left-0 right-0 z-50 transition-transform duration-300 max-w-6xl mx-auto mt-3 px-1">

    <div class="max-w-5x1 mx-auto mt-0 bg-[#043277] rounded-2xl px-6 py-4">
        <div class="flex justify-between items-center">

            
            <div class="flex items-center gap-3">
    <img
        src="{{ asset('assets/images/logoSIBUKPAK.PNG') }}"
        alt="Logo SIBUKPAK"
        class="h-10 w-auto">
    <h1 class="text-white font-bold text-2xl">
        SIBUKPAK
    </h1>
</div>

            <ul class="hidden md:flex gap-8 text-white items-center">
                <li><a href="#home" class="hover:text-blue-200">Home</a></li>
                <li><a href="#profil" class="hover:text-blue-200">Profil</a></li>
                <li><a href="#alur" class="hover:text-blue-200">Alur Pendaftaran</a></li>
            </ul>

            <div class="hidden md:block">
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.home') }}" class="bg-white text-[#043277] font-semibold px-5 py-2 rounded-lg hover:bg-gray-100 transition">
                            Login
                        </a>
                    @else
                        <a href="{{ route('mahasiswa.dashboard') }}" class="bg-white text-[#043277] font-semibold px-5 py-2 rounded-lg hover:bg-gray-100 transition">
                            Login
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bg-white text-[#043277] font-semibold px-5 py-2 rounded-lg hover:bg-gray-100 transition">
                        Login
                    </a>
                @endauth
            </div>

            <button
                @click="open = !open"
                class="text-white text-3xl md:hidden focus:outline-none">
                ☰
            </button>

        </div>

        <div
            x-show="open"
            x-transition
            class="md:hidden mt-5 border-t border-blue-400 pt-4">

            <ul class="space-y-4 text-white">
                <li><a href="#home" class="block hover:text-blue-200">Home</a></li>
                <li><a href="#profil" class="block hover:text-blue-200">Profil</a></li>
                <li><a href="#alur" class="block hover:text-blue-200">Alur Pendaftaran</a></li>
                
                <li class="pt-2">
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.home') }}" class="block text-center bg-white text-[#043277] font-semibold px-5 py-2 rounded-lg hover:bg-gray-100 transition w-full">
                                Dashboard Admin
                            </a>
                        @else
                            <a href="{{ route('mahasiswa.dashboard') }}" class="block text-center bg-white text-[#043277] font-semibold px-5 py-2 rounded-lg hover:bg-gray-100 transition w-full">
                                Dashboard Mahasiswa
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block text-center bg-white text-[#043277] font-semibold px-5 py-2 rounded-lg hover:bg-gray-100 transition w-full">
                            Login
                        </a>
                    @endauth
                </li>
            </ul>

        </div>
    </div>
</nav>

<script>
let lastScroll = 0;
const navbar = document.getElementById('navbar');

window.addEventListener('scroll', function(){
    let currentScroll = window.pageYOffset;
    if(currentScroll <= 0){
        navbar.style.transform = "translateY(0)";
        return;
    }
    if(currentScroll > lastScroll){
        // Scroll ke bawah
        navbar.style.transform = "translateY(-120%)";
    }else{
        // Scroll ke atas
        navbar.style.transform = "translateY(0)";
    }
    lastScroll = currentScroll;
});
</script>