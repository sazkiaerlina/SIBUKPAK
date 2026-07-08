<section id="formasi" class="max-w-7xl mx-auto py-20 px-6">

    <div class="text-center mb-14">

        <h2 class="text-4xl font-bold text-[#043277]">
            Informasi Biro 
        </h2>

        <p class="text-blue-600 font-semibold mt-2">
            BPS Kabupaten Ogan Ilir
        </p>

        <p class="text-gray-500 mt-4 max-w-2xl mx-auto leading-7">
            Berikut adalah informasi mengenai fungsi dan peran di BPS Kabupaten Ogan Ilir yang
            dapat menjadi pilihan bidang magang sesuai minat dan latar belakang pendidikan Anda.
        </p>

    </div>

    <div class="grid md:grid-cols-3 gap-8">

        <!-- ========== TATA USAHA ========== -->
        <div
            x-data="{ open: false }"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition"
        >
            <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2">
                Bagian Tata Usaha
            </h3>

            <p class="text-gray-600 leading-7">
                Bagian Tata Usaha bertugas mengelola administrasi internal agar kegiatan
                instansi berjalan lancar, meliputi perencanaan dan pengelolaan anggaran,
                administrasi kepegawaian, pengelolaan sarana dan prasarana, persuratan,
                serta layanan operasional kantor.
            </p>

            <div class="border-t border-gray-100 mt-6 pt-4">
                <button
                    @click="open = !open"
                    class="flex items-center justify-between w-full text-left"
                >
                    <span class="flex items-center gap-2 text-orange-600 font-semibold text-sm">
                        Jurusan yang Diterima
                        <span class="bg-orange-100 text-orange-600 text-xs font-bold px-2 py-0.5 rounded-full">4</span>
                    </span>

                    <svg
                        :class="open ? 'rotate-180' : ''"
                        class="w-5 h-5 text-gray-400 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <ul x-show="open" x-transition x-cloak class="mt-3 space-y-1.5 text-sm text-gray-600 list-disc pl-5">
                    <li>Administrasi Perkantoran</li>
                    <li>Manajemen / Administrasi Bisnis</li>
                    <li>Akuntansi</li>
                    <li>Sekretaris</li>
                </ul>
            </div>
        </div>

        <!-- ========== SPBE ========== -->
        <div
            x-data="{ open: false }"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition"
        >
            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2">
                Fungsi SPBE
            </h3>

            <p class="text-gray-600 leading-7">
                Fungsi Sistem Pemerintahan Berbasis Elektronik (SPBE) bertugas mengelola
                infrastruktur teknologi informasi, pengembangan dan pemeliharaan sistem/aplikasi,
                keamanan data, serta mendukung digitalisasi layanan statistik di lingkungan BPS
                Kabupaten Ogan Ilir.
            </p>

            <div class="border-t border-gray-100 mt-6 pt-4">
                <button
                    @click="open = !open"
                    class="flex items-center justify-between w-full text-left"
                >
                    <span class="flex items-center gap-2 text-blue-600 font-semibold text-sm">
                        Jurusan yang Diterima
                        <span class="bg-blue-100 text-blue-600 text-xs font-bold px-2 py-0.5 rounded-full">5</span>
                    </span>

                    <svg
                        :class="open ? 'rotate-180' : ''"
                        class="w-5 h-5 text-gray-400 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <ul x-show="open" x-transition x-cloak class="mt-3 space-y-1.5 text-sm text-gray-600 list-disc pl-5">
                    <li>Teknik Informatika</li>
                    <li>Sistem Informasi</li>
                    <li>Ilmu Komputer</li>
                    <li>Teknik Komputer / Jaringan</li>
                    <li>Manajemen Informatika</li>
                </ul>
            </div>
        </div>

        <!-- ========== TEKNIS ========== -->
        <div
            x-data="{ open: false }"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition"
        >
            <div class="w-14 h-14 rounded-2xl bg-teal-100 flex items-center justify-center mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2">
                Fungsi Teknis
            </h3>

            <p class="text-gray-600 leading-7">
                Fungsi Teknis bertugas melaksanakan kegiatan pengumpulan, pengolahan, dan
                analisis data statistik di lapangan, mulai dari sensus dan survei, pemeriksaan
                kualitas data, hingga penyajian hasil statistik sesuai standar dan metodologi
                yang berlaku.
            </p>

            <div class="border-t border-gray-100 mt-6 pt-4">
                <button
                    @click="open = !open"
                    class="flex items-center justify-between w-full text-left"
                >
                    <span class="flex items-center gap-2 text-teal-600 font-semibold text-sm">
                        Jurusan yang Diterima
                        <span class="bg-teal-100 text-teal-600 text-xs font-bold px-2 py-0.5 rounded-full">4</span>
                    </span>

                    <svg
                        :class="open ? 'rotate-180' : ''"
                        class="w-5 h-5 text-gray-400 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <ul x-show="open" x-transition x-cloak class="mt-3 space-y-1.5 text-sm text-gray-600 list-disc pl-5">
                    <li>Statistika</li>
                    <li>Matematika</li>
                    <li>Ilmu Ekonomi</li>
                    <li>Geografi</li>
                </ul>
            </div>
        </div>

    </div>

    <!-- ========== BARIS 2: 2 KARTU DI TENGAH ========== -->
    <div class="grid md:grid-cols-2 gap-8 md:w-2/3 mx-auto mt-8">

        <!-- ========== PELAYANAN STATISTIK ========== -->
        <div
            x-data="{ open: false }"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition"
        >
            <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2">
                Fungsi Pelayanan Statistik
            </h3>

            <p class="text-gray-600 leading-7">
                Fungsi Pelayanan Statistik bertugas menyampaikan informasi dan data statistik
                kepada masyarakat, instansi pemerintah, maupun peneliti, meliputi layanan
                perpustakaan data, konsultasi statistik, serta publikasi hasil kegiatan
                BPS Kabupaten Ogan Ilir.
            </p>

            <div class="border-t border-gray-100 mt-6 pt-4">
                <button
                    @click="open = !open"
                    class="flex items-center justify-between w-full text-left"
                >
                    <span class="flex items-center gap-2 text-purple-600 font-semibold text-sm">
                        Jurusan yang Diterima
                        <span class="bg-purple-100 text-purple-600 text-xs font-bold px-2 py-0.5 rounded-full">4</span>
                    </span>

                    <svg
                        :class="open ? 'rotate-180' : ''"
                        class="w-5 h-5 text-gray-400 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <ul x-show="open" x-transition x-cloak class="mt-3 space-y-1.5 text-sm text-gray-600 list-disc pl-5">
                    <li>Statistika</li>
                    <li>Ilmu Ekonomi</li>
                    <li>Ilmu Perpustakaan</li>
                    <li>Administrasi Publik</li>
                </ul>
            </div>
        </div>

        <!-- ========== HUMAS ========== -->
        <div
            x-data="{ open: false }"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition"
        >
            <div class="w-14 h-14 rounded-2xl bg-rose-100 flex items-center justify-center mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2">
                Fungsi Humas
            </h3>

            <p class="text-gray-600 leading-7">
                Fungsi Hubungan Masyarakat (Humas) bertugas mengelola komunikasi dan citra
                instansi, meliputi publikasi kegiatan, pengelolaan media sosial, dokumentasi
                acara, serta layanan informasi publik BPS Kabupaten Ogan Ilir kepada masyarakat.
            </p>

            <div class="border-t border-gray-100 mt-6 pt-4">
                <button
                    @click="open = !open"
                    class="flex items-center justify-between w-full text-left"
                >
                    <span class="flex items-center gap-2 text-rose-600 font-semibold text-sm">
                        Jurusan yang Diterima
                        <span class="bg-rose-100 text-rose-600 text-xs font-bold px-2 py-0.5 rounded-full">4</span>
                    </span>

                    <svg
                        :class="open ? 'rotate-180' : ''"
                        class="w-5 h-5 text-gray-400 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <ul x-show="open" x-transition x-cloak class="mt-3 space-y-1.5 text-sm text-gray-600 list-disc pl-5">
                    <li>Ilmu Komunikasi</li>
                    <li>Hubungan Masyarakat</li>
                    <li>Desain Komunikasi Visual</li>
                    <li>Jurnalistik</li>
                </ul>
            </div>
        </div>

    </div>

</section>