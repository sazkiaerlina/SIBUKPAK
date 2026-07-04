<nav
x-data="{ open:false }"
id="navbar"
class="fixed top-0 left-0 right-0 z-50 transition-transform duration-300 max-w-6xl mx-auto mt-3 px-1">


    <div class="max-w-5x1 mx-auto mt-0 bg-[#043277] rounded-2xl px-6 py-4">

        <div class="flex justify-between items-center">

            <h1 class="text-white font-bold text-2xl">
                SIBUKPAK
            </h1>

            <!-- Desktop Menu -->
            <ul class="hidden md:flex gap-8 text-white">

                <li><a href="#home">Home</a></li>
                <li><a href="#profil">Profil</a></li>
                <li><a href="#alur">Alur</a></li>

            </ul>

            <a href="/login"
               class="hidden md:block bg-white px-5 py-2 rounded-lg">

                Login

            </a>

            <!-- Mobile Button -->
            <button
                @click="open = !open"
                class="text-white text-3xl md:hidden">

                ☰

            </button>

        </div>

        <!-- Mobile Menu -->
        <div
            x-show="open"
            x-transition
            class="md:hidden mt-5 border-t border-blue-400 pt-4">

            <ul class="space-y-4 text-white">

                <li><a href="#home">Home</a></li>

                <li><a href="#profil">Profil</a></li>

                <li><a href="#alur">Alur</a></li>

                <li>

                    <a
                        href="{{ route('login') }}"
                        class="inline-block bg-white text-[#043277] px-5 py-2 rounded-lg">

                        Login

                    </a>

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