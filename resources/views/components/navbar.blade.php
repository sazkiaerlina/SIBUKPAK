<nav x-data="{ open: false }" class="max-w-7xl mx-auto mt-6">

    <div class="bg-[#043277] rounded-2xl px-6 py-4">

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
                        href="/login"
                        class="inline-block bg-white text-[#043277] px-5 py-2 rounded-lg">

                        Login

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>