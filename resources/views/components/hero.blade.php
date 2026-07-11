<section id="home" class="max-w-7xl mx-auto px-6 pt-36 pb-20">

    <div class="flex flex-col lg:flex-row items-center justify-between gap-12 max-w-7xl mx-auto mt-3 px-4 sm:px-6 lg:px-8">

        <!-- Gambar -->
        <div class="w-full lg:w-1/2 flex justify-center lg:justify-start">

            <img
                src="{{ asset('assets/images/kantor_bps.png') }}"
                alt="Kantor BPS"
                class="w-[320px] lg:w-[400px] xl:w-[460px] h-auto">

        </div>

        <!-- Teks -->
        <div class="w-full lg:w-1/2 text-center lg:text-left flex flex-col items-center lg:items-start">

           
<div class="flex items-center justify-center lg:justify-start gap-4 mb-6">

    <img
        src="{{ asset('assets/images/logoSIBUKPAK.png') }}"
        alt="Logo SIBUKPAK"
        class="h-16 lg:h-20 w-auto">

    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-black">
        SIBUKPAK
    </h1>

</div>


            <p class="text-lg sm:text-xl text-gray-700 leading-8 sm:leading-9 max-w-xl mx-auto lg:mx-0">
                Statistik Berdampak Untuk Kampus Berdampak (SIBUKPAK) 
                merupakan platform Badan Pusat Statistik Kabupaten Ogan Ilir
                yang mendukung digitalisasi proses magang mahasiswa. Melalui website ini, 
                mahasiswa dapat melakukan pendaftaran magang secara online dan 
                melakukan absensi harian secara mudah setelah diterima sebagai peserta magang di BPS Kabupaten Ogan Ilir.
            </p>

            <a
                href="{{ route('register') }}"
                class="inline-block mt-10 bg-[#043277] hover:bg-[#0D3D88] text-white text-xl px-14 py-5 rounded-2xl shadow-lg transition">

                Daftar Magang

            </a>

        </div>

    </div>

</section>