<nav

x-data="{ open:false }"
id="navbar"
class="fixed top-0 w-full z-50 transition-transform duration-300 overflow-x-hidden">

<div class="w-full max-w-7xl mx-auto mt-3 px-4">
    <div class="w-full bg-[#043277] rounded-2xl px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
    <img
        src="{{ asset('assets/images/logoSIBUKPAK.png') }}"
        alt="Logo SIBUKPAK"
        class="h-10 w-auto">
    <h1 class="text-white font-bold text-2xl">
        PANDU
    </h1>
</div>
            <ul class="hidden md:flex gap-8 text-white items-center">
                <li>
                    <a href="#home"
                        @click.prevent="document.querySelector('#home').scrollIntoView({ behavior: 'smooth' });"
                        class="hover:text-blue-200">
                        Home
                    </a>
                </li>
                <li>
                    <a href="#profil"
                        @click.prevent="document.querySelector('#profil').scrollIntoView({ behavior: 'smooth' });"
                        class="hover:text-blue-200">
                        Profil
                    </a>
                </li>
                <li>
                    <a href="#alur"
                        @click.prevent="document.querySelector('#alur').scrollIntoView({ behavior: 'smooth' });"
                        class="hover:text-blue-200">
                        Alur Pendaftaran
                    </a>
                </li>
                <li>
                    <a href="#formasi"
                        @click.prevent="document.querySelector('#formasi').scrollIntoView({ behavior: 'smooth' });"
                        class="hover:text-blue-200">
                        Informasi Magang
                    </a>
                </li>
                <li>
                    <a href="{{ asset('storage/panduan/panduan-mahasiswa.pdf') }}" target="_blank" class="hover:text-blue-200">Panduan</a></li>

            </ul>

            <div class="hidden md:block">
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.home') }}" class="bg-white text-[#043277] font-semibold px-5 py-2 rounded-lg hover:bg-gray-100 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('mahasiswa.dashboard') }}" class="bg-white text-[#043277] font-semibold px-5 py-2 rounded-lg hover:bg-gray-100 transition">
                            Dashboard
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
            class="md:hidden mt-5 border-t border-[#043277] pt-4">
        
            <ul class="space-y-2 text-white">
                <li>
                    <a href="#home"
                        @click.prevent="document.querySelector('#home').scrollIntoView({ behavior: 'smooth' }); open = false;"
                        class="block hover:text-blue-200">
                        Home
                    </a>
                </li>
                <li>
                    <a href="#profil"
                        @click.prevent="document.querySelector('#profil').scrollIntoView({ behavior: 'smooth' }); open = false;"
                        class="block hover:text-blue-200">
                        Profil
                    </a>
                </li>
                <li>
                    <a href="#alur"
                        @click.prevent="document.querySelector('#alur').scrollIntoView({ behavior: 'smooth' }); open = false;"
                        class="block hover:text-blue-200">
                        Alur Pendaftaran
                    </a>
                </li>
                <li>
                    <a href="#formasi"
                        @click.prevent="document.querySelector('#formasi').scrollIntoView({ behavior: 'smooth' }); open = false;"
                        class="block hover:text-blue-200">
                        Informasi Magang
                    </a>
                </li>
                <li><a href="{{ route('mahasiswa.panduan.show') }}" target="_blank" class="block hover:text-blue-200">Panduan</a></li>
                <li class="pt-2">
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.home') }}" class="block text-center bg-white text-[#043277] font-semibold px-5 py-2 rounded-lg hover:bg-gray-100 transition w-full">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('mahasiswa.dashboard') }}" class="block text-center bg-white text-[#043277] font-semibold px-5 py-2 rounded-lg hover:bg-gray-100 transition w-full">
                                Dashboard
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