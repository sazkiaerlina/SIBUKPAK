<section id="home" class="max-w-7xl mx-auto px-6 pt-36 pb-20">

    <div class="flex flex-col lg:flex-row items-center justify-between gap-12 max-w-8x5 mx-auto mt-3 px-15">

        <!-- Gambar -->
        <div class="w-full lg:w-1/2 flex justify-center lg:justify-start">

            <img
                src="{{ asset('assets/images/kantor_bps.png') }}"
                alt="Kantor BPS"
                class="w-[320px] lg:w-[400px] xl:w-[460px] h-auto">

        </div>

        <!-- Teks -->
        <div class="w-full lg:w-1/2 text-center lg:text-left">

           
<div class="flex items-center justify-center lg:justify-start gap-4 mb-6">

    <img
        src="{{ asset('assets/images/logoSIBUKPAK.PNG') }}"
        alt="Logo SIBUKPAK"
        class="h-16 lg:h-20 w-auto">

    <h1 class="text-5xl lg:text-7xl font-extrabold text-black">
        SIBUKPAK
    </h1>

</div>


            <p class="text-xl text-gray-700 leading-9 max-w-xl">
                Statistik Berdampak Untuk Kampus Berdampak (SIBUKPAK) 
                merupakan platform Badan Pusat Statistik Kabupaten Ogan Ilir
                yang mendukung digitalisasi proses magang mahasiswa. Melalui website ini, 
                mahasiswa dapat melakukan pendaftaran magang secara online dan 
                melakukan absensi harian secara mudah setelah diterima sebagai peserta magang di BPS Kabupaten Ogan Ilir.
            </p>

            <a
                href="{{ route('register') }}"
                class="inline-block mt-10 bg-[#043277] hover:bg-[#0D3D88] text-white text-xl px-10 py-4 rounded-2xl shadow-lg transition">

                Daftar Magang

            </a>

        </div>

    </div>

</section>